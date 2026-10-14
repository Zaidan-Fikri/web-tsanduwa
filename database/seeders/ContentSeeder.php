<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Agenda;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\HeroSlide;
use App\Models\Mission;
use App\Models\OrgStructure;
use App\Models\Post;
use App\Models\Program;
use App\Models\Setting;
use App\Models\SpmbRequirement;
use App\Models\SpmbStep;
use App\Models\Staff;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;

/**
 * Isi awal website, diambil dari prototype1.html.
 * Hanya mengisi tabel yang masih kosong agar data yang sudah diubah admin tidak tertimpa.
 */
class ContentSeeder extends Seeder
{
    private function unsplash(string $id, int $w = 600): string
    {
        return "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w={$w}&q=80";
    }

    public function run(): void
    {
        $this->settings();
        $this->beranda();
        $this->profil();
        $this->programs();
        $this->ekskul();
        $this->informasi();
        $this->spmb();
    }

    private function settings(): void
    {
        $defaults = [
            // Beranda
            'stat_guru' => '42',
            'stat_siswa' => '650',
            'stat_rombel' => '21',
            'video_url' => 'https://www.youtube.com/@MTsDarulUlumWaru/videos',
            'video_thumbnail' => $this->unsplash('1580582932707-520aed937b7b', 1400),

            // Sambutan
            'principal_name' => 'Drs. H. Amiruddin, M.Pd.I',
            'principal_photo' => 'img/amir_mencep.png',
            'greeting_short' => "Assalamu'alaikum Warahmatullahi Wabarakatuh. Selamat datang di website resmi MTs. Darul Ulum Waru, sarana informasi, komunikasi, dan transparansi publik madrasah kami.",
            'greeting_opening' => "Assalamu'alaikum Warahmatullahi Wabarakatuh,",
            'greeting_body' => "Alhamdulillahirabbil 'alamin, puji dan syukur senantiasa kita panjatkan kehadirat Allah SWT yang telah memberikan rahmat, taufik, serta hidayah-Nya sehingga kita berada dalam kondisi sehat wal 'afiat. Shalawat serta salam senantiasa tercurahkan kepada junjungan kita Nabi Besar Muhammad SAW.\n\nMemasuki era digitalisasi pendidikan modern, MTs. Darul Ulum Waru terus berbenah dan bertransformasi guna memberikan layanan pendidikan terbaik bagi seluruh peserta didik. Kami meyakini bahwa pendidikan bukan hanya sekadar mentransfer ilmu pengetahuan (transfer of knowledge), tetapi juga menanamkan nilai-nilai luhur kepribadian dan nilai keagamaan (transfer of values).",
            'greeting_quote' => 'Dengan motto utama kami: Beriman, Berilmu, Berprestasi, madrasah bertekad melahirkan lulusan yang berakhlak mulia, cerdas secara intelektual dan emosional, serta memiliki daya saing yang tinggi untuk melanjutkan ke jenjang yang lebih tinggi.',
            'greeting_closing_body' => 'Terima kasih atas kepercayaan bapak/ibu orang tua wali siswa yang telah mempercayakan putera-puterinya untuk menimba ilmu di MTs. Darul Ulum Waru. Semoga Allah SWT senantiasa memberikan kemudahan dan kelancaran bagi kita semua.',
            'greeting_closing' => "Wassalamu'alaikum Warahmatullahi Wabarakatuh.",

            // Identitas
            'school_name' => 'MTs. Darul Ulum Waru',
            'npsn_nsm' => '20583921 / 121235150088',
            'accreditation' => 'Terakreditasi A (Unggul)',
            'ownership' => 'Swasta under Yayasan Darul Ulum',
            'address_full' => 'Jl. Kolonel Sugiono No. 101-103, Pulosari, Kureksari, Kec. Waru, Kabupaten Sidoarjo, Jawa Timur',
            'postal_code' => '61256',
            'website' => 'www.mtsdarululumwaru.sch.id',
            'sk_operational' => 'Kd.13.15/4/PP.00.4/128/2010',

            // Visi & sejarah
            'vision' => 'Terwujudnya Generasi Islami yang Beriman Teguh, Berilmu Luas, Berakhlak Mulia, dan Unggul Dalam Prestasi Berbasis Teknologi.',
            'history' => "MTs. Darul Ulum Waru didirikan pada tahun 1985 oleh para tokoh ulama dan kyai di wilayah Waru, Sidoarjo di bawah naungan Yayasan Pendidikan Darul Ulum. Pendirian madrasah ini didasari atas kepedulian terhadap pentingnya fasilitas pendidikan Islam jenjang menengah pertama yang terjangkau dan berkualitas bagi masyarakat Sidoarjo dan sekitarnya.\n\nBerawal dari gedung sederhana dengan jumlah siswa puluhan orang, kini MTs. Darul Ulum Waru telah berkembang pesat menjadi salah satu madrasah rujukan pilihan masyarakat. Saat ini madrasah telah dilengkapi gedung bertingkat modern, laboratorium komputer, perpustakaan digital, serta sarana ibadah yang memadai.",

            // SPMB
            'spmb_year' => '2027/2028',
            'spmb_badge' => 'Pendaftaran Telah Dibuka',
            'spmb_description' => 'Sistem Penerimaan Murid Baru MTs. Darul Ulum Waru. Bergabunglah menjadi bagian dari keluarga besar madrasah kami.',
            'spmb_hours' => '07.00 – 12.00 WIB',
            'spmb_wa_message' => "Assalamu'alaikum, saya ingin bertanya tentang SPMB MTs. Darul Ulum Waru.",
            'spmb_photo' => 'img/foto_spmb.png',
            'spmb_brochure' => 'img/brosur.jpeg',

            // Kontak
            'contact_phone' => '(031) 854-0767',
            'contact_whatsapp' => '0819-9844-3000',
            'contact_email' => 'tsanduwa130169@gmail.com',
            'contact_address' => 'Jl. Kolonel Sugiono No. 101-103, Kureksari, Waru, Sidoarjo',
            'footer_about' => 'Madrasah Tsanawiyah unggulan berakreditasi A di Waru, Sidoarjo. Mewujudkan pendidikan berkualitas berbasis keislaman dan teknologi modern.',
            'social_facebook' => 'https://web.facebook.com/p/MTs-DARUL-ULUM-WARU-100050485581616/',
            'social_instagram' => 'https://www.instagram.com/tsanduwa/',
            'social_youtube' => 'https://www.youtube.com/@MTsDarulUlumWaru/videos',
            'social_tiktok' => '',
            'maps_query' => 'MTs Darul Ulum Waru, Kureksari, Waru, Sidoarjo',
            'maps_link' => 'https://www.google.com/maps/place/MTs+Darul+Ulum+Waru/@-7.356347,112.7326286,17z/data=!3m1!4b1!4m6!3m5!1s0x51041f4b2f53dc7:0x5e8dbe727f85de70!8m2!3d-7.3563523!4d112.7352035!16s%2Fg%2F11b6p6nnql',
            'rdm_url' => '',
        ];

        $existing = Setting::pluck('key')->all();
        Setting::setMany(array_diff_key($defaults, array_flip($existing)));
    }

