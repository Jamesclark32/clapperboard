@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\Models\User;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user): array
    {
        return [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'form' => [
@foreach($resource->writableFillable() as $field)
                '{{ $field }}' => {{ str($field)->startsWith(['is_', 'has_', 'can_', 'should_', 'will_', 'was_']) ? 'false' : 'null' }},
@endforeach
            ],
            'language' => $this->getLanguage(),
            'options' => [],
            'pageTitle' => $this->getPageTitle(),
            'submit' => [
                'method' => 'post',
                'url' => route('{{ str($resource->resource)->lower()->plural() }}.store'),
            ],
        ];
    }

    protected function getBreadcrumbs(): array
    {
        return [
            [
                'title' => trans('{{ $resource->language->key('breadcrumbs.resource') }}'),
                'href' => route('{{ str($resource->resource)->lower()->plural() }}.index'),
            ],
            [
                'title' => trans('{{ $resource->language->key('breadcrumbs.verb') }}'),
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
