@php echo '<?php'.PHP_EOL @endphp

return [
    'messages' => [
        'success' => '{{ str($resource->resource)->title()->singular() }} deleted.',
    ],
];
