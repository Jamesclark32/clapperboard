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
        ${{ str($resource->resource)->camel()->singular() }} = new {{ str($resource->resource)->studly()->singular() }}(
            $data->only([
@foreach($resource->writableFillable() as $field)
                '{{ $field }}',
@endforeach
            ])->all(),
        );
@if($resource->isOwned())
        ${{ str($resource->resource)->camel()->singular() }}->user()->associate($user);
@endif
        ${{ str($resource->resource)->camel()->singular() }}->save();

        return ${{ str($resource->resource)->camel()->singular() }};
    }
}
