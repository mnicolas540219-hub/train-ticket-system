<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ticket Validation</title>
    <link href="/build/assets/app.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100 text-slate-950">
    @php
        $isSuccess = ($status ?? '') === 'success';
        $accent = $isSuccess ? 'emerald' : 'rose';
        $icon = $isSuccess ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12';
        $statusLabel = $isSuccess ? 'VALID' : 'INVALID';
    @endphp

    <section class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=1400&q=80')] bg-cover bg-center opacity-30"></div>
        <div class="absolute inset-0 bg-slate-950/70"></div>

        <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-16">
            <div class="w-full max-w-3xl rounded-[2rem] border border-white/10 bg-white/95 p-10 shadow-2xl backdrop-blur-sm">
                <div class="flex flex-col items-center gap-6 text-center">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-{{ $accent }}-500/10 text-{{ $accent }}-700 shadow-sm">
                        <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $icon }}" />
                        </svg>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-950 px-8 py-6 shadow-sm">
                        <h1 class="text-5xl font-extrabold tracking-tight text-white">{{ $statusLabel }}</h1>
                        <p class="mt-4 text-xl text-slate-200">{{ $message ?? '' }}</p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        @if(!empty($full_name))
                            <div class="rounded-3xl border border-slate-200 bg-slate-950/5 px-6 py-4 text-left">
                                <p class="text-sm uppercase tracking-[0.18em] text-slate-500">Passenger</p>
                                <p class="mt-1 text-lg font-semibold text-slate-900">{{ $full_name }}</p>
                            </div>
                        @endif
                        <div class="rounded-3xl border border-slate-200 bg-slate-950/5 px-6 py-4 text-left">
                            <p class="text-sm uppercase tracking-[0.18em] text-slate-500">Ticket Reference</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900">{{ $ticket_code ?? '—' }}</p>
                        </div>
                    </div>

                    <p class="mt-6 text-sm text-slate-500">Returning to the main page in <span id="count">5</span>s</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function(){
            var t = 5;
            var el = document.getElementById('count');
            var iv = setInterval(function(){
                t--; if (el) el.textContent = t;
                if (t <= 0) { clearInterval(iv); window.location = '/'; }
            }, 1000);
        })();
    </script>
</body>
</html>
