@props([
    'id' => null,
    'name' => null,
    'class' => null,
    'rows' => 3,
])

<textarea
    {{ $attributes->merge([
        'id' => $id,
        'name' => $name,
        'class' => 'mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ' . ($class ?? ''),
        'rows' => $rows,
    ]) }}
>{{ $slot }}</textarea>
