export type User = {
    name: string
}

export type {{ str($resource->resource)->studly()->singular() }} = {
@foreach($resource->fillable as $fillable)
    {{ $fillable }}: string | number | null
@endforeach
}

export type Language = {
    messages: {
        greeting: string
    }
    fields: Record<string, string>
}
