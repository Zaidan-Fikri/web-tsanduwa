    <!-- Footer -->
    <footer class="bg-tosca-950 text-slate-300 pt-16 pb-8 border-t border-tosca-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-tosca-900">

                <!-- Col 1: About -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo {{ setting('school_name', 'MTs. Darul Ulum Waru') }}" class="w-14 h-14 object-contain shrink-0">
                        <span class="text-lg font-extrabold text-white">MTs. DARUL ULUM WARU</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        {{ setting('footer_about') }}
                    </p>
                    <div class="text-xs text-gold-400 font-bold">
                        Motto: Beriman – Berilmu – Berprestasi
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 border-b border-tosca-800 pb-2">Tautan Cepat</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-gold-400 transition">Beranda</a></li>
                        <li><a href="{{ route('profil.identitas') }}" class="hover:text-gold-400 transition">Profil Sekolah</a></li>
                        <li><a href="{{ route('profil.visimisi') }}" class="hover:text-gold-400 transition">Visi & Misi</a></li>
                        <li><a href="{{ route('berita') }}" class="hover:text-gold-400 transition">Berita Pengumuman</a></li>
                        <li><a href="{{ route('spmb') }}" class="hover:text-gold-400 transition">SPMB {{ setting('spmb_year') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Contact Info -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 border-b border-tosca-800 pb-2">Kontak Kami</h4>
                    <ul class="space-y-3 text-xs">
                        @if (setting('address_full'))
                            <li class="flex items-start space-x-2">
                                <i class="fa-solid fa-location-dot text-gold-400 mt-0.5"></i>
                                <a href="{{ setting('maps_link', '#') }}" target="_blank" rel="noopener noreferrer" class="hover:text-gold-400 transition">{{ setting('address_full') }} {{ setting('postal_code') }}</a>
                            </li>
                        @endif
                        @if (setting('contact_phone'))
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-phone text-gold-400"></i>
                                <a href="{{ tel_link(setting('contact_phone')) }}" class="hover:text-gold-400 transition">{{ setting('contact_phone') }}</a>
                            </li>
                        @endif
                        @if (setting('contact_whatsapp'))
                            <li class="flex items-center space-x-2">
                                <i class="fa-brands fa-whatsapp text-gold-400 text-sm"></i>
                                <a href="{{ wa_link(setting('contact_whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="hover:text-gold-400 transition">{{ setting('contact_whatsapp') }}</a>
                            </li>
                        @endif
                        @if (setting('contact_email'))
                            <li class="flex items-center space-x-2">
                                <i class="fa-solid fa-envelope text-gold-400"></i>
                                <a href="mailto:{{ setting('contact_email') }}" class="hover:text-gold-400 transition">{{ setting('contact_email') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Col 4: Map Location -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 border-b border-tosca-800 pb-2">Lokasi Sekolah</h4>
                    <div class="w-full h-40 bg-tosca-900 rounded-xl overflow-hidden relative border border-tosca-800">
                        <iframe class="w-full h-full" src="https://maps.google.com/maps?q={{ urlencode(setting('maps_query', 'MTs Darul Ulum Waru, Sidoarjo')) }}&z=17&hl=id&output=embed" title="Lokasi {{ setting('school_name', 'MTs. Darul Ulum Waru') }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" frameborder="0"></iframe>
                    </div>
                    @if (setting('maps_link'))
                        <a href="{{ setting('maps_link') }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-gold-400 hover:text-gold-500 transition"><i class="fa-solid fa-diamond-turn-right"></i> Buka di Google Maps</a>
                    @endif
                </div>
            </div>

            <!-- Copyright -->
            <div class="pt-8 text-center text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} {{ setting('school_name', 'MTs. Darul Ulum Waru') }} Sidoarjo. All rights reserved.</p>
                <div class="mt-2 sm:mt-0 space-x-4">
                    <a href="#" class="hover:text-slate-300">Privasi</a>
                    <a href="#" class="hover:text-slate-300">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
