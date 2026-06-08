export type {{ Str::of($resource->resource)->studly()->singular() }} = {
@foreach($resource->attributes as $attribute)
@if($attribute->fillable!==false)
    {{ $attribute->name }}: {{ $attribute->javascriptType }},
@endif
@endforeach
}
