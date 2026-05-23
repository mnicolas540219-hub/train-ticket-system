@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md border-slate-300 bg-white shadow-sm transition focus:border-slate-700 focus:ring-slate-700 disabled:bg-slate-100 disabled:text-slate-500']) }}>
