@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\DTOs\{{ str($resource->resource)->studly()->plural() }}\CreateCollection;
use App\Models\User;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user): array
    {
        $data = CreateCollection::make([
            'form' => [
                'name' => '',
                'description' => '',
                'price' => '',
                'is_active' => true,
                'category' => '',
            ],
        ]);

        return $this->getBaseProps($user, $data->toArray());
    }

    protected function getResourceName(): string
    {
        return '{{ str($resource->resource)->lower()->plural() }}';
    }

    protected function getBreadcrumbs(): array
    {
        return [
            $this->getBaseBreadcrumb(),
            [
                'title' => trans('{{ $resource->language->key('breadcrumbs.create') }}'),
                'href' => '#',
            ],
        ];
    }

    protected function getPageTitle(): string
    {
        return trans('{{ $resource->language->key('page_titles.create') }}');
    }

    protected function getLanguage(User $user, array $additionalMessages = []): array
    {
        $language = parent::getLanguage($user, $additionalMessages);
        $language['form'] = trans('{{ $resource->language->key('form') }}');

        return $language;
    }
}
