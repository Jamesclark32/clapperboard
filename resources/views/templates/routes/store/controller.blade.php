@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->controller->namespace() }};

use {{ $resource->action->qualifiedClassname() }};
use {{ $resource->formRequest->qualifiedClassname() }};
use Illuminate\Http\RedirectResponse;

final class {{ $resource->controller->className() }} extends BaseController
{
    public function __invoke({{ $resource->formRequest->className() }} $request, {{ $resource->action->className() }} {{ $resource->action->variableName() }}): RedirectResponse
    {
        // Abort with a 403 unless authorized by the {{ str($resource->resource)->studly()->singular() }}Policy
        $this->authorize('{{ $resource->verb }}', {{ str($resource->resource)->studly()->singular() }}::class);

        ${{ str($resource->resource)->camel()->singular() }} = {{ $resource->action->variableName() }}->{{ $resource->action->entryMethod() }}(
            $request->user(),
            collect($request->validated()
            ));

        return redirect()
            ->route('{{ str($resource->resource)->lower()->plural() }}.show', ${{ str($resource->resource)->camel()->singular() }})
            ->with('success', trans('{{ $resource->language->key('messages.success') }}'));
    }
}
