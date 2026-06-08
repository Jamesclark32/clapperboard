@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\DTOs\{{ str($resource->resource)->studly()->plural() }}\EditCollection;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user, {{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): array
    {
        $data = EditCollection::make([
            '{{ str($resource->resource)->camel()->singular() }}' => [
                'id' => ${{ str($resource->resource)->camel()->singular() }}->id,
                'form' => [
                    'name' => ${{ str($resource->resource)->camel()->singular() }}->name,
                    'description' => ${{ str($resource->resource)->camel()->singular() }}->description,
                    'price' => ${{ str($resource->resource)->camel()->singular() }}->price,
                    'is_active' => ${{ str($resource->resource)->camel()->singular() }}->is_active,
                    'category' => ${{ str($resource->resource)->camel()->singular() }}->category,
                ],
            ],
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
                'href' => route('{{ str($resource->resource)->lower()->plural() }}.show', ['{{ str($resource->resource)->camel()->singular() }}' => ${{ str($resource->resource)->camel()->singular() }}]),
            ];
            $breadcrumbs[] = [
                'title' => trans('{{ $resource->language->key('breadcrumbs.edit') }}'),
                'href' => '#',
            ];
        }

        return $breadcrumbs;
    }

    protected function getPageTitle(): string
    {
        return trans('{{ $resource->language->key('page_titles.edit') }}');
    }

    protected function getLanguage(User $user, array $additionalMessages = []): array
    {
        $language = parent::getLanguage($user, $additionalMessages);
        $language['form'] = trans('{{ $resource->language->key('form') }}');

        return $language;
    }
}
