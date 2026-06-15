@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Gate;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user): array
    {
        $this->authorize($user);

return [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageTitle' => $this->getPageTitle(),
            'userName' => $this->getUserName($user),
            '{{ str($resource->resource)->camel()->plural() }}' => $this->get{{ str($resource->resource)->studly()->plural() }}(),
        ];
    }

    protected function authorize(User $user): void
    {
        if(Gate::forUser($user)->denies('index', {{ str($resource->resource)->studly()->singular() }}::class)) {
            throw new AuthorizationException;
        }
    }

    protected function getBreadcrumbs(): array
    {
        return [
            [
                'title' => trans('{{ str($resource->resource)->snake()->singular() }}/index.breadcrumbs.resource'),
                'href' => '#',
            ],
        ];
    }

    protected function getPageTitle(): string
    {
        return trans('{{ str($resource->resource)->snake()->singular() }}/index.page_title');
    }

    protected function getUserName(User $user): string
    {
        return $user->name;
    }

    protected function get{{ str($resource->resource)->studly()->plural() }}(): LengthAwarePaginator
    {
        return {{ str($resource->resource)->studly()->singular() }}::query()
            ->select([
@if($resource->fillable)
@foreach($resource->fillable as $fillable)
                '{{ $fillable }}',
@endforeach
@endif
                        'created_at',
                        'updated_at',
            ])
            ->latest()
            ->paginate(25);
    }
}
