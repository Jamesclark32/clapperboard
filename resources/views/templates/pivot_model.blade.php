@php echo '<?php'.PHP_EOL @endphp

namespace {{ $pivotModel->namespace() }};

use App\Models\{{ $pivotModel->resourceName }};
use App\Models\{{ $pivotModel->relationship->model }};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class {{ $pivotModel->className() }} extends Pivot
{

    // The pivot migration creates an auto-incrementing id and timestamps.
    public $incrementing = true;

    /**
     * @return BelongsTo<{{ $pivotModel->resourceName }}, $this>
     */
    public function {{ Str::camel($pivotModel->resourceName) }}(): BelongsTo
    {
        return $this->belongsTo({{ $pivotModel->resourceName }}::class);
    }

    /**
     * @return BelongsTo<{{ $pivotModel->relationship->model }}, $this>
     */
    public function {{ Str::camel($pivotModel->relationship->model) }}(): BelongsTo
    {
        return $this->belongsTo({{ $pivotModel->relationship->model }}::class);
    }
}
