@props(['name', 'label', 'checked' => false, 'help' => null])

@php($isOn = (bool) old($name, $checked))
<label {{ $attributes->merge(['class' => 'flex items-start gap-3 p-4 rounded-xl border border-slate-200 bg-white cursor-pointer hover:border-tosca-300 transition has-[:checked]:bg-tosca-50 has-[:checked]:border-tosca-300']) }}>
    <input type="hidden" name="{{ $name }}" value="0">
    <span class="relative inline-flex shrink-0 mt-0.5">
        <input type="checkbox" name="{{ $name }}" value="1" @checked($isOn) class="peer sr-only">
        <span class="w-11 h-6 rounded-full bg-slate-300 peer-checked:bg-tosca-600 transition"></span>
        <span class="absolute left-0.5 top-0.5 w-5 h-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span>
    </span>
    <span>
        <span class="block text-sm font-semibold text-slate-800">{{ $label }}</span>
        @if ($help)
            <span class="block text-xs text-slate-500 mt-0.5">{{ $help }}</span>
        @endif
    </span>
</label>
