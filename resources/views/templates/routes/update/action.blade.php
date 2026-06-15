@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Commands\BaseCommand;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Collection;

final readonly class {{ $resource->action->className() }} extends BaseCommand
{
    public function process(User $user, {{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}, Collection $data): void
    {
        $this->authorize($user, ${{ str($resource->resource)->camel()->singular() }});

        ${{ str($resource->resource)->camel()->singular() }}->update(
            $data->only([
@foreach($resource->writableFillable() as $field)
                '{{ $field }}',
@endforeach
            ])->all(),
        );
    }

    protected function authorize(User $user {{ str($resource->resource)->studly()->singular() }}, ${{ str($resource->resource)->camel()->singular() }}): void
    {
        if(Gate::forUser($user)->denies('update', ${{ str($resource->resource)->camel()->singular() }})) {
            throw new AuthorizationException;
        }
    }
}
