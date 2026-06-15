@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Commands\BaseCommand;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Gate;

final readonly class {{ $resource->action->className() }} extends BaseCommand
{
    public function process(User $user, {{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): void
    {
       $this->authorize($user, ${{ str($resource->resource)->camel()->singular() }});

        ${{ str($resource->resource)->camel()->singular() }}->delete();
    }

    protected function authorize(User $user {{ str($resource->resource)->studly()->singular() }}, ${{ str($resource->resource)->camel()->singular() }}): void
    {
        if(Gate::forUser($user)->denies('destroy', ${{ str($resource->resource)->camel()->singular() }})) {
            throw new AuthorizationException;
        }
    }
}
