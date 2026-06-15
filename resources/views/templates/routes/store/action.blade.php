@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Commands\BaseCommand;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;
use Illuminate\Auth\Access\Gate;

final readonly class {{ $resource->action->className() }} extends BaseCommand
{
    public function process(User $user, Collection $data): {{ str($resource->resource)->studly()->singular() }}
    {
        $this->authorize($user);

        return {{ str($resource->resource)->studly()->singular() }}::query()->create(
            $data->only([
@foreach($resource->writableFillable() as $field)
                '{{ $field }}',
@endforeach
            ])->all(),
        );
    }

    protected function authorize(User $user): void
    {
        if(Gate::forUser($user)->denies('store', {{ str($resource->resource)->studly()->singular() }}::class)) {
            throw new AuthorizationException;
        }
    }
}
