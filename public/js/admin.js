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
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
            if (form.dataset.confirmed) return;
            e.preventDefault();
            pendingForm = form;
            document.getElementById('confirm-message').textContent = form.dataset.confirm;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });
    modal.querySelector('[data-confirm-cancel]').addEventListener('click', closeConfirm);
    modal.addEventListener('click', e => { if (e.target === modal) closeConfirm(); });
    modal.querySelector('[data-confirm-ok]').addEventListener('click', () => {
        if (!pendingForm) return;
        pendingForm.dataset.confirmed = '1';
        pendingForm.submit();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeConfirm(); });

    // ===== Preview gambar sebelum upload =====
    document.querySelectorAll('[data-image-field]').forEach(field => {
        const input = field.querySelector('[data-image-input]');
        const preview = field.querySelector('[data-preview]');
        const placeholder = field.querySelector('[data-placeholder]');
        const filename = field.querySelector('[data-filename]');
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 10 MB.');
                input.value = '';
                return;
            }
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            filename.textContent = file.name;
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
