@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Commands\BaseCommand;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use Illuminate\Support\Collection;

final readonly class {{ $resource->action->className() }} extends BaseCommand
{
    public function process({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}, Collection $data): void
    {
        $this->executeWithLogging(function () use (${{ str($resource->resource)->camel()->singular() }}, $data) {
            ${{ str($resource->resource)->camel()->singular() }}->update(
                $data->only([
@foreach($resource->writableFillable() as $field)
                    '{{ $field }}',
@endforeach
                ])->all(),
            );
        });
    }
}
