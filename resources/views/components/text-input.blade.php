@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border border-white/10 bg-[#0c1220]/90 text-white placeholder-slate-500 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 shadow-inner px-3.5 py-2.5 text-sm transition']) }}>
