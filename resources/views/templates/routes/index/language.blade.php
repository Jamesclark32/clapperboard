@php echo '<?php'.PHP_EOL @endphp

return [
    'page_titles' => '{{ str($resource->resource)->title()->plural() }}',
    'breadcrumbs' => [
        'resource' => '{{ str($resource->resource)->title()->plural() }}',
    ],
    'fields' => [
@foreach($resource->languageFieldEntries() as $field => $label)
        '{{ $field }}' => '{{ $label }}',
@endforeach
    ],
];
