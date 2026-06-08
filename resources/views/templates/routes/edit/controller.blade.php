@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->controller->namespace() }};

use App\Models\{{ str($resource->resource)->studly()->singular() }};
use {{ $resource->controller->qualifiedBaseController() }};
use Illuminate\Http\Request;
use {{ $resource->action->qualifiedClassname() }};
use Inertia\Inertia;
use Inertia\Response;

final readonly class {{ $resource->controller->className() }} extends Controller
{
    public function __invoke(Request $request, {{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}, {{ $resource->action->className() }} {{ $resource->action->variableName() }}): Response
    {
        if ($request->user()->cannot('{{ $resource->verb }}', ${{ str($resource->resource)->camel()->singular() }})) {
            abort(403);
        }

        return Inertia::render('{{ $resource->component->namespacedName() }}', {{ $resource->action->variableName() }}->{{ $resource->action->entryMethod() }}($request->user(), ${{ str($resource->resource)->camel()->singular() }}));
    }
}
