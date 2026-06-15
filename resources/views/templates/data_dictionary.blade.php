
# {{ str($resource->resource)->studly()->singular() }}

## Description
{{ $resource->description }}

## Table: `{{ str($resource->resource)->snake()->plural() }}`

### Columns

| Column | Type | Nullable | Unique | Default | Description |
|--------|------|----------|--------|---------|-------------|
| id | bigint | no | yes | auto | Primary key |
@foreach($resource->relationships->where('type', 'belongsTo') as $relationship)
| {{ Str::snake($relationship->model) }}_id | unsignedBigInteger | no | no | - | Foreign key to {{ $relationship->model }} |
@endforeach
@foreach($resource->attributes as $attribute)
| {{ $attribute->name }} | {{ $attribute->columnType }}{!! $attribute->length ? '('.$attribute->length.')' : '' !!} | {{ $attribute->nullable ? 'yes' : 'no' }} | {{ $attribute->unique ? 'yes' : 'no' }} | {{ $attribute->default ?? '-' }} | {{ $attribute->comment ?? '-' }} |
@endforeach
@if($resource->softDeletes)
| deleted_at | timestamp | yes | no | null | Soft delete timestamp |
@endif
| created_at | timestamp | no | no | auto | Record creation timestamp |
| updated_at | timestamp | no | no | auto | Record update timestamp |

@if($resource->softDeletes)
## Soft Deletes
This model supports soft deletion. Records are not permanently removed from the database when deleted.
@endif

## Relationships

| Type | Related Model | Method | Description |
|------|---------------|--------|-------------|
@foreach($resource->relationships as $relationship)
| {{ $relationship->type }} | {{ $relationship->model }} | {{ in_array($relationship->type, ['hasMany', 'belongsToMany', 'morphMany', 'morphToMany']) ? Str::camel(Str::plural($relationship->model)) : Str::camel($relationship->model) }}() | {{ $relationship->type === 'belongsTo' ? 'This ' . $resource->resource . ' belongs to a ' . $relationship->model : ($relationship->type === 'hasMany' ? 'This ' . $resource->resource . ' has many ' . Str::plural($relationship->model) : ($relationship->type === 'hasOne' ? 'This ' . $resource->resource . ' has one ' . $relationship->model : ($relationship->type === 'belongsToMany' ? 'Many-to-many with ' . Str::plural($relationship->model) : 'Polymorphic ' . $relationship->type . ' via ' . $relationship->morphName))) }} |
@endforeach

## Files
 - Model: {{ $resource->model->qualifiedFilename() }}
 - Migration: {{ $resource->migration->qualifiedFilename() }}
 - Factory: {{ $resource->factory->qualifiedFilename() }}
 - Seeder: {{ $resource->seeder->qualifiedFilename() }}
 - Seeder Config: {{ $resource->seederConfig->qualifiedFilename() }}
 - Policy: {{ $resource->policy->qualifiedFilename() }}
 - Routes: routes/web/{{ str($resource->resource)->snake()->plural() }}.php

 ### BelongsToMany and MorphMany relationships will also have a pivot table and related pivot model.

## Notes
 --

<!-- Generated: {{ now()->toDateTimeString() }} by model:route -->
<!-- Last updated: {{ now()->toDateTimeString() }} -->
