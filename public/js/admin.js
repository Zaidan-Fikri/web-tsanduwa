// ===== Sidebar (mobile) =====
function toggleSidebar(open) {
    document.getElementById('admin-sidebar').classList.toggle('-translate-x-full', !open);
    document.getElementById('admin-sidebar-backdrop').classList.toggle('hidden', !open);
}

document.addEventListener('DOMContentLoaded', () => {
    // ===== Dropdown akun =====
    document.querySelectorAll('[data-dropdown]').forEach(dd => {
        const menu = dd.querySelector('[data-dropdown-menu]');
        dd.querySelector('[data-dropdown-toggle]').addEventListener('click', e => {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });
        document.addEventListener('click', () => menu.classList.add('hidden'));
    });

    // ===== Notifikasi hilang otomatis =====
    document.querySelectorAll('[data-toast]').forEach(toast => {
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 4500);
    });

    // ===== Konfirmasi hapus =====
    const modal = document.getElementById('confirm-modal');
    let pendingForm = null;
    const closeConfirm = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); pendingForm = null; };
    // Didaftarkan di document agar tetap berlaku untuk baris tabel hasil pencarian langsung.
    document.addEventListener('submit', e => {
        const form = e.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed) return;
        e.preventDefault();
        pendingForm = form;
        document.getElementById('confirm-message').textContent = form.dataset.confirm;
        document.getElementById('confirm-title').textContent = form.dataset.confirmTitle || 'Yakin ingin menghapus?';
        document.getElementById('confirm-ok-label').textContent = form.dataset.confirmButton || 'Ya, Hapus';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });
    modal.querySelector('[data-confirm-cancel]').addEventListener('click', closeConfirm);
    modal.addEventListener('click', e => { if (e.target === modal) closeConfirm(); });
    modal.querySelector('[data-confirm-ok]').addEventListener('click', () => {
        if (!pendingForm) return;
        pendingForm.dataset.confirmed = '1';
        pendingForm.submit();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeConfirm(); });

    // ===== Kompres + preview gambar sebelum upload =====
    document.querySelectorAll('[data-image-field]').forEach(field => {
        const input = field.querySelector('[data-image-input]');
        const preview = field.querySelector('[data-preview]');
        const placeholder = field.querySelector('[data-placeholder]');
        const filename = field.querySelector('[data-filename]');
        const form = input.closest('form');
        const submits = form ? form.querySelectorAll('button[type=submit]') : [];
        const maxSize = parseInt(field.dataset.maxSize || '1920', 10);

        input.addEventListener('change', async () => {
            const original = input.files[0];
            if (!original) return;
            if (original.size > 30 * 1024 * 1024) {
                alert('Ukuran gambar terlalu besar (maksimal 30 MB sebelum dikompres).');
                input.value = '';
                return;
            }

            // Kunci tombol simpan selama proses kompres agar file asli tidak terkirim.
            submits.forEach(b => { b.disabled = true; b.classList.add('opacity-60', 'pointer-events-none'); });
            filename.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-tosca-600 mr-1"></i> Mengompres gambar...';

            const file = await compressImage(original, maxSize);
            if (file !== original) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
            }

            submits.forEach(b => { b.disabled = false; b.classList.remove('opacity-60', 'pointer-events-none'); });

            if (file.size > 10 * 1024 * 1024) {
                alert('Gambar masih lebih dari 10 MB setelah dikompres. Silakan pilih gambar lain.');
                input.value = '';
                filename.textContent = 'Format JPG, PNG, atau WEBP. Maksimal 10 MB.';
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            filename.textContent = file === original
                ? `${original.name} (${formatBytes(original.size)})`
                : `${original.name}: ${formatBytes(original.size)} → ${formatBytes(file.size)} (sudah dikompres)`;
        });
    });

    initIconPickers(document);
    initRepeaters();

    // ===== Peringatan jika meninggalkan form yang belum disimpan =====
    document.querySelectorAll('form[data-dirty-check]').forEach(form => {
        let dirty = false;
        form.addEventListener('input', () => { dirty = true; });
        form.addEventListener('change', () => { dirty = true; });
        form.addEventListener('submit', () => { dirty = false; });
        window.addEventListener('beforeunload', e => {
            if (dirty) { e.preventDefault(); e.returnValue = ''; }
        });
    });
});

