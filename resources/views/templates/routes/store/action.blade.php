@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Commands\BaseCommand;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Support\Collection;

final readonly class {{ $resource->action->className() }} extends BaseCommand
{
    public function process(User $user, Collection $data): {{ str($resource->resource)->studly()->singular() }}
    {
        return {{ str($resource->resource)->studly()->singular() }}::query()->create(
            $data->only([
@foreach($resource->writableFillable() as $field)
                '{{ $field }}',
@endforeach
            ])->all(),
        );
    }
}
