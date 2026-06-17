@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user, {{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): array
    {
        return [
            'breadcrumbs' => $this->getBreadcrumbs(${{ str($resource->resource)->camel()->singular() }}),
            'pageTitle' => $this->getPageTitle(),
            '{{ str($resource->resource)->camel()->singular() }}' => [
@foreach($resource->writableFillable() as $field)
                '{{ $field }}' => ${{ str($resource->resource)->camel()->singular() }}->{{ $field }},
@endforeach
                'id' => ${{ str($resource->resource)->camel()->singular() }}->id,
                'created_at' => ${{ str($resource->resource)->camel()->singular() }}->created_at?->toISOString(),
                'updated_at' => ${{ str($resource->resource)->camel()->singular() }}->updated_at?->toISOString(),
            ],
            'language' => $this->getLanguage(),
        ];
    }

    protected function getBreadcrumbs({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): array
    {
        return [
            [
                'title' => trans('{{ $resource->language->key('breadcrumbs.index') }}'),
                'href' => route('{{ str($resource->resource)->lower()->plural() }}.index'),
            ],
            [
                'title' => (string) ${{ str($resource->resource)->camel()->singular() }}->getKey(),
                'href' => '#',
            ],
        ];
    }

    protected function getLanguage(): array
    {
        return [
            'fields' => trans('{{ $resource->language->key('fields') }}'),
        ];
    }

    protected function getPageTitle(): string
    {
        return trans('{{ $resource->language->key('page_title') }}');
    }
}
