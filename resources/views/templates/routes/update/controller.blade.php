@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->controller->namespace() }};

use App\Models\{{ str($resource->resource)->studly()->singular() }};
use {{ $resource->action->qualifiedClassname() }};
use {{ $resource->controller->qualifiedBaseController() }};
use {{ $resource->formRequest->qualifiedClassname() }};
use Illuminate\Http\RedirectResponse;

final readonly class {{ $resource->controller->className() }} extends Controller
{
    public function __invoke({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}, {{ $resource->formRequest->className() }} $request, {{ $resource->action->className() }} {{ $resource->action->variableName() }}): RedirectResponse
    {
        abort_if($request->user()->cannot('{{ $resource->verb }}', ${{ str($resource->resource)->camel()->singular() }}), 403);

        {{ $resource->action->variableName() }}->{{ $resource->action->entryMethod() }}(${{ str($resource->resource)->camel()->singular() }}, collect($request->validated()));

        return redirect()
            ->route('{{ str($resource->resource)->lower()->plural() }}.show', ${{ str($resource->resource)->camel()->singular() }})
            ->with('success', trans('{{ $resource->language->key('messages.success') }}'));
    }
}
