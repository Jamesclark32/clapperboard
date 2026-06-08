@php echo '<?php'.PHP_EOL @endphp

return [
    'page_titles' => [
        'create' => '{{ $resource->component->pageTitle() }}',
    ],
    'breadcrumbs' => [
        'index' => '{{ str($resource->resource)->title()->plural() }}',
        'create' => 'Create',
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
        'submit' => 'Create {{ str($resource->resource)->title()->singular() }}',
    ],
];
