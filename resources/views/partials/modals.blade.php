    <!-- RDM Login Portal Modal -->
    <div id="rdm-modal" onclick="if (event.target === this) closeRDMModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl border border-slate-100 relative animate-in fade-in zoom-in duration-200">
            <button type="button" onclick="closeRDMModal()" aria-label="Tutup" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-tosca-100 text-tosca-700 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-3">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900">Portal RDM</h3>
                <p class="text-xs text-slate-500 mt-1">Rapor Digital Madrasah (HDSE Kemenag)</p>
            </div>
            {{-- Form ini tidak dikirim ke server website; tombol hanya membuka Portal RDM resmi. --}}
            <form onsubmit="handleRDMLogin(event)" data-rdm-url="{{ setting('rdm_url') }}" class="space-y-4" autocomplete="off">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Username / NSM / NISN</label>
                    <input type="text" required placeholder="Masukkan Username / NSM" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Password</label>
                    <input type="password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Akses Sebagai</label>
                    <select class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-tosca-600 focus:outline-none">
                        <option>Siswa / Orang Tua</option>
                        <option>Guru / Tenaga Pendidik</option>
                        <option>Administrator</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-tosca-700 hover:bg-tosca-800 text-white font-bold py-3 rounded-xl shadow transition text-sm">
                    Masuk Portal RDM
                </button>
            </form>
        </div>
    </div>

    <!-- News Modal Pop-up -->
    <div id="news-modal" onclick="if (event.target === this) closeNewsModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-8 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="closeNewsModal()" aria-label="Tutup" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div id="news-modal-content"></div>
        </div>
    </div>
