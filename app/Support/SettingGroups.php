<?php

namespace App\Support;

/**
 * Daftar halaman "pengaturan" di panel admin beserta field-nya.
 * Setiap field disimpan di tabel settings dengan key yang sama.
 *
 * Tipe field: text, textarea, number, email, url, image.
 */
class SettingGroups
{
    public static function all(): array
    {
        return [
            'beranda' => [
                'title' => 'Pengaturan Beranda',
                'icon' => 'fa-house',
                'description' => 'Statistik madrasah dan video profil yang tampil di halaman Beranda.',
                'fields' => [
                    'stat_guru' => ['label' => 'Jumlah Guru & Tenaga Kependidikan', 'type' => 'number'],
                    'stat_siswa' => ['label' => 'Jumlah Peserta Didik', 'type' => 'number'],
                    'stat_rombel' => ['label' => 'Jumlah Rombel', 'type' => 'number'],
                    'video_url' => ['label' => 'Link Video Profil / Channel YouTube', 'type' => 'url', 'help' => 'Contoh: https://www.youtube.com/@MTsDarulUlumWaru/videos'],
                    'video_thumbnail' => ['label' => 'Gambar Sampul Video', 'type' => 'image'],
                ],
            ],
            'sambutan' => [
                'title' => 'Sambutan Kepala Madrasah',
                'icon' => 'fa-user-tie',
                'description' => 'Nama, foto, dan isi sambutan kepala madrasah.',
                'fields' => [
                    'principal_name' => ['label' => 'Nama Kepala Madrasah', 'type' => 'text', 'required' => true],
                    'principal_photo' => ['label' => 'Foto Kepala Madrasah', 'type' => 'image', 'help' => 'Disarankan PNG latar transparan, potret (tegak).'],
                    'greeting_short' => ['label' => 'Sambutan Singkat (Beranda)', 'type' => 'textarea', 'rows' => 3],
                    'greeting_opening' => ['label' => 'Salam Pembuka', 'type' => 'text'],
                    'greeting_body' => ['label' => 'Isi Sambutan', 'type' => 'textarea', 'rows' => 10, 'help' => 'Pisahkan paragraf dengan satu baris kosong.'],
                    'greeting_quote' => ['label' => 'Kutipan / Motto (kotak hijau)', 'type' => 'textarea', 'rows' => 3],
                    'greeting_closing_body' => ['label' => 'Paragraf Penutup', 'type' => 'textarea', 'rows' => 4],
                    'greeting_closing' => ['label' => 'Salam Penutup', 'type' => 'text'],
                ],
            ],
            'identitas' => [
                'title' => 'Profil & Identitas Sekolah',
                'icon' => 'fa-id-card',
                'description' => 'Data identitas resmi madrasah.',
                'fields' => [
                    'school_name' => ['label' => 'Nama Madrasah', 'type' => 'text', 'required' => true],
                    'npsn_nsm' => ['label' => 'NPSN / NSM', 'type' => 'text'],
                    'accreditation' => ['label' => 'Status Akreditasi', 'type' => 'text'],
                    'ownership' => ['label' => 'Status Kepemilikan', 'type' => 'text'],
                    'address_full' => ['label' => 'Alamat Lengkap', 'type' => 'textarea', 'rows' => 2],
                    'postal_code' => ['label' => 'Kode Pos', 'type' => 'text'],
                    'website' => ['label' => 'Website', 'type' => 'text'],
                    'sk_operational' => ['label' => 'SK Izin Operasional', 'type' => 'text'],
                ],
            ],
            'visi' => [
                'title' => 'Visi Madrasah',
                'icon' => 'fa-eye',
                'description' => 'Visi utama madrasah.',
                'fields' => [
                    'vision' => ['label' => 'Visi Utama', 'type' => 'textarea', 'rows' => 3, 'required' => true],
                ],
            ],
            'sejarah' => [
                'title' => 'Sejarah Madrasah',
                'icon' => 'fa-landmark',
                'description' => 'Sejarah singkat berdirinya madrasah.',
                'fields' => [
                    'history' => ['label' => 'Isi Sejarah', 'type' => 'textarea', 'rows' => 12, 'required' => true, 'help' => 'Pisahkan paragraf dengan satu baris kosong.'],
                ],
            ],
            'spmb' => [
                'title' => 'Pengaturan SPMB',
                'icon' => 'fa-user-plus',
                'description' => 'Informasi utama halaman SPMB (Sistem Penerimaan Murid Baru).',
                'fields' => [
                    'spmb_year' => ['label' => 'Tahun Ajaran', 'type' => 'text', 'required' => true, 'help' => 'Contoh: 2027/2028'],
                    'spmb_badge' => ['label' => 'Status Pendaftaran', 'type' => 'text', 'help' => 'Contoh: Pendaftaran Telah Dibuka'],
                    'spmb_description' => ['label' => 'Deskripsi Singkat', 'type' => 'textarea', 'rows' => 3],
                    'spmb_hours' => ['label' => 'Jam Layanan Pendaftaran', 'type' => 'text', 'help' => 'Contoh: 07.00 – 12.00 WIB'],
                    'spmb_wa_message' => ['label' => 'Pesan Otomatis WhatsApp', 'type' => 'text'],
                    'spmb_photo' => ['label' => 'Foto Banner SPMB', 'type' => 'image', 'help' => 'PNG latar transparan agar menyatu dengan banner.'],
                    'spmb_brochure' => ['label' => 'Gambar Brosur', 'type' => 'image'],
                ],
            ],
            'kontak' => [
                'title' => 'Kontak & Media Sosial',
                'icon' => 'fa-address-book',
                'description' => 'Kontak di top bar dan footer, media sosial, peta lokasi, serta link Portal RDM.',
                'fields' => [
                    'contact_phone' => ['label' => 'Telepon', 'type' => 'text'],
                    'contact_whatsapp' => ['label' => 'WhatsApp', 'type' => 'text', 'help' => 'Contoh: 0819-9844-3000'],
                    'contact_email' => ['label' => 'Email', 'type' => 'email'],
                    'contact_address' => ['label' => 'Alamat Singkat (top bar & SPMB)', 'type' => 'text'],
                    'footer_about' => ['label' => 'Deskripsi Footer', 'type' => 'textarea', 'rows' => 3],
                    'social_facebook' => ['label' => 'Facebook', 'type' => 'url'],
                    'social_instagram' => ['label' => 'Instagram', 'type' => 'url'],
                    'social_youtube' => ['label' => 'YouTube', 'type' => 'url'],
                    'social_tiktok' => ['label' => 'TikTok', 'type' => 'url'],
                    'maps_query' => ['label' => 'Kata Kunci Peta (Google Maps)', 'type' => 'text', 'help' => 'Contoh: MTs Darul Ulum Waru, Kureksari, Waru, Sidoarjo'],
                    'maps_link' => ['label' => 'Link Buka di Google Maps', 'type' => 'url'],
                    'rdm_url' => ['label' => 'Link Portal RDM', 'type' => 'url', 'help' => 'Jika diisi, tombol "Portal RDM" di navbar langsung membuka alamat ini (tanpa form login).'],
                ],
            ],
            'pembayaran' => [
                'title' => 'Info Pembayaran',
                'icon' => 'fa-wallet',
                'description' => 'Informasi cara membayar yang tampil di halaman cek tagihan (menu Informasi → Pembayaran).',
                'fields' => [
                    'spp_call_center' => ['label' => 'Call Center Pembayaran', 'type' => 'text', 'help' => 'Nomor HP/WA petugas, contoh: 0822-3240-0303 (Bu Itaul). Otomatis terisi dari footer file Excel.'],
                    'spp_bank_account' => ['label' => 'Rekening Transfer', 'type' => 'text', 'help' => 'Contoh: BCA 7205002715 a.n. Itaul Khusnah. Otomatis terisi dari footer file Excel.'],
                    'spp_notes' => ['label' => 'Catatan untuk Wali Murid', 'type' => 'textarea', 'rows' => 3, 'help' => 'Contoh: Setelah transfer, kirim bukti pembayaran ke call center beserta nama & kelas siswa.'],
                ],
            ],
        ];
    }

    public static function find(string $group): ?array
    {
        return static::all()[$group] ?? null;
    }

    /**
     * Aturan validasi untuk satu grup.
     */
    public static function rules(string $group): array
    {
        $rules = [];

        foreach (static::find($group)['fields'] as $key => $field) {
            $base = ! empty($field['required']) ? ['required'] : ['nullable'];

            $rules[$key] = array_merge($base, match ($field['type']) {
                'number' => ['integer', 'min:0', 'max:1000000'],
                'email' => ['email:rfc', 'max:150'],
                'url' => ['url:http,https', 'max:255'],
                'textarea' => ['string', 'max:20000'],
                default => ['string', 'max:255'],
            });

            if ($field['type'] === 'image') {
                $rules[$key] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240', 'dimensions:max_width=12000,max_height=12000'];
            }
        }

        return $rules;
    }
}
