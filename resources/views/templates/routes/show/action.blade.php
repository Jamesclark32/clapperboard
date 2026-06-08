@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\DTOs\{{ str($resource->resource)->studly()->plural() }}\ShowCollection;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user, {{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): array
    {
        $data = ShowCollection::make([
            '{{ str($resource->resource)->camel()->singular() }}' => ${{ str($resource->resource)->camel()->singular() }},
        ]);

        return $this->getBaseProps($user, array_merge($data->toArray(), [
            'breadcrumbs' => $this->getBreadcrumbs(${{ str($resource->resource)->camel()->singular() }}),
        ]));
    }

    protected function getResourceName(): string
    {
        return '{{ str($resource->resource)->lower()->plural() }}';
    }

    protected function getBreadcrumbs({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }} = null): array
    {
        $breadcrumbs = [$this->getBaseBreadcrumb()];

        if (${{ str($resource->resource)->camel()->singular() }}) {
            $breadcrumbs[] = [
                'title' => (string) ${{ str($resource->resource)->camel()->singular() }}->getKey(),
                'href' => '#',
            ];
        }

        return $breadcrumbs;
    }

    protected function getPageTitle(): string
    {
        return trans('{{ $resource->language->key('page_titles.show') }}');
    }
}
