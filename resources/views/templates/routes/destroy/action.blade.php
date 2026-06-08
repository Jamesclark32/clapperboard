@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Commands\BaseCommand;
use App\Models\{{ str($resource->resource)->studly()->singular() }};

final readonly class {{ $resource->action->className() }} extends BaseCommand
{
    public function process({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): void
    {
        $this->executeWithLogging(function () use (${{ str($resource->resource)->camel()->singular() }}) {
            ${{ str($resource->resource)->camel()->singular() }}->delete();
        });
    }
}
