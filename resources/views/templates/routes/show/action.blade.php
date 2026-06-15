@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Gate;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user, {{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): array
    {
        $this->authorize($user, ${{ str($resource->resource)->camel()->singular() }});

        return [
            'breadcrumbs' => $this->getBreadcrumbs(${{ str($resource->resource)->camel()->singular()}}),
            'pageTitle' => $this->getPageTitle(),
            'userName' => $this->getUserName($user),
            '{{ str($resource->resource)->camel()->singular() }}' => $this->get{{ str($resource->resource)->studly()->singular() }}(${{ str($resource->resource)->camel()->singular() }}),
        ];
    }

    protected function authorize(User $user {{ str($resource->resource)->studly()->singular() }}, ${{ str($resource->resource)->camel()->singular() }}): void
    {
        if(Gate::forUser($user)->denies('show', ${{ str($resource->resource)->camel()->singular() }})) {
         throw new AuthorizationException;
        }
    }

    protected function getBreadcrumbs({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): array
    {
        return [
            [
                'title' => trans('{{ str($resource->resource)->snake()->singular() }}/show.breadcrumbs.resource'),
                'href' => route('{{ str($resource->resource)->lower()->plural() }}.index'),
            ],
            [
                'title' => ${{ str($resource->resource)->camel()->singular()}}->name,
                'href' => '#',
            ],
        ];
    }

    protected function getPageTitle(): string
    {
        return trans('{{ str($resource->resource)->snake()->singular() }}/show.page_title');
    }

    protected function getUserName(User $user): string
    {
        return $user->name;
    }

    protected function get{{ str($resource->resource)->studly()->singular() }}(${{ str($resource->resource)->camel()->singular() }}): array
    {
        return ${{ str($resource->resource)->camel()->singular() }};
    }
}
