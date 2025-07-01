<div {{ $attributes->merge(['class' => 'p-4 text-sm rounded-lg ' . ($type === 'info' ? 'bg-blue-500 teext-justify text-blue-800' : 'bg-red-50 text-red-800')]) }} role="alert">
    {{ $slot }}
</div>