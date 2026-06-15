@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->controller->namespace() }};

use App\Models\{{ str($resource->resource)->studly()->singular() }};
use {{ $resource->controller->qualifiedBaseController() }} as Controller;
use Illuminate\Http\Request;
use {{ $resource->action->qualifiedClassname() }};
use Inertia\Inertia;
use Inertia\Response;

final class {{ $resource->controller->className() }} extends Controller
{
    public function __invoke(Request $request, {{ $resource->action->className() }} {{ $resource->action->variableName() }}): Response
    {
        if ($request->user()->cannot('{{ $resource->verb }}', {{$resource->controller->policyParameter()}})) {
            abort(403);
        }

        return Inertia::render('{{ $resource->component->namespacedName() }}', {{ $resource->action->variableName() }}->{{ $resource->action->entryMethod() }}($request->user()));
    }
}
