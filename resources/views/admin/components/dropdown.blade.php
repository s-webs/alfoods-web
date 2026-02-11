@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 w-48 rounded-lg shadow-xl bg-white dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-slate-600 {{ $alignmentClasses }}"
         style="display: none;"
         @click="open = false">
        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</div>
