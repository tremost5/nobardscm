const defaultForm = () => ({
    full_name: '',
    church_name: '',
    whatsapp_number: '',
    bring_snack: '0',
});

export function registrationForm(config = {}) {
    return {
        storageKey: config.storageKey || 'dscm-event.registration',
        submitUrl: config.submitUrl,
        csrfToken: config.csrfToken,
        open: false,
        confirmCloseOpen: false,
        successOpen: false,
        submitting: false,
        submitted: false,
        lastSubmittedAt: 0,
        ticketUrl: null,
        registeredName: '',
        fieldsTouched: {
            full_name: false,
            church_name: false,
            whatsapp_number: false,
        },
        form: defaultForm(),
        errors: {},

        init() {
            this.restore();
            window.addEventListener('beforeunload', () => this.persist());

            this.$watch('form.full_name', () => this.persist());
            this.$watch('form.church_name', () => this.persist());
            this.$watch('form.whatsapp_number', () => this.persist());
            this.$watch('form.bring_snack', () => this.persist());
        },

        openModal() {
            this.open = true;
            this.confirmCloseOpen = false;
            this.errors = {};
        },

        handleBackdropClick(event) {
            if (event.target === event.currentTarget) {
                this.requestClose();
            }
        },

        handleKeydown(event) {
            if (event.key === 'Escape') {
                event.preventDefault();
                this.requestClose();
            }
        },

        requestClose() {
            if (this.isDirty()) {
                this.confirmCloseOpen = true;
                return;
            }

            this.closeModal();
        },

        closeModal() {
            this.open = false;
            this.confirmCloseOpen = false;
        },

        keepEditing() {
            this.confirmCloseOpen = false;
        },

        confirmDiscard() {
            this.resetForm();
            this.clearStorage();
            this.confirmCloseOpen = false;
            this.open = false;
            this.errors = {};
            this.fieldsTouched = {
                full_name: false,
                church_name: false,
                whatsapp_number: false,
            };
        },

        openSuccess(message) {
            this.successOpen = true;
            this.successMessage = message || 'Pendaftaran berhasil.';
        },

        closeSuccess() {
            this.successOpen = false;
        },

        isDirty() {
            return Boolean(
                this.form.full_name ||
                this.form.church_name ||
                this.form.whatsapp_number ||
                this.form.bring_snack === '1'
            );
        },

        markTouched(field) {
            this.fieldsTouched[field] = true;
        },

        normalizeWhatsapp() {
            let value = this.form.whatsapp_number.replace(/[^\d+]/g, '');
            value = value.replace(/^\+?62/, '62');
            value = value.replace(/^0/, '62');
            value = value.replace(/\D/g, '').slice(0, 15);
            this.form.whatsapp_number = value.startsWith('62') ? value : '62' + value;
        },

        validateField(field) {
            this.errors = {
                ...this.errors,
                [field]: null,
            };

            if (field === 'full_name' && !this.form.full_name.trim()) {
                this.errors.full_name = 'Nama lengkap wajib diisi.';
            }

            if (field === 'church_name' && !this.form.church_name.trim()) {
                this.errors.church_name = 'Nama gereja asal wajib diisi.';
            }

            if (field === 'whatsapp_number') {
                this.normalizeWhatsapp();
                if (!this.form.whatsapp_number) {
                    this.errors.whatsapp_number = 'Nomor WhatsApp wajib diisi.';
                } else if (!/^62\d{8,13}$/.test(this.form.whatsapp_number)) {
                    this.errors.whatsapp_number = 'Gunakan nomor WhatsApp yang valid.';
                }
            }
        },

        validateAll() {
            this.errors = {};
            this.validateField('full_name');
            this.validateField('church_name');
            this.validateField('whatsapp_number');

            this.fieldsTouched = {
                full_name: true,
                church_name: true,
                whatsapp_number: true,
            };

            return !this.errors.full_name && !this.errors.church_name && !this.errors.whatsapp_number;
        },

        async submit() {
            if (this.submitting) {
                return;
            }

            if (!this.validateAll()) {
                return;
            }

            const now = Date.now();
            if (now - this.lastSubmittedAt < 1500) {
                this.errors.form = 'Mohon tunggu sebentar sebelum mengirim ulang.';
                return;
            }

            this.submitting = true;
            this.errors = {};

            try {
                const response = await fetch(this.submitUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({
                        full_name: this.form.full_name.trim(),
                        church_name: this.form.church_name.trim(),
                        whatsapp_number: this.form.whatsapp_number,
                        bring_snack: this.form.bring_snack === '1',
                        website: '',
                    }),
                });

                if (response.status === 422) {
                    const payload = await response.json();
                    this.errors = Object.fromEntries(
                        Object.entries(payload.errors || {}).map(([key, value]) => [key, Array.isArray(value) ? value[0] : value]),
                    );
                    return;
                }

                if (!response.ok) {
                    throw new Error('Request failed');
                }

                const payload = await response.json();
                this.lastSubmittedAt = Date.now();
                this.ticketUrl = payload.ticket_url || null;
                this.registeredName = this.form.full_name;
                this.clearStorage();
                this.resetForm();
                this.closeModal();
                this.openSuccess(payload.message || 'Pendaftaran berhasil.');
            } catch (error) {
                this.errors = {
                    form: 'Pendaftaran belum bisa diproses. Silakan coba lagi.',
                };
            } finally {
                this.submitting = false;
            }
        },

        persist() {
            if (typeof window === 'undefined') {
                return;
            }

            window.localStorage.setItem(this.storageKey, JSON.stringify(this.form));
        },

        restore() {
            if (typeof window === 'undefined') {
                return;
            }

            const raw = window.localStorage.getItem(this.storageKey);

            if (!raw) {
                return;
            }

            try {
                const stored = JSON.parse(raw);

                this.form = {
                    ...defaultForm(),
                    ...stored,
                    whatsapp_number: String(stored.whatsapp_number || '').replace(/[^\d]/g, '').slice(0, 15),
                    bring_snack: stored.bring_snack === true || stored.bring_snack === '1' ? '1' : '0',
                };
            } catch (error) {
                this.clearStorage();
            }
        },

        clearStorage() {
            if (typeof window === 'undefined') {
                return;
            }

            window.localStorage.removeItem(this.storageKey);
        },

        resetForm() {
            this.form = defaultForm();
            this.errors = {};
            this.fieldsTouched = {
                full_name: false,
                church_name: false,
                whatsapp_number: false,
            };
        },
    };
}
