@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->controller->namespace() }};

use {{ $resource->action->qualifiedClassname() }};
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class {{ $resource->controller->className() }} extends BaseController
{
    public function __invoke(Request $request, {{ $resource->action->className() }} {{ $resource->action->variableName() }}): Response
    {
        // Abort with a 403 unless authorized by the {{ str($resource->resource)->studly()->singular() }}Policy
        $this->authorize('{{ $resource->verb }}', {{ str($resource->resource)->studly()->singular() }}::class);

        return Inertia::render('{{ $resource->component->namespacedName() }}', {{ $resource->action->variableName() }}->{{ $resource->action->entryMethod() }}(
            $request->user()
        ));
    }
}
