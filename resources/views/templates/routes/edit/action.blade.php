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
            'form' => [
@foreach($resource->writableFillable() as $field)
                '{{ $field }}' => ${{ str($resource->resource)->camel()->singular() }}->{{ $field }},
@endforeach
            ],
            'language' => $this->getLanguage(),
            'options' => [],
            'pageTitle' => $this->getPageTitle(),
            'submit' => [
                'method' => 'put',
                'url' => route('{{ str($resource->resource)->lower()->plural() }}.update', ['{{ str($resource->resource)->camel()->singular() }}' => ${{ str($resource->resource)->camel()->singular() }}]),
            ],
        ];
    }

    protected function getBreadcrumbs({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): array
    {
        return [
            [
                'title' => trans('{{ $resource->language->key('breadcrumbs.resource') }}'),
                'href' => route('{{ str($resource->resource)->lower()->plural() }}.index'),
            ],
            [
                'title' => trans('{{ $resource->language->key('breadcrumbs.verb') }}').' '.${{ str($resource->resource)->camel()->singular() }}->getKey(),
                'href' => '#',
            ],
        ];
    }

    protected function getLanguage(): array
    {
        return [
            'fields' => trans('{{ $resource->language->key('form.labels') }}'),
            'form' => [
                'submit' => trans('{{ $resource->language->key('form.buttons.submit') }}'),
            ],
        ];
    }

    protected function getPageTitle(): string
    {
        return trans('{{ $resource->language->key('page_title') }}');
    }
}
