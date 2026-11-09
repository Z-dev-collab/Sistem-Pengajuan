import './bootstrap';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function onReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback, { once: true });
    } else {
        callback();
    }
}

onReady(() => {
    initTextSize();
    initPublicMenu();
    initAdminMenu();
    initFlashMessages();
    initFileUploads();
    initAppointmentAvailability();
    initCopyButtons();
    initPrintButtons();
    initPasswordToggles();
    initSubmitButtons();
    initTrackingInputs();
    initTypewriter();
    initDraggableSummaryCard();
});

/* ---------- Large text preference ---------- */
function initTextSize() {
    const toggles = document.querySelectorAll('[data-font-toggle]');
    if (!toggles.length) return;

    let stored = null;
    try {
        stored = localStorage.getItem('kelurahan-font');
    } catch (error) {
        stored = null;
    }

    const apply = (large) => {
        document.documentElement.dataset.font = large ? 'large' : 'normal';
        toggles.forEach((toggle) => {
            toggle.setAttribute('aria-pressed', large ? 'true' : 'false');
            toggle.setAttribute('title', large ? 'Kembalikan ukuran teks' : 'Perbesar ukuran teks');
            const label = toggle.querySelector('.sr-only');
            if (label) label.textContent = large ? 'Kembalikan ukuran teks' : 'Perbesar teks';
        });
    };

    apply(stored === 'large');

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const large = document.documentElement.dataset.font !== 'large';
            apply(large);
            try {
                localStorage.setItem('kelurahan-font', large ? 'large' : 'normal');
            } catch (error) {
                /* storage unavailable */
            }
        });
    });
}

/* ---------- Public mobile navigation ---------- */
function initPublicMenu() {
    const toggle = document.querySelector('[data-menu-toggle]');
    const nav = document.getElementById('main-nav');
    if (!toggle || !nav) return;

    const setOpen = (open) => {
        document.body.classList.toggle('nav-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        const label = toggle.querySelector('.sr-only');
        if (label) label.textContent = open ? 'Tutup menu' : 'Buka menu';
    };

    toggle.addEventListener('click', () => setOpen(!document.body.classList.contains('nav-open')));
    nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.body.classList.contains('nav-open')) {
            setOpen(false);
            toggle.focus();
        }
    });

    document.addEventListener('click', (event) => {
        if (!document.body.classList.contains('nav-open')) return;
        if (nav.contains(event.target) || toggle.contains(event.target)) return;
        setOpen(false);
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 960 && document.body.classList.contains('nav-open')) setOpen(false);
    });
}

/* ---------- Admin sidebar ---------- */
function initAdminMenu() {
    const toggle = document.querySelector('[data-admin-menu]');
    const overlay = document.querySelector('[data-admin-overlay]');
    if (!toggle) return;

    const setOpen = (open) => {
        document.body.classList.toggle('nav-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    };

    toggle.addEventListener('click', () => setOpen(!document.body.classList.contains('nav-open')));
    if (overlay) overlay.addEventListener('click', () => setOpen(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.body.classList.contains('nav-open')) setOpen(false);
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) setOpen(false);
    });
}

/* ---------- Flash messages ---------- */
function initFlashMessages() {
    document.querySelectorAll('[data-flash-close]').forEach((button) => {
        button.addEventListener('click', () => {
            const flash = button.closest('[data-flash]');
            if (flash) flash.remove();
        });
    });

    document.querySelectorAll('[data-flash]').forEach((flash) => {
        if (prefersReducedMotion) return;
        window.setTimeout(() => {
            flash.style.transition = 'opacity .3s ease';
            flash.style.opacity = '0';
            window.setTimeout(() => flash.remove(), 350);
        }, 9000);
    });
}

