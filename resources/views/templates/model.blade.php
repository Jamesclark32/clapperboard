@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->model->namespace() }};

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
@foreach($resource->relationships->pluck('type')->unique()->sort() as $type)
use Illuminate\Database\Eloquent\Relations\{{ ucfirst($type) }};
@endforeach
@if($resource->softDeletes)
use Illuminate\Database\Eloquent\SoftDeletes;
@endif

class {{ $resource->model->className() }} extends Model
{
    /** @@use HasFactory<\Database\Factories\{{ $resource->factory->className() }}> */
    use HasFactory;
@if($resource->softDeletes)
    use SoftDeletes;
@endif

    protected $fillable = [
@foreach($resource->attributes as $attribute)
@if($attribute->fillable!==false)
        '{{ $attribute->name }}',
@endif
@endforeach
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
@foreach($resource->attributes as $attribute)
@if($attribute->casts!==null)
            '{{ $attribute->name }}' => '{{ $attribute->casts }}',
@endif
@endforeach
        ];
    }

@foreach($resource->relationships as $relationship)
    /**
@if(in_array($relationship->type, ['belongsTo', 'morphTo']))
     * @return {{ ucfirst($relationship->type) }}<{{ $relationship->model }}, $this>
@elseif($relationship->type === 'belongsToMany')
     * @return BelongsToMany<{{ $relationship->model }}, $this, \App\Models\Pivots\{{ (new \JamesClark32\Clapperboard\ModelResources\PivotModel($resource->resource, $relationship))->className() }}>
@else
     * @return {{ ucfirst($relationship->type) }}<{{ $relationship->model }}, $this>
@endif
     */
    public function {{ $relationship->methodName() }}(): {{ ucfirst($relationship->type) }}
    {
@if($relationship->type === 'belongsToMany')
        return $this->belongsToMany({{ $relationship->model }}::class)
            ->using(\App\Models\Pivots\{{ (new \JamesClark32\Clapperboard\ModelResources\PivotModel($resource->resource, $relationship))->className() }}::class)
            ->withTimestamps();
@elseif(in_array($relationship->type, ['morphOne', 'morphMany', 'morphToMany']))
        return $this->{{ $relationship->type }}({{ $relationship->model }}::class, '{{ $relationship->morphName }}');
@elseif($relationship->type === 'belongsTo' && $relationship->foreignKey)
        return $this->belongsTo({{ $relationship->model }}::class, '{{ $relationship->foreignKey }}');
@else
        return $this->{{ $relationship->type }}({{ $relationship->model }}::class);
@endif
    }

@endforeach
}
