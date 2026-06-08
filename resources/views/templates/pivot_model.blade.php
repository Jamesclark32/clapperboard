@php echo '<?php'.PHP_EOL @endphp

namespace {{ $pivotModel->namespace() }};

use App\Models\{{ $pivotModel->resourceName }};
use App\Models\{{ $pivotModel->relationship->model }};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class {{ $pivotModel->className() }} extends Pivot
{

@if($pivotModel->relationship->pivot && !empty($pivotModel->relationship->pivot['timestamps']))
    public $timestamps = true;
@else
    public $timestamps = false;
@endif

    public function {{ Str::camel($pivotModel->resourceName) }}(): BelongsTo
    {
        return $this->belongsTo({{ $pivotModel->resourceName }}::class);
    }

    public function {{ Str::camel($pivotModel->relationship->model) }}(): BelongsTo
    {
        return $this->belongsTo({{ $pivotModel->relationship->model }}::class);
    }
}