/* ---------- File uploads ---------- */
function initFileUploads() {
    document.querySelectorAll('[data-upload]').forEach((wrapper) => {
        const input = wrapper.querySelector('[data-file-input]');
        const box = wrapper.querySelector('.upload-box');
        const title = wrapper.querySelector('[data-upload-title]');
        const name = wrapper.querySelector('[data-upload-name]');
        const selected = wrapper.querySelector('[data-upload-selected]');
        if (!input) return;

        const defaultTitle = title ? title.textContent : '';
        const defaultName = name ? name.textContent : '';

        const formatSize = (bytes) => {
            if (bytes < 1024) return `${bytes} B`;
            if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} KB`;
            return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
        };

        const render = () => {
            const files = Array.from(input.files || []);
            if (!files.length) {
                if (title) title.textContent = defaultTitle;
                if (name) name.textContent = defaultName;
                if (selected) {
                    selected.hidden = true;
                    selected.innerHTML = '';
                }
                return;
            }

            if (title) title.textContent = files.length > 1 ? `${files.length} berkas dipilih` : 'Berkas siap diunggah';
            if (name) name.textContent = files.length === 1 ? files[0].name : 'Klik untuk mengganti pilihan';

            if (selected) {
                selected.hidden = false;
                selected.innerHTML = files
                    .map((file) => `<span>${escapeHtml(file.name)} <em>(${formatSize(file.size)})</em></span>`)
                    .join('');
            }
        };

        input.addEventListener('change', render);
        render();

        if (!box) return;

        ['dragenter', 'dragover'].forEach((type) => {
            box.addEventListener(type, (event) => {
                event.preventDefault();
                box.classList.add('is-dragging');
            });
        });

        ['dragleave', 'drop'].forEach((type) => {
            box.addEventListener(type, (event) => {
                event.preventDefault();
                box.classList.remove('is-dragging');
            });
        });

        box.addEventListener('drop', (event) => {
            const dropped = event.dataTransfer?.files;
            if (!dropped || !dropped.length) return;
            const transfer = new DataTransfer();
            const limit = input.multiple ? Math.min(dropped.length, 3) : 1;
            for (let index = 0; index < limit; index += 1) transfer.items.add(dropped[index]);
            input.files = transfer.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
}

/* ---------- Appointment availability ---------- */
function initAppointmentAvailability() {
    const form = document.getElementById('appointment-form');
    if (!form) return;

    const dateInput = form.querySelector('[data-visit-date]');
    const slotsWrap = form.querySelector('[data-time-slots]');
    const message = form.querySelector('[data-slot-message]');
    if (!dateInput || !slotsWrap) return;

    const url = form.dataset.availabilityUrl;
    const defaultMessage = message ? message.textContent : '';

    const setMessage = (text, tone = '') => {
        if (!message) return;
        message.textContent = text;
        message.dataset.tone = tone;
    };

    const resetSlots = () => {
        slotsWrap.querySelectorAll('.time-slot').forEach((label) => {
            label.classList.remove('is-full');
            const input = label.querySelector('input');
            if (input) {
                input.disabled = false;
                input.checked = false;
            }
            const note = label.querySelector('[data-slot-note]');
            if (note) note.textContent = 'Pilih tanggal dulu';
        });
        setMessage(defaultMessage);
    };

    dateInput.addEventListener('change', async () => {
        if (!dateInput.value) {
            resetSlots();
            return;
        }

        const date = new Date(`${dateInput.value}T00:00:00`);
        if ([0, 6].includes(date.getDay())) {
            dateInput.setCustomValidity('Kantor tutup pada hari Sabtu dan Minggu.');
            dateInput.reportValidity();
            resetSlots();
            setMessage('Sabtu dan Minggu kantor tutup. Silakan pilih hari kerja.', 'warning');
            return;
        }
        dateInput.setCustomValidity('');

        if (!url) return;

        setMessage('Memeriksa ketersediaan jam…');
        try {
            const response = await fetch(`${url}?date=${encodeURIComponent(dateInput.value)}`, {
                headers: { Accept: 'application/json' },
            });
            if (!response.ok) throw new Error('availability');
            const data = await response.json();

            slotsWrap.querySelectorAll('.time-slot').forEach((label) => {
                const input = label.querySelector('input');
                const note = label.querySelector('[data-slot-note]');
                if (!input || !data.slots) return;
                const slot = data.slots.find((item) => item.time === input.value);
                const remaining = slot ? Number(slot.remaining) : 0;
                const full = data.closed || remaining <= 0;

                label.classList.toggle('is-full', full);
                input.disabled = full;
                if (full) input.checked = false;
                if (note) {
                    note.textContent = data.closed
                        ? 'Kantor tutup'
                        : remaining > 0
                            ? `Sisa ${remaining} kuota`
                            : 'Kuota penuh';
                }
            });

            if (data.closed) {
                setMessage('Kantor tutup pada tanggal tersebut. Silakan pilih hari lain.', 'warning');
            } else if (data.slots && data.slots.every((slot) => Number(slot.remaining) <= 0)) {
                setMessage('Semua jam pada tanggal ini sudah penuh. Silakan pilih tanggal lain.', 'warning');
            } else {
                setMessage('Jam yang penuh otomatis dinonaktifkan.', 'success');
            }
        } catch (error) {
            setMessage('Ketersediaan jam tidak dapat dimuat. Silakan coba lagi.', 'warning');
        }
    });
}

/* ---------- Copy buttons ---------- */
function initCopyButtons() {
    document.querySelectorAll('[data-copy]').forEach((button) => {
        button.addEventListener('click', async () => {
            const value = button.dataset.copy;
            if (!value) return;

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(value);
                } else {
                    const helper = document.createElement('textarea');
                    helper.value = value;
                    helper.setAttribute('readonly', '');
                    helper.style.position = 'fixed';
                    helper.style.opacity = '0';
                    document.body.appendChild(helper);
                    helper.select();
                    document.execCommand('copy');
                    document.body.removeChild(helper);
                }

                const target = document.querySelector('[data-copy-target]');
                const original = button.dataset.originalLabel ?? button.getAttribute('title') ?? '';
                button.dataset.originalLabel = original;
                button.classList.add('copied');
                button.setAttribute('title', 'Tersalin');
                button.setAttribute('aria-label', 'Tersalin');

                if (target && !button.closest('.tracking-result-head')) {
                    /* keep target text unchanged; visual feedback only */
                }

                window.setTimeout(() => {
                    button.classList.remove('copied');
                    button.setAttribute('title', original);
                    button.setAttribute('aria-label', original);
                }, 1800);
            } catch (error) {
                /* clipboard unavailable */
            }
        });
    });
}

/* ---------- Print buttons ---------- */
function initPrintButtons() {
    document.querySelectorAll('[data-print]').forEach((button) => {
        button.addEventListener('click', () => window.print());
    });
}

/* ---------- Password visibility ---------- */
function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const field = button.closest('.password-field')?.querySelector('input');
        if (!field) return;

        button.addEventListener('click', () => {
            const visible = field.type === 'text';
            field.type = visible ? 'password' : 'text';
            button.setAttribute('aria-label', visible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
            button.setAttribute('aria-pressed', visible ? 'false' : 'true');
        });
    });
}

/* ---------- Submit loading state ---------- */
function initSubmitButtons() {
    document.querySelectorAll('form').forEach((form) => {
        if (form.hasAttribute('onsubmit')) return;

        form.addEventListener('submit', (event) => {
            if (form.dataset.submitting === 'true') {
                event.preventDefault();
                return;
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            form.dataset.submitting = 'true';
            const button = form.querySelector('[data-submit-button]') || form.querySelector('button[type="submit"]');
            if (!button) return;
            button.disabled = true;
            button.classList.add('is-loading');
            button.setAttribute('aria-busy', 'true');
            const label = button.querySelector('span:not(.icon)');
            if (label && !label.dataset.originalText) {
                label.dataset.originalText = label.textContent;
                label.textContent = 'Memproses…';
            }
        });
    });
}

/* ---------- Tracking code normalisation ---------- */
function initTrackingInputs() {
    document.querySelectorAll('input[name="tracking_code"]').forEach((input) => {
        input.addEventListener('input', () => {
            input.value = input.value.toUpperCase().replace(/\s+/g, '');
        });
    });
}

/* ---------- Typing name banner ---------- */
function initTypewriter() {
    const root = document.querySelector('[data-typewriter]');
    if (!root) return;

    const textEl = root.querySelector('.tw-text');
    const fullText = textEl.textContent;
    if (!fullText) return;

    if (prefersReducedMotion) return;

    const typeDelay = 110;
    const eraseDelay = 45;
    const holdDelay = 2800;
    const restartDelay = 550;

    let index = 0;
    let erasing = false;

    textEl.textContent = '';
    root.classList.add('is-typing');

    const step = () => {
        if (!erasing) {
            index += 1;
            textEl.textContent = fullText.slice(0, index);
            if (index >= fullText.length) {
                erasing = true;
                window.setTimeout(step, holdDelay);
            } else {
                window.setTimeout(step, typeDelay);
            }
            return;
        }

        index -= 1;
        textEl.textContent = fullText.slice(0, index);
        if (index <= 0) {
            erasing = false;
            window.setTimeout(step, restartDelay);
        } else {
            window.setTimeout(step, eraseDelay);
        }
    };

    window.setTimeout(step, 800);
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/* ---------- Draggable summary card (mobile) ---------- */
function initDraggableSummaryCard() {
    const card = document.getElementById('summary-card');
    if (!card) return;

    const handle = card.querySelector('.summary-card-drag-handle');
    if (!handle) return;

    let isFixed = false;
    let isDragging = false;
    let startX, startY, startLeft, startTop;

    function setCardFixed(x, y) {
        card.classList.add('is-fixed');
        card.style.left = x + 'px';
        card.style.top = y + 'px';
        card.dataset.dragged = 'true';
        isFixed = true;
    }

    function setCardFlow() {
        card.classList.remove('is-fixed');
        card.style.left = '';
        card.style.top = '';
        card.dataset.dragged = 'false';
        isFixed = false;
    }

    handle.addEventListener('pointerdown', (e) => {
        if (window.matchMedia('(min-width: 901px)').matches) return;
        e.preventDefault();
        isDragging = true;
        card.classList.add('is-dragging');
        const rect = card.getBoundingClientRect();
        startX = e.clientX;
        startY = e.clientY;
        startLeft = rect.left;
        startTop = rect.top;
        card.setPointerCapture(e.pointerId);
    });

    handle.addEventListener('pointermove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        const clampedX = Math.max(0, Math.min(window.innerWidth - 60, startLeft + dx));
        const clampedY = Math.max(0, Math.min(window.innerHeight - 60, startTop + dy));
        card.style.transition = 'none';
        card.style.left = clampedX + 'px';
        card.style.top = clampedY + 'px';
    });

    handle.addEventListener('pointerup', (e) => {
        if (!isDragging) return;
        isDragging = false;
        card.classList.remove('is-dragging');
        card.style.transition = '';

        const moved = Math.abs(e.clientX - startX) > 8 || Math.abs(e.clientY - startY) > 8;
        if (moved) {
            setCardFixed(
                Math.max(0, Math.min(window.innerWidth - 60, startLeft + (e.clientX - startX))),
                Math.max(0, Math.min(window.innerHeight - 80, startTop + (e.clientY - startY)))
            );
        } else {
            setCardFlow();
        }
    });

    handle.addEventListener('pointercancel', () => {
        if (isDragging) {
            isDragging = false;
            card.classList.remove('is-dragging');
        }
    });
}
