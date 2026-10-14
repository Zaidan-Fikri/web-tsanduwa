// ===== Navigasi mobile =====
function toggleMobileMenu() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
}

// ===== Modal umum =====
function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    ['brosur-modal', 'news-modal', 'rdm-modal'].forEach(id => {
        const m = document.getElementById(id);
        if (m && !m.classList.contains('hidden')) closeModal(id);
    });
});

// RDM Modal
function openRDMModal() { openModal('rdm-modal'); }
function closeRDMModal() { closeModal('rdm-modal'); }
function handleRDMLogin(e) {
    e.preventDefault();
    // Kredensial tidak pernah dikirim ke server website ini.
    const url = e.target.dataset.rdmUrl;
    e.target.reset();
    closeRDMModal();
    if (url) {
        window.open(url, '_blank', 'noopener');
    } else {
        alert('Portal RDM belum dikonfigurasi. Silakan hubungi operator madrasah.');
    }
}

// Brosur SPMB Modal
function openBrosurModal() { openModal('brosur-modal'); }
function closeBrosurModal() { closeModal('brosur-modal'); }

// News Modal: isi diambil dari <template id="news-tpl-{id}"> yang sudah di-escape server
function openNewsModal(id) {
    const tpl = document.getElementById('news-tpl-' + id);
    if (!tpl) return;
    const container = document.getElementById('news-modal-content');
    container.replaceChildren(tpl.content.cloneNode(true));
    openModal('news-modal');
}
function closeNewsModal() { closeModal('news-modal'); }

// ===== Filter Ekstrakurikuler & Prestasi =====
function filterEkskul(cat) {
    document.querySelectorAll('.ekskul-filter[data-cat]').forEach(b => b.classList.toggle('active', b.dataset.cat === cat));
    document.querySelectorAll('.ekskul-item').forEach(c => c.classList.toggle('hidden', cat !== 'semua' && c.dataset.cat !== cat));
}
function filterPrestasi(tk) {
    document.querySelectorAll('.prestasi-filter').forEach(b => b.classList.toggle('active', b.dataset.tk === tk));
    document.querySelectorAll('.prestasi-item').forEach(c => c.classList.toggle('hidden', tk !== 'semua' && c.dataset.tk !== tk));
}

// ===== Galeri (Foto / Video) =====
function switchGallery(type) {
    const fotoContainer = document.getElementById('gallery-foto-container');
    const videoContainer = document.getElementById('gallery-video-container');
    const btnFoto = document.getElementById('gal-btn-foto');
    const btnVideo = document.getElementById('gal-btn-video');
    const on = 'px-6 py-2 rounded-full font-bold text-sm bg-tosca-700 text-white shadow';
    const off = 'px-6 py-2 rounded-full font-bold text-sm bg-slate-200 text-slate-700 hover:bg-slate-300';

    fotoContainer.classList.toggle('hidden', type !== 'foto');
    videoContainer.classList.toggle('hidden', type === 'foto');
    btnFoto.className = type === 'foto' ? on : off;
    btnVideo.className = type === 'foto' ? off : on;
}

// ===== Hero Slider Beranda =====
let heroIndex = 0;
let heroTimer;
function heroShow(i) {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    if (!slides.length) return;
    heroIndex = (i + slides.length) % slides.length;
    slides.forEach((s, n) => {
        s.classList.toggle('opacity-100', n === heroIndex);
        s.classList.toggle('opacity-0', n !== heroIndex);
        s.classList.toggle('pointer-events-none', n !== heroIndex);
        // Mulai ulang animasi geser + zoom pada slide yang aktif
        const img = s.querySelector('.hero-img');
        if (img && n === heroIndex) {
            img.classList.remove('hero-anim');
            void img.offsetWidth;
            img.classList.add('hero-anim');
        }
    });
    dots.forEach((d, n) => {
        d.classList.toggle('w-8', n === heroIndex);
        d.classList.toggle('bg-gold-400', n === heroIndex);
        d.classList.toggle('w-3', n !== heroIndex);
        d.classList.toggle('bg-white/50', n !== heroIndex);
    });
    clearInterval(heroTimer);
    if (slides.length > 1) heroTimer = setInterval(() => heroShow(heroIndex + 1), 7000);
}
function heroGo(step) { heroShow(heroIndex + step); }

// Slider geser (guru, tendik, fasilitas, ekskul)
function slideTrack(id, dir) {
    const track = document.getElementById(id);
    if (!track) return;
    const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 5;
    if (dir > 0 && atEnd) track.scrollTo({ left: 0, behavior: 'smooth' });
    else track.scrollBy({ left: dir * track.clientWidth, behavior: 'smooth' });
}

// ===== Checklist kesiapan berkas SPMB =====
function updateSyarat() {
    const boxes = document.querySelectorAll('.syarat-cb');
    const wajib = document.querySelectorAll('.syarat-wajib');
    const count = document.getElementById('syarat-count');
    if (!count) return;
    const done = [...wajib].filter(c => c.checked).length;
    count.textContent = done;
    document.getElementById('syarat-bar').style.width = (wajib.length ? done / wajib.length * 100 : 0) + '%';
    const status = document.getElementById('syarat-status');
    if (wajib.length && done === wajib.length) {
        status.innerHTML = '<i class="fa-solid fa-circle-check text-tosca-600 mr-1"></i> Semua berkas wajib sudah siap. Silakan datang ke kantor madrasah!';
        status.className = 'text-sm font-semibold text-tosca-700 mt-3';
    } else {
        status.textContent = 'Masih kurang ' + (wajib.length - done) + ' berkas wajib.';
        status.className = 'text-sm text-slate-500 mt-3';
    }
    try { localStorage.setItem('spmb-syarat', JSON.stringify([...boxes].map(c => c.checked))); } catch (e) {}
}
function resetSyarat() {
    document.querySelectorAll('.syarat-cb').forEach(c => c.checked = false);
    updateSyarat();
}

document.addEventListener('DOMContentLoaded', () => {
    heroShow(0);

    if (document.getElementById('syarat-count')) {
        try {
            const saved = JSON.parse(localStorage.getItem('spmb-syarat') || '[]');
            document.querySelectorAll('.syarat-cb').forEach((c, i) => c.checked = !!saved[i]);
        } catch (e) {}
        updateSyarat();
    }
});
