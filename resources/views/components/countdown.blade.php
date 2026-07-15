@props(['target'])

<div class="landing-countdown mx-auto grid w-full max-w-[320px] grid-cols-4 gap-2 sm:max-w-sm sm:gap-3" data-countdown-target="{{ $target }}">
    <div class="rounded-2xl border border-yellow-500/30 bg-zinc-900 p-3 text-center sm:p-4">
        <div id="days" class="number text-2xl font-black text-yellow-400 sm:text-3xl">00</div>
        <div class="label text-[10px] uppercase tracking-[0.2em] text-zinc-400 sm:text-[11px] sm:tracking-[0.25em]">Hari</div>
    </div>
    <div class="rounded-2xl border border-yellow-500/30 bg-zinc-900 p-3 text-center sm:p-4">
        <div id="hours" class="number text-2xl font-black text-yellow-400 sm:text-3xl">00</div>
        <div class="label text-[10px] uppercase tracking-[0.2em] text-zinc-400 sm:text-[11px] sm:tracking-[0.25em]">Jam</div>
    </div>
    <div class="rounded-2xl border border-yellow-500/30 bg-zinc-900 p-3 text-center sm:p-4">
        <div id="minutes" class="number text-2xl font-black text-yellow-400 sm:text-3xl">00</div>
        <div class="label text-[10px] uppercase tracking-[0.2em] text-zinc-400 sm:text-[11px] sm:tracking-[0.25em]">Menit</div>
    </div>
    <div class="rounded-2xl border border-yellow-500/30 bg-zinc-900 p-3 text-center sm:p-4">
        <div id="seconds" class="number text-2xl font-black text-yellow-400 sm:text-3xl">00</div>
        <div class="label text-[10px] uppercase tracking-[0.2em] text-zinc-400 sm:text-[11px] sm:tracking-[0.25em]">Detik</div>
    </div>
</div>

<script>
    (function () {
        const container = document.currentScript.previousElementSibling;
        const target = container?.getAttribute('data-countdown-target');

        if (!target) {
            return;
        }

        const updateCountdown = () => {
            const endTime = new Date(target).getTime();
            const now = Date.now();
            const diff = Math.max(0, Math.floor((endTime - now) / 1000));

            const days = Math.floor(diff / 86400);
            const hours = Math.floor((diff % 86400) / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;

            const dayEl = container.querySelector('#days');
            const hourEl = container.querySelector('#hours');
            const minuteEl = container.querySelector('#minutes');
            const secondEl = container.querySelector('#seconds');

            if (dayEl) dayEl.textContent = String(days).padStart(2, '0');
            if (hourEl) hourEl.textContent = String(hours).padStart(2, '0');
            if (minuteEl) minuteEl.textContent = String(minutes).padStart(2, '0');
            if (secondEl) secondEl.textContent = String(seconds).padStart(2, '0');
        };

        updateCountdown();
        setInterval(updateCountdown, 1000);
    })();
</script>
