@php echo '<?php'.PHP_EOL @endphp

return [
    'page_titles' => [
        'show' => '{{ str($resource->resource)->title()->singular() }}',
    ],
    'breadcrumbs' => [
        'index' => '{{ str($resource->resource)->title()->plural() }}',
        'show' => '{{ str($resource->resource)->title()->singular() }}',
    ],
    'messages' => [
        'greeting' => 'Hello, :name.',
    ],
    'fields' => [
@foreach($resource->languageFieldEntries() as $field => $label)
        '{{ $field }}' => '{{ $label }}',
@endforeach
    ],
];
