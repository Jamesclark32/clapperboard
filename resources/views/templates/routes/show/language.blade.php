@php echo '<?php'.PHP_EOL @endphp

return [
    'page_title' => '{{ str($resource->resource)->title()->singular() }}',
    'breadcrumbs' => [
        'index' => '{{ str($resource->resource)->title()->plural() }}',
        'show' => '{{ str($resource->resource)->title()->singular() }}',
    ],
    'fields' => [
@foreach($resource->languageFieldEntries() as $field => $label)
        '{{ $field }}' => '{{ $label }}',
@endforeach
    ],
];
