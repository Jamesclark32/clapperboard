@php echo '<?php'.PHP_EOL @endphp

return [
    'page_title' => '{{ $resource->component->pageTitle() }}',
    'breadcrumbs' => [
        'resource' => '{{ str($resource->resource)->title()->plural() }}',
        'verb' => 'Create',
    ],
    'form' => [
        'labels' => [
@foreach($resource->languageFieldEntries() as $field => $label)
            '{{ $field }}' => '{{ $label }}',
@endforeach
        ],
        'buttons' => [
            'submit' => 'Create new {{ str($resource->resource)->title()->singular() }}',
        ],
    ],
];
