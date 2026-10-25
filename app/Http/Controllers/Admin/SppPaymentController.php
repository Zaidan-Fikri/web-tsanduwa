<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppBill;
use App\Models\SppPayment;
use App\Support\Spp\AccessCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Edit tagihan satu siswa: catat pembayaran (bisa dicicil), koreksi data, dan batalkan pembayaran.
 */
class SppPaymentController extends Controller
{
    public function edit(SppBill $sppBill)
    {
        $sppBill->load(['payments.user', 'sppImport']);

        return view('admin.spp.edit', [
            'bill' => $sppBill,
            'importedAt' => $sppBill->sppImport?->created_at,
        ]);
    }

    /** Catat pembayaran: nominal per jenis tagihan mengurangi sisa tagihan. */
    public function pay(Request $request, SppBill $sppBill)
    {
        $request->merge(['amounts' => $this->digitsOnly($request->input('amounts', []))]);

        $data = $request->validate([
            'amounts' => ['required', 'array'],
            'amounts.*' => ['nullable', 'integer', 'min:0'],
            'paid_at' => ['required', 'date', 'before_or_equal:today'],
            'pay_note' => ['nullable', 'string', 'max:200'],
        ], [
            'paid_at.before_or_equal' => 'Tanggal bayar tidak boleh di masa depan.',
        ], ['paid_at' => 'tanggal bayar', 'pay_note' => 'keterangan']);

        DB::transaction(function () use ($sppBill, $data, $request) {
            $bill = SppBill::whereKey($sppBill->id)->lockForUpdate()->first();
            $items = $bill->items ?? [];
            $allocations = [];

            foreach ($items as $i => $item) {
                $paid = (int) ($data['amounts'][$i] ?? 0);
                if ($paid <= 0) {
                    continue;
                }
                if ($paid > (int) $item['amount']) {
                    throw ValidationException::withMessages([
                        "amounts.$i" => 'Pembayaran "'.$item['label'].'" melebihi sisa tagihannya ('.rupiah($item['amount']).').',
                    ]);
                }
                $items[$i]['amount'] = (int) $item['amount'] - $paid;
                $allocations[] = ['label' => $item['label'], 'amount' => $paid];
            }

            if (! $allocations) {
                throw ValidationException::withMessages(['amounts' => 'Isi nominal pembayaran minimal pada satu jenis tagihan.']);
            }

            $before = (int) $bill->total;
            $after = max(0, $before - array_sum(array_column($allocations, 'amount')));

            $bill->update(['items' => $items, 'total' => $after]);

            SppPayment::create([
                'spp_bill_id' => $bill->id,
                'type' => 'pembayaran',
                'amount' => $before - $after,
                'allocations' => $allocations,
                'total_before' => $before,
                'total_after' => $after,
                'paid_at' => $data['paid_at'],
                'note' => $data['pay_note'] ?? null,
                'user_id' => $request->user()->id,
            ]);
        });

        $sppBill->refresh();
        $message = $sppBill->isPaidOff()
            ? "Pembayaran {$sppBill->name} dicatat. Tagihan sekarang LUNAS."
            : "Pembayaran {$sppBill->name} dicatat. Sisa tagihan ".rupiah($sppBill->total).'.';

        return redirect()->route('admin.spp.edit', $sppBill)->with('success', $message);
    }

    /** Koreksi data (salah ketik di Excel, dsb.). Dicatat di riwayat sebagai "koreksi". */
    public function update(Request $request, SppBill $sppBill)
    {
        $request->merge(['items' => $this->digitsOnly($request->input('items', []))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'class_name' => ['required', 'string', 'max:10', 'regex:/^[0-9]{1,2}[A-Za-z]{0,3}$/'],
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0', 'max:1000000000'],
            'note' => ['nullable', 'string', 'max:200'],
        ], [
            'class_name.regex' => 'Format kelas contoh: 7A, 8B, 9C.',
        ], ['name' => 'nama', 'class_name' => 'kelas', 'note' => 'keterangan']);

        DB::transaction(function () use ($sppBill, $data, $request) {
            $bill = SppBill::whereKey($sppBill->id)->lockForUpdate()->first();
            $items = $bill->items ?? [];
            foreach ($items as $i => $item) {
                $items[$i]['amount'] = (int) ($data['items'][$i] ?? 0);
            }

            $before = (int) $bill->total;
            $after = array_sum(array_column($items, 'amount'));

            $bill->update([
                'name' => $data['name'],
                'class_name' => strtoupper($data['class_name']),
                'items' => $items,
                'total' => $after,
                'note' => $data['note'] ?? null,
            ]);

            if ($before !== $after) {
                SppPayment::create([
                    'spp_bill_id' => $bill->id,
                    'type' => 'koreksi',
                    'amount' => 0,
                    'total_before' => $before,
                    'total_after' => $after,
                    'paid_at' => today(),
                    'note' => 'Koreksi data tagihan',
                    'user_id' => $request->user()->id,
                ]);
            }
        });

        return redirect()->route('admin.spp.edit', $sppBill)->with('success', 'Data tagihan '.$data['name'].' diperbarui.');
    }

    /** Batalkan pembayaran yang salah input: nominalnya dikembalikan ke tagihan. */
    public function cancel(SppBill $sppBill, SppPayment $sppPayment)
    {
        abort_unless($sppPayment->spp_bill_id === $sppBill->id && $sppPayment->isPayment(), 404);

        $importedAt = $sppBill->sppImport?->created_at;
        if ($importedAt && $sppPayment->created_at->lt($importedAt)) {
            return back()->with('error', 'Pembayaran ini dicatat sebelum upload Excel terbaru (sudah tercakup di data Excel), jadi tidak bisa dibatalkan di sini.');
        }

        DB::transaction(function () use ($sppBill, $sppPayment) {
            $bill = SppBill::whereKey($sppBill->id)->lockForUpdate()->first();
            $items = $bill->items ?? [];

            foreach ($sppPayment->allocations ?? [] as $allocation) {
                $index = collect($items)->search(fn ($item) => $item['label'] === $allocation['label']);
                if ($index === false) {
                    $items[] = ['label' => $allocation['label'], 'amount' => (int) $allocation['amount']];
                } else {
                    $items[$index]['amount'] = (int) $items[$index]['amount'] + (int) $allocation['amount'];
                }
            }

            $bill->update(['items' => $items, 'total' => array_sum(array_column($items, 'amount'))]);
            $sppPayment->delete();
        });

        return back()->with('success', 'Pembayaran '.rupiah($sppPayment->amount).' dibatalkan, tagihan dikembalikan.');
    }

    /** Buat ulang kode akses (mis. kode lama bocor / tersebar). Kode lama langsung tidak berlaku. */
    public function resetCode(SppBill $sppBill)
    {
        $taken = array_fill_keys(SppBill::whereNotNull('access_code')->pluck('access_code')->all(), true);
        $sppBill->update(['access_code' => AccessCode::generate($taken)]);
        RateLimiter::clear('spp-code|'.$sppBill->nisn);

        return back()->with('success', 'Kode akses baru untuk '.$sppBill->name.': '.$sppBill->accessCodeLabel().'. Kode lama sudah tidak berlaku.');
    }

    /** "150.000" / "Rp 150.000" => 150000 (kosong tetap null). */
    private function digitsOnly(mixed $values): array
    {
        return collect(is_array($values) ? $values : [])
            ->map(fn ($v) => ($d = preg_replace('/\D/', '', (string) $v)) === '' ? null : (int) $d)
            ->all();
    }
}