    private function beranda(): void
    {
        if (HeroSlide::doesntExist()) {
            $slides = [
                ['Mewujudkan Generasi Islami yang Beriman, Berilmu & Berprestasi', "Pendidikan madrasah berkualitas yang memadukan kurikulum nasional, nilai Ahlussunnah wal Jama'ah, dan penguasaan teknologi digital.", '1577896851231-70ef18881754', '/profil/identitas'],
                ['Siswa Raih Medali Emas KSM Kabupaten Sidoarjo', 'Bukti nyata kualitas pendampingan akademik dan pembinaan olimpiade di MTs. Darul Ulum Waru.', '1427504494785-3a9ca7044f45', '/berita/siswa-mts-darul-ulum-raih-medali-emas-ksm-kab-sidoarjo'],
                ['Pembukaan SPMB Gelombang I Tahun Ajaran 2027/2028', 'Pendaftaran dapat dilakukan secara online melalui website ini atau langsung datang ke sekretariat SPMB madrasah.', '1509062522246-3755977927d7', '/spmb'],
            ];
            foreach ($slides as $i => [$title, $desc, $img, $url]) {
                HeroSlide::create([
                    'title' => $title, 'description' => $desc, 'image' => $this->unsplash($img, 1600),
                    'button_text' => 'Selengkapnya', 'button_url' => $url, 'sort_order' => $i + 1,
                ]);
            }
        }

        if (Agenda::doesntExist()) {
            Agenda::insert([
                ['title' => 'Penilaian Akhir Semester Ganjil 2026/2027', 'start_date' => '2026-11-30', 'end_date' => '2026-12-09', 'category' => 'ujian', 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Pembagian Rapor Semester Ganjil 2026/2027', 'start_date' => '2026-12-18', 'end_date' => null, 'category' => 'semester', 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Libur Akhir Semester Ganjil 2026/2027', 'start_date' => '2026-12-21', 'end_date' => '2027-01-02', 'category' => 'libur', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (Facility::doesntExist()) {
            $items = [
                ['Laboratorium IPA', '1546410531-bb4caa6b424d'],
                ['Laboratorium Komputer', '1517694712202-14dd9538aa97'],
                ['Perpustakaan', '1521587760476-6c12a4b040da'],
                ['Ruang Kelas Nyaman', '1580582932707-520aed937b7b'],
                ['Lapangan Olahraga', '1546519638-68e109498ffc'],
            ];
            foreach ($items as $i => [$name, $img]) {
                Facility::create(['name' => $name, 'image' => $this->unsplash($img, 700), 'sort_order' => $i + 1]);
            }
        }
    }

    private function profil(): void
    {
        if (Mission::doesntExist()) {
            $missions = [
                "Menyelenggarakan pembelajaran keislaman berasas Ahlussunnah wal Jama'ah An-Nahdliyah.",
                'Meningkatkan mutu pembelajaran sains dan teknologi yang inovatif serta responsif terhadap perkembangan zaman.',
                'Mengembangkan potensi bakat dan minat siswa melalui kegiatan ekstrakurikuler terstruktur.',
                'Mewujudkan lingkungan madrasah yang bersih, ramah anak, dan bernuansa religius.',
            ];
            foreach ($missions as $i => $content) {
                Mission::create(['content' => $content, 'sort_order' => $i + 1]);
            }
        }

        if (OrgStructure::doesntExist()) {
            $rows = [
                ['Kepala Madrasah', 'Drs. H. Amiruddin, M.Pd.I', 1],
                ['Waka Kurikulum', 'Yuli Asfiah, S.Pd', 2],
                ['Waka Kesiswaan', 'M. Nur Hidayatullah, M.Ag', 2],
                ['Waka Humas', 'Zam Resa Sidiq, M.Pd', 2],
                ['Waka Sarpras', 'Saiful Hery, S.Pd', 2],
            ];
            foreach ($rows as $i => [$position, $name, $level]) {
                OrgStructure::create(['position' => $position, 'name' => $name, 'level' => $level, 'sort_order' => $i + 1]);
            }
        }

        if (WorkUnit::doesntExist()) {
            $units = [
                ['kurikulum', 'Waka Kurikulum', 'Bidang Kurikulum & Pembelajaran', 'Yuli Asfiah, S.Pd', 'img/waka_kurikulum.png', 'fa-book-open-reader',
                    'Bertanggung jawab atas perencanaan, pelaksanaan, dan evaluasi kegiatan belajar mengajar sesuai Kurikulum Merdeka Terintegrasi Kemenag.',
                    ['Pengembangan Kalender Akademik & Jadwal Pembelajaran', 'Implementasi Rapor Digital Madrasah (RDM)', 'Program Pengayaan & Pembinaan Olimpiade Sains']],
                ['kesiswaan', 'Waka Kesiswaan', 'Bidang Kesiswaan & Ekstrakurikuler', 'M. Nur Hidayatullah, M.Ag', 'img/waka_kesiswaan.png', 'fa-users',
                    'Fokus pada pembinaan karakter, pembiasaan ibadah, organisasi OSIS, dan pengembangan minat bakat peserta didik.',
                    ['Pengelolaan 15+ Ekstrakurikuler (Pramuka, Paskibra, PMR, Seni Hadrah, Robotik)', "Pelaksanaan Masa Ta'araf Siswa Madrasah (MATSAMA)", 'Kedisiplinan & Tata Tertib Siswa']],
                ['humas', 'Waka Humas', 'Bidang Hubungan Masyarakat (Humas)', 'Zam Resa Sidiq, M.Pd', 'img/waka_humas.png', 'fa-handshake',
                    'Membangun sinergi yang harmonis antara madrasah, orang tua/wali siswa, instansi kementerian agama, dan masyarakat umum.',
                    ['Komunikasi & Sosialisasi Program Sekolah ke Wali Murid', 'Kerjasama Lembaga & Studi Banding', 'Pengelolaan Publikasi & Informasi Resmi Media Sosial']],
                ['sarpras', 'Waka Sarpras', 'Bidang Sarana & Prasarana', 'Saiful Hery, S.Pd', 'img/waka_sarpras.png', 'fa-building',
                    'Menyediakan dan memelihara sarana fisik pendukung proses pembelajaran demi kenyamanan warga madrasah.',
                    ['Pemeliharaan Ruang Kelas Ber-AC, Lab Komputer, & Perpustakaan', 'Pengelolaan Masjid Madrasah & Sarana Olahraga', 'Ketersediaan Jaringan Internet Fiber Optic & WiFi Area']],
            ];
            foreach ($units as $i => [$slug, $position, $field, $person, $photo, $icon, $desc, $programs]) {
                WorkUnit::create([
                    'slug' => $slug, 'position' => $position, 'field_title' => $field, 'person_name' => $person,
                    'photo' => $photo, 'icon' => $icon, 'description' => $desc, 'programs' => $programs, 'sort_order' => $i + 1,
                ]);
            }
        }

        if (Staff::doesntExist()) {
            $people = [
                ['Drs. H. Amiruddin, M.Pd.I', 'Kepala Madrasah', 'pimpinan', 'img/amir_mencep.png', false],
                ['Yuli Asfiah, S.Pd', 'Waka Kurikulum', 'pimpinan', 'img/waka_kurikulum.png', false],
                ['M. Nur Hidayatullah, M.Ag', 'Waka Kesiswaan', 'pimpinan', 'img/waka_kesiswaan.png', false],
                ['Zam Resa Sidiq, M.Pd', 'Waka Humas', 'pimpinan', 'img/waka_humas.png', false],
                ['Saiful Hery, S.Pd', 'Waka Sarpras', 'pimpinan', 'img/waka_sarpras.png', false],
                ['Moh. Ridwan, S.Pd', 'Guru Matematika', 'guru', $this->unsplash('1507003211169-0a1dd7228f2d', 500), true],
                ['Siti Aminah, S.Pd.I', "Guru Al-Qur'an Hadits", 'guru', $this->unsplash('1494790108377-be9c29b29330', 500), true],
                ['Ahmad Syaifudin, S.Kom', 'Guru Informatika', 'guru', $this->unsplash('1500648767791-00dcc994a43e', 500), true],
                ['Nur Laila, S.Pd', 'Guru Bahasa Inggris', 'guru', $this->unsplash('1438761681033-6461ffad8d80', 500), true],
                ['Abdul Rohman, S.Ag', 'Guru Fiqih', 'guru', $this->unsplash('1472099645785-5658abf4ff4e', 500), true],
                ['Ahmad Zainuri', 'Kepala Tata Usaha', 'tendik', $this->unsplash('1539571696357-5a69c17a67c6', 500), true],
                ['Umi Kulsum', 'Bendahara', 'tendik', $this->unsplash('1524504388940-b1c1722653e1', 500), true],
                ['Rizky Hermawan', 'Operator Madrasah', 'tendik', $this->unsplash('1506794778202-cad84cf45f1d', 500), true],
                ['Maya Yuliana', 'Pustakawan', 'tendik', $this->unsplash('1531123897727-8f129e1688ce', 500), true],
                ['Hadi Suyanto', 'Administrasi Umum', 'tendik', $this->unsplash('1519085360753-af0119f7cbe7', 500), true],
            ];
            foreach ($people as $i => [$name, $position, $type, $photo, $home]) {
                Staff::create([
                    'name' => $name, 'position' => $position, 'type' => $type, 'photo' => $photo,
                    'show_on_home' => $home, 'sort_order' => $i + 1,
                ]);
            }
        }
    }

    private function programs(): void
    {
        if (Program::exists()) {
            return;
        }

        $programs = [
            [
                'slug' => 'btq', 'menu_name' => 'BTQ & Tahfidz', 'title' => "Baca Tulis Al-Qur'an & Tahfidz", 'icon' => 'fa-book-quran',
                'description' => "Program pembinaan membaca dan menulis Al-Qur'an dengan tartil sesuai kaidah tajwid, dilanjutkan hafalan juz 30 dan surat-surat pilihan.",
                'activities' => [
                    ['icon' => 'fa-spell-check', 'title' => 'Tahsin & Tajwid', 'description' => "Perbaikan bacaan Al-Qur'an secara bertahap sesuai kaidah tajwid."],
                    ['icon' => 'fa-pen-nib', 'title' => "Tulis Al-Qur'an", 'description' => "Latihan menulis huruf hijaiyah dan ayat Al-Qur'an dengan benar."],
                    ['icon' => 'fa-brain', 'title' => 'Setoran Hafalan', 'description' => 'Target hafalan juz 30 dengan setoran rutin kepada pembimbing.'],
                ],
                'infos' => [
                    ['icon' => 'fa-calendar-days', 'text' => 'Setiap hari, 06.45 – 07.15 WIB'],
                    ['icon' => 'fa-users', 'text' => 'Seluruh siswa kelas VII – IX'],
                    ['icon' => 'fa-award', 'text' => 'Wisuda Tahfidz akhir tahun'],
                ],
            ],
            [
                'slug' => 'kitab', 'menu_name' => 'Kajian Kitab', 'title' => 'Kajian Kitab Kuning', 'icon' => 'fa-book-open',
                'description' => "Pengenalan dan kajian kitab-kitab dasar karya ulama Ahlussunnah wal Jama'ah untuk memperkuat pemahaman akidah, fiqih, dan akhlak.",
                'activities' => [
                    ['icon' => 'fa-scroll', 'title' => 'Kitab Akhlak', 'description' => "Kajian kitab Ta'lim Muta'allim tentang adab menuntut ilmu."],
                    ['icon' => 'fa-scale-balanced', 'title' => 'Kitab Fiqih', 'description' => 'Kajian fiqih ibadah sehari-hari dari kitab Safinatun Najah.'],
                    ['icon' => 'fa-language', 'title' => 'Nahwu & Shorof', 'description' => 'Dasar tata bahasa Arab untuk membaca kitab gundul.'],
                ],
                'infos' => [
                    ['icon' => 'fa-calendar-days', 'text' => "Setiap Jum'at pagi"],
                    ['icon' => 'fa-users', 'text' => 'Seluruh siswa kelas VII – IX'],
                    ['icon' => 'fa-mosque', 'text' => 'Bertempat di Masjid Madrasah'],
                ],
            ],
            [
                'slug' => 'olimpiade', 'menu_name' => 'Olimpiade Academic Squad', 'title' => 'Olimpiade Academic Squad', 'icon' => 'fa-medal',
                'description' => 'Kelompok pembinaan siswa berbakat akademik untuk mengikuti kompetisi sains dan olimpiade tingkat kabupaten, provinsi, hingga nasional.',
                'activities' => [
                    ['icon' => 'fa-square-root-variable', 'title' => 'Matematika', 'description' => 'Pembinaan soal-soal olimpiade dan KSM bidang Matematika.'],
                    ['icon' => 'fa-flask', 'title' => 'IPA Terpadu', 'description' => 'Pendalaman materi fisika, kimia, dan biologi tingkat lanjut.'],
                    ['icon' => 'fa-earth-asia', 'title' => 'IPS Terpadu', 'description' => 'Persiapan kompetisi bidang ekonomi, geografi, dan sejarah.'],
                ],
                'infos' => [
                    ['icon' => 'fa-calendar-days', 'text' => 'Selasa & Kamis, 13.00 – 15.00 WIB'],
                    ['icon' => 'fa-user-check', 'text' => 'Siswa terpilih hasil seleksi'],
                    ['icon' => 'fa-trophy', 'text' => 'Persiapan KSM, OSN & lomba lainnya'],
                ],
            ],
            [
                'slug' => 'tatakrama', 'menu_name' => 'Tata Krama Siswa', 'title' => 'Tata Krama Siswa', 'icon' => 'fa-hands-praying',
                'description' => 'Program pembiasaan akhlakul karimah dalam kehidupan sehari-hari di madrasah, rumah, dan masyarakat.',
                'activities' => [
                    ['icon' => 'fa-handshake', 'title' => 'Budaya 5S', 'description' => 'Senyum, Sapa, Salam, Sopan, dan Santun kepada seluruh warga madrasah.'],
                    ['icon' => 'fa-person-praying', 'title' => 'Sholat Berjamaah', 'description' => 'Pembiasaan sholat Dhuha dan Dhuhur berjamaah setiap hari.'],
                    ['icon' => 'fa-clipboard-check', 'title' => 'Tata Tertib', 'description' => 'Pembinaan kedisiplinan, kerapian, dan tanggung jawab siswa.'],
                ],
                'infos' => [
                    ['icon' => 'fa-calendar-days', 'text' => 'Diterapkan setiap hari'],
                    ['icon' => 'fa-users', 'text' => 'Seluruh siswa kelas VII – IX'],
                    ['icon' => 'fa-star', 'text' => 'Apresiasi siswa teladan'],
                ],
            ],
            [
                'slug' => 'lazisnu', 'menu_name' => 'Lazisnu Unit MTs', 'title' => 'Lazisnu Unit MTs', 'icon' => 'fa-hand-holding-heart',
                'description' => 'Unit layanan zakat, infaq, dan shodaqoh NU di lingkungan madrasah untuk menumbuhkan jiwa sosial dan kepedulian siswa.',
                'activities' => [
                    ['icon' => 'fa-coins', 'title' => "Infaq Jum'at", 'description' => "Penghimpunan infaq sukarela siswa dan guru setiap hari Jum'at."],
                    ['icon' => 'fa-box-open', 'title' => 'Santunan Sosial', 'description' => 'Penyaluran bantuan untuk anak yatim dan warga yang membutuhkan.'],
                    ['icon' => 'fa-kit-medical', 'title' => 'Peduli Bencana', 'description' => 'Penggalangan dana kemanusiaan saat terjadi musibah.'],
                ],
                'infos' => [
                    ['icon' => 'fa-calendar-days', 'text' => "Setiap hari Jum'at"],
                    ['icon' => 'fa-people-group', 'text' => 'Dikelola pengurus Lazisnu & OSIS'],
                    ['icon' => 'fa-file-invoice', 'text' => 'Laporan transparan setiap bulan'],
                ],
            ],
        ];

        foreach ($programs as $i => $program) {
            Program::create($program + ['sort_order' => $i + 1]);
        }
    }

    private function ekskul(): void
    {
        if (Extracurricular::exists()) {
            return;
        }

        $items = [
            ['Pramuka', 'sosial', 'fa-campground', 'Melatih kemandirian, kepemimpinan, dan cinta alam melalui kegiatan kepramukaan.', 'Jumat, 13.00 WIB', '1504280390367-361c6d9f38f4'],
            ["Qiro'ah", 'agama', 'fa-book-quran', "Pembinaan seni membaca Al-Qur'an dengan lagu dan irama yang indah sesuai kaidah.", 'Senin, 13.00 WIB', '1497633762265-9d179a990aa6'],
            ['Banjari', 'agama', 'fa-drum', 'Seni sholawat diiringi rebana khas banjari untuk syiar dan berbagai perlombaan.', 'Rabu, 13.00 WIB', null],
            ['PMR', 'sosial', 'fa-kit-medical', 'Palang Merah Remaja: pertolongan pertama, kesehatan, dan kepedulian sosial.', 'Kamis, 13.00 WIB', null],
            ['Paduan Suara', 'agama', 'fa-music', 'Mengasah vokal dan harmoni untuk tampil di upacara dan acara madrasah.', 'Selasa, 13.00 WIB', null],
            ['PASKIBRA', 'sosial', 'fa-flag', 'Pasukan Pengibar Bendera: melatih kedisiplinan, baris-berbaris, dan nasionalisme.', 'Sabtu, 07.00 WIB', null],
            ['Drum Band', 'agama', 'fa-drum-steelpan', 'Kelompok musik barisan yang tampil di karnaval, upacara, dan perlombaan.', 'Sabtu, 08.00 WIB', null],
            ['Badminton', 'olahraga', 'fa-feather', 'Latihan teknik dasar hingga strategi permainan bulu tangkis untuk kompetisi.', 'Selasa, 14.00 WIB', '1546519638-68e109498ffc'],
            ['Futsal', 'olahraga', 'fa-futbol', 'Mengembangkan kerja sama tim, teknik, dan sportivitas di lapangan futsal.', 'Kamis, 14.00 WIB', '1431324155629-1a6deb1dec8d'],
            ['Karate', 'olahraga', 'fa-hand-fist', 'Seni bela diri untuk melatih fisik, mental, dan kedisiplinan diri.', 'Rabu, 14.00 WIB', null],
        ];

        foreach ($items as $i => [$name, $cat, $icon, $desc, $schedule, $img]) {
            Extracurricular::create([
                'name' => $name, 'category' => $cat, 'icon' => $icon, 'description' => $desc, 'schedule' => $schedule,
                'image' => $img ? $this->unsplash($img) : null, 'show_on_home' => (bool) $img, 'sort_order' => $i + 1,
            ]);
        }
    }

    private function informasi(): void
    {
        if (Post::doesntExist()) {
            $posts = [
                ['Pembukaan SPMB Gelombang I Tahun Ajaran 2027/2028', 'pengumuman', '2026-10-10', '1523240795612-9a054b0db644',
                    'Sistem Penerimaan Murid Baru (SPMB) MTs. Darul Ulum Waru resmi dibuka secara online maupun offline.',
                    'Panitia Sistem Penerimaan Murid Baru (SPMB) MTs. Darul Ulum Waru secara resmi membuka pendaftaran Gelombang I. Pendaftaran dapat dilakukan secara online melalui website ini atau langsung datang ke sekretariat pendaftaran di Kampus MTs Darul Ulum Waru. Dapatkan potongan biaya pendaftaran bagi pendaftar 50 pertama!'],
                ['Siswa MTs Darul Ulum Raih Medali Emas KSM Kab. Sidoarjo', 'prestasi', '2026-10-02', '1511632765486-a01980e01a18',
                    'Siswa-siswi MTs Darul Ulum Waru kembali mengukir prestasi membanggakan pada ajang Kompetisi Sains Madrasah.',
                    'Selamat dan sukses kepada ananda Ahmad Rayhan yang meraih Juara 1 (Medali Emas) pada Kompetisi Sains Madrasah (KSM) bidang Matematika Terintegrasi tingkat Kabupaten Sidoarjo. Prestasi ini membuktikan kualitas pendampingan akademik di madrasah kita.'],
                ["Peringatan Hari Besar Islam & Khotmil Qur'an", 'kegiatan', '2026-09-25', '1544717305-2782549b5136',
                    "Rangkaian kegiatan peringatan hari besar Islam berlangsung khidmah diisi dengan pembacaan sholawat & khotmil Qur'an.",
                    "Seluruh sivitas akademika MTs Darul Ulum Waru menyelenggarakan peringatan Hari Besar Islam yang diisi dengan kegiatan pembacaan Sholawat Diba', Khotmil Qur'an 30 Juz, serta santunan anak yatim. Kegiatan ini bertujuan memperkuat karakter religius para siswa."],
            ];
            foreach ($posts as [$title, $cat, $date, $img, $excerpt, $content]) {
                Post::create([
                    'title' => $title, 'slug' => Post::uniqueSlug($title), 'category' => $cat, 'published_at' => $date,
                    'image' => $this->unsplash($img, 700), 'excerpt' => $excerpt, 'content' => $content, 'is_published' => true,
                ]);
            }
        }

        if (Achievement::doesntExist()) {
            $items = [
                ['Kompetisi Sains Madrasah (KSM) Matematika Terintegrasi', 'Ahmad Rayhan', 'Juara 1', 'kabupaten', 'Akademik', 'fa-square-root-variable', 2026, '1427504494785-3a9ca7044f45', true],
                ['MTQ Pelajar Cabang Tilawah', 'Fatimah Azzahra', 'Juara 2', 'provinsi', 'Keagamaan', 'fa-book-quran', 2025, '1580582932707-520aed937b7b', false],
                ['Kejuaraan Futsal Antar MTs se-Sidoarjo', 'Tim Futsal Putra', 'Juara 1', 'kabupaten', 'Olahraga', 'fa-futbol', 2025, '1431324155629-1a6deb1dec8d', false],
                ['Lomba Tahfidz Juz 30', 'Nur Aisyah', 'Juara 2', 'kabupaten', 'Keagamaan', 'fa-book-open', 2025, null, false],
                ['Lomba Tahfidz Juz 30', 'M. Fahmi', 'Juara 3', 'kabupaten', 'Keagamaan', 'fa-book-open', 2025, null, false],
                ['Lomba Pramuka Penggalang (LT II)', 'Regu Elang', 'Juara 1', 'kecamatan', 'Kepramukaan', 'fa-campground', 2025, null, false],
                ['Kejuaraan Karate Pelajar', 'Rizky Pratama', 'Juara 2', 'provinsi', 'Bela Diri', 'fa-hand-fist', 2025, null, false],
                ['Olimpiade Sains Online Tingkat SMP/MTs', 'Salsabila Putri', 'Juara 3', 'nasional', 'Akademik', 'fa-flask', 2024, null, false],
                ['Festival Banjari Pelajar', 'Tim Banjari', 'Juara 1', 'kabupaten', 'Seni', 'fa-drum', 2024, null, false],
            ];
            foreach ($items as [$title, $winner, $rank, $level, $cat, $icon, $year, $img, $featured]) {
                Achievement::create([
                    'title' => $title, 'winner' => $winner, 'rank' => $rank, 'level' => $level, 'category' => $cat,
                    'icon' => $icon, 'year' => $year, 'image' => $img ? $this->unsplash($img) : null,
                    'is_featured' => $featured, 'show_on_home' => (bool) $img,
                ]);
            }
        }

        if (Gallery::doesntExist()) {
            $photos = [
                ['Kegiatan Pembelajaran Berbasis ICT di Lab Komputer', '1509062522246-3755977927d7'],
                ['Upacara Bendera Hari Senin & Pembinaan Kedisiplinan', '1577896851231-70ef18881754'],
                ['Kegiatan Perkemahan & Outbound Pramuka Penggalang', '1529156069898-49953e39b3ac'],
            ];
            foreach ($photos as $i => [$title, $img]) {
                Gallery::create(['type' => 'foto', 'title' => $title, 'image' => $this->unsplash($img), 'sort_order' => $i + 1]);
            }
            Gallery::create(['type' => 'video', 'title' => 'Profil Resmi MTs. Darul Ulum Waru', 'youtube_id' => 'dQw4w9WgXcQ', 'sort_order' => 1]);
            Gallery::create(['type' => 'video', 'title' => 'Keseruan MATSAMA Siswa Baru', 'youtube_id' => 'dQw4w9WgXcQ', 'sort_order' => 2]);
        }
    }

    private function spmb(): void
    {
        if (SpmbStep::doesntExist()) {
            $steps = [
                ['Pembelian Formulir', 'Datang ke kantor sekolah dan membeli formulir.', 'fa-file-invoice', null, false],
                ['Pengembalian Formulir', 'Setelah data formulir diisi lengkap sesuai data yang diperlukan.', 'fa-file-circle-check', null, false],
                ['Registrasi Pendaftaran (SPMB)', 'Pembayaran langsung ke loket pembayaran sekolah.', 'fa-cash-register', '07.00 – 12.00 WIB', false],
                ['Pembelian Seragam Sekolah', "Pembelian seragam di Koperasi Sekolah Ma'arif Mart.", 'fa-shirt', null, true],
            ];
            foreach ($steps as $i => [$title, $desc, $icon, $badge, $highlight]) {
                SpmbStep::create([
                    'title' => $title, 'description' => $desc, 'icon' => $icon, 'badge' => $badge,
                    'is_highlight' => $highlight, 'sort_order' => $i + 1,
                ]);
            }
        }

        if (SpmbRequirement::doesntExist()) {
            $items = [
                ['Print Out NISN', true],
                ['Fotokopi KTP Ayah dan Ibu', true],
                ['Fotokopi Kartu Keluarga', true],
                ['Fotokopi Akta Kelahiran', true],
                ['Fotokopi KTP Wali', false],
                ['Fotokopi Ijazah Mengaji / Syahadah TPQ', false],
                ['Fotokopi KIP / KKS', false],
                ['Fotokopi Sertifikat Juara Lomba / Olimpiade Tingkat Kabupaten', false],
                ['Surat Keterangan Peringkat 1–3 Paralel', false],
            ];
            foreach ($items as $i => [$name, $required]) {
                SpmbRequirement::create(['name' => $name, 'is_required' => $required, 'sort_order' => $i + 1]);
            }
        }
    }
}
