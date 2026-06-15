@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->controller->namespace() }};

use {{ $resource->action->qualifiedClassname() }};
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class {{ $resource->controller->className() }} extends BaseController
{
    public function __invoke({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}, Request $request, {{ $resource->action->className() }} {{ $resource->action->variableName() }}): RedirectResponse
    {
        // Abort with a 403 unless authorized by the {{ str($resource->resource)->studly()->singular() }}Policy
        $this->authorize('{{ $resource->verb }}', ${{ str($resource->resource)->camel()->singular() }});

        {{ $resource->action->variableName() }}->{{ $resource->action->entryMethod() }}(
            $request->user(),
            ${{ str($resource->resource)->camel()->singular() }}
        );

        return redirect()
            ->route('{{ str($resource->resource)->lower()->plural() }}.index')
            ->with('success', trans('{{ $resource->language->key('messages.success') }}'));
    }
}