// ===== Pemilih ikon =====
function initIconPickers(root) {
    root.querySelectorAll('[data-icon-picker]').forEach(picker => {
        if (picker.dataset.ready) return;
        picker.dataset.ready = '1';
        const grid = picker.querySelector('[data-icon-grid]');
        const input = picker.querySelector('[data-icon-input]');
        const preview = picker.querySelector('[data-icon-preview] i');
        const toggle = e => { e.stopPropagation(); grid.classList.toggle('hidden'); };
        picker.querySelector('[data-icon-toggle]').addEventListener('click', toggle);
        input.addEventListener('click', toggle);
        grid.addEventListener('click', e => e.stopPropagation());
        document.addEventListener('click', () => grid.classList.add('hidden'));
        grid.querySelectorAll('[data-icon]').forEach(btn => {
            btn.addEventListener('click', () => {
                const icon = btn.dataset.icon;
                input.value = icon;
                preview.className = 'fa-solid ' + icon;
                grid.querySelectorAll('[data-icon]').forEach(b => {
                    const on = b === btn;
                    b.classList.toggle('bg-tosca-700', on);
                    b.classList.toggle('text-white', on);
                });
                grid.classList.add('hidden');
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    });
}

// ===== Daftar isian dinamis (kegiatan, informasi, program kerja) =====
function initRepeaters() {
    document.querySelectorAll('[data-repeater]').forEach(rep => {
        const list = rep.querySelector('[data-repeater-list]');
        const tpl = rep.querySelector('template');
        const renumber = () => {
            list.querySelectorAll('[data-repeater-item]').forEach((row, i) => {
                const no = row.querySelector('[data-repeater-no]');
                if (no) no.textContent = i + 1;
            });
            const empty = rep.querySelector('[data-repeater-empty]');
            if (empty) empty.classList.toggle('hidden', list.children.length > 0);
        };
        rep.querySelector('[data-repeater-add]').addEventListener('click', () => {
            const key = Date.now().toString(36) + Math.random().toString(36).slice(2, 6);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = tpl.innerHTML.replaceAll('__KEY__', key).trim();
            const row = wrapper.firstElementChild;
            list.appendChild(row);
            initIconPickers(row);
            renumber();
            const first = row.querySelector('input[type=text]:not([readonly]), textarea');
            if (first) first.focus();
        });
        list.addEventListener('click', e => {
            const btn = e.target.closest('[data-repeater-remove]');
            if (!btn) return;
            btn.closest('[data-repeater-item]').remove();
            renumber();
            rep.closest('form')?.dispatchEvent(new Event('change'));
        });
        renumber();
    });
}

// ===== Kompres gambar di browser sebelum diupload =====
// Gambar diperkecil (sisi terpanjang <= maxSize) lalu diubah ke WebP. Server tetap
// mengompres lagi sesuai kebutuhan tiap menu. Jika browser tidak mendukung, file asli dipakai.
async function compressImage(file, maxSize = 1920, quality = 0.85) {
    if (!/^image\/(jpeg|png|webp)$/.test(file.type) || !window.createImageBitmap) return file;

    try {
        // imageOrientation: foto HP yang tersimpan miring otomatis diputar sesuai aslinya.
        const bitmap = await createImageBitmap(file, { imageOrientation: 'from-image' });
        const scale = Math.min(1, maxSize / Math.max(bitmap.width, bitmap.height));
        const width = Math.max(1, Math.round(bitmap.width * scale));
        const height = Math.max(1, Math.round(bitmap.height * scale));

        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.imageSmoothingQuality = 'high';
        ctx.drawImage(bitmap, 0, 0, width, height);
        if (bitmap.close) bitmap.close();

        let blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/webp', quality));
        let ext = 'webp';
        // Browser lama tanpa dukungan WebP: pakai JPEG untuk foto tanpa transparansi.
        if (!blob || blob.type !== 'image/webp') {
            if (file.type !== 'image/jpeg') return file;
            blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
            ext = 'jpg';
        }

        if (!blob || blob.size >= file.size) return file;

        const name = file.name.replace(/\.[^.]+$/, '') + '.' + ext;
        return new File([blob], name, { type: blob.type, lastModified: Date.now() });
    } catch (e) {
        return file;
    }
}

function formatBytes(bytes) {
    return bytes >= 1024 * 1024 ? (bytes / 1024 / 1024).toFixed(1) + ' MB' : Math.max(1, Math.round(bytes / 1024)) + ' KB';
}

// ===== Pencarian langsung (tanpa klik tombol Cari) =====
// Form dengan [data-live-search] memuat ulang isi [data-live-results] saat mengetik / mengganti filter.
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-live-search]').forEach(form => {
        const results = document.querySelector('[data-live-results]');
        if (!results || !window.fetch) return;

        const reset = form.querySelector('[data-live-reset]');
        let timer = null;
        let controller = null;

        const run = async () => {
            const params = new URLSearchParams();
            new FormData(form).forEach((value, key) => { if (String(value).trim() !== '') params.append(key, value); });
            const url = (form.getAttribute('action') || location.pathname) + (params.toString() ? '?' + params : '');

            if (controller) controller.abort();
            controller = new AbortController();
            results.classList.add('opacity-60', 'pointer-events-none', 'transition-opacity');

            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal });
                if (!response.ok) throw new Error(response.status);
                const html = new DOMParser().parseFromString(await response.text(), 'text/html');
                const fresh = html.querySelector('[data-live-results]');
                if (fresh) results.innerHTML = fresh.innerHTML;
                history.replaceState(null, '', url);
                if (reset) reset.classList.toggle('hidden', !params.toString());
            } catch (err) {
                if (err.name !== 'AbortError') form.submit(); // gagal: pakai cara biasa
            } finally {
                results.classList.remove('opacity-60', 'pointer-events-none');
            }
        };

        form.querySelectorAll('input[type=search], input[type=text]').forEach(input => {
            input.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(run, 350); });
        });
        form.querySelectorAll('select').forEach(select => select.addEventListener('change', run));
        form.addEventListener('submit', e => { e.preventDefault(); clearTimeout(timer); run(); });
    });
});
