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
    public function {{ in_array($relationship->type, ['hasMany', 'belongsToMany', 'morphMany', 'morphToMany']) ? Str::camel(Str::plural($relationship->model)) : Str::camel($relationship->model) }}(): {{ ucfirst($relationship->type) }}
    {
@if(in_array($relationship->type, ['morphOne', 'morphMany', 'morphToMany']))
        return $this->{{ $relationship->type }}({{ $relationship->model }}::class, '{{ $relationship->morphName }}');
@else
        return $this->{{ $relationship->type }}({{ $relationship->model }}::class);
@endif
    }

@endforeach
}
