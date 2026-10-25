<?php

namespace App\Http\Controllers;

use App\Models\SppBill;
use App\Support\Spp\AccessCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Menu Informasi → Pembayaran: wali murid memasukkan NISN + kode akses untuk melihat tagihan anaknya.
 */
class PaymentController extends Controller
{
    /** Salah kode berapa kali sebelum NISN dikunci sementara. */
    private const MAX_FAILED = 5;

    /** Lama kunci per NISN (detik). */
    private const LOCK_SECONDS = 1800;

    public function show()
    {
        return view('pages.pembayaran');
    }

    public function check(Request $request)
    {
        $request->merge([
            'nisn' => preg_replace('/\D/', '', (string) $request->input('nisn')),
            'access_code' => AccessCode::normalize($request->input('access_code')),
        ]);

        $data = $request->validate([
            'nisn' => ['required', 'digits:10'],
            'access_code' => ['required', 'string', 'size:'.AccessCode::LENGTH],
        ], [
            'nisn.required' => 'Masukkan NISN siswa.',
            'nisn.digits' => 'NISN harus terdiri dari 10 angka.',
            'access_code.required' => 'Masukkan kode akses.',
            'access_code.size' => 'Kode akses terdiri dari '.AccessCode::LENGTH.' huruf/angka, contoh: K7X-2MP.',
        ]);

        // Kunci per NISN (bukan per perangkat), agar kode tidak bisa ditebak dari banyak HP/IP.
        $lockKey = 'spp-code|'.$data['nisn'];
        if (RateLimiter::tooManyAttempts($lockKey, self::MAX_FAILED)) {
            $minutes = (int) ceil(RateLimiter::availableIn($lockKey) / 60);
            throw ValidationException::withMessages([
                'access_code' => "Terlalu banyak percobaan kode akses yang salah untuk NISN ini. Coba lagi dalam {$minutes} menit, atau hubungi bagian keuangan madrasah.",
            ]);
        }

        $bill = SppBill::where('nisn', $data['nisn'])->first();

        // Pesan sama untuk NISN tidak terdaftar & kode salah, agar tidak bisa dipakai menebak NISN.
        if (! $bill || ! $bill->access_code || ! hash_equals($bill->access_code, $data['access_code'])) {
            RateLimiter::hit($lockKey, self::LOCK_SECONDS);

            throw ValidationException::withMessages([
                'access_code' => 'NISN atau kode akses tidak cocok. Periksa kembali kode akses Anda.',
            ]);
        }

        RateLimiter::clear($lockKey);

        // POST lalu langsung tampilkan hasil (NISN & kode tidak muncul di URL / riwayat browser).
        return view('pages.pembayaran', [
            'searched' => $data['nisn'],
            'bill' => $bill,
        ]);
    }
}
