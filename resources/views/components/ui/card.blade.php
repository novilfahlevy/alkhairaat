@props([
    'class' => '',
])

<div
    {{ $attributes->merge([
        'class' => 'rounded-lg bg-white p-6 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 ' . $class,
    ]) }}>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        {{ $header ?? '' }}
    </div>
    {{ $slot }}
</div>
