@php echo '<?php'.PHP_EOL @endphp

return [
    'page_title' => '{{ str($resource->resource)->title()->plural() }}',
    'breadcrumbs' => [
        'resource' => '{{ str($resource->resource)->title()->plural() }}',
    ],
    'messages' => [
        'description' => '{{ str($resource->resource)->title()->plural() }} library',
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
