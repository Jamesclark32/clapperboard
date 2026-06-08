@php echo '<?php'.PHP_EOL @endphp

return [
//    [
@foreach($resource->attributes as $attribute)
//        '{{ $attribute->name }}' => '',
@endforeach
//    ],
];
