@php echo '<?php'.PHP_EOL @endphp

return [
    'page_title' => '{{ $resource->component->pageTitle() }}',
    'breadcrumbs' => [
        'resource' => '{{ str($resource->resource)->title()->plural() }}',
        'show' => '{{ str($resource->resource)->title()->singular() }}',
        'verb' => 'Edit',
    ],
    'form' => [
        'labels' => [
        @foreach($resource->languageFieldEntries() as $field => $label)
            '{{ $field }}' => '{{ $label }}',
        @endforeach
        ],
        'buttons' => [
           'submit' => 'Update {{ str($resource->resource)->title()->singular() }}',
        ],
    ],
];
