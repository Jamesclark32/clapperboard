@php echo '<?php'.PHP_EOL @endphp

return [
    'page_titles' => [
        'edit' => '{{ $resource->component->pageTitle() }}',
    ],
    'breadcrumbs' => [
        'index' => '{{ str($resource->resource)->title()->plural() }}',
        'show' => '{{ str($resource->resource)->title()->singular() }}',
        'edit' => 'Edit',
    ],
    'messages' => [
        'greeting' => 'Hello, :name.',
    ],
    'fields' => [
@foreach($resource->languageFieldEntries() as $field => $label)
        '{{ $field }}' => '{{ $label }}',
@endforeach
    ],
    'form' => [
        'submit' => 'Save {{ str($resource->resource)->title()->singular() }}',
    ],
];
