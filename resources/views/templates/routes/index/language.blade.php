@php echo '<?php'.PHP_EOL @endphp

return [
    'page_titles' => [
        'index' => '{{ str($resource->resource)->title()->plural() }}',
    ],
    'breadcrumbs' => [
        'index' => '{{ str($resource->resource)->title()->plural() }}',
    ],
    'messages' => [
        'greeting' => 'Hello, :name.',
        'intro' => 'Here are the {{ str($resource->resource)->title()->plural() }}.',
    ],
    'fields' => [
@foreach($resource->languageFieldEntries() as $field => $label)
        '{{ $field }}' => '{{ $label }}',
@endforeach
    ],
    'filters' => [
        'search' => 'Search',
    ],
];
