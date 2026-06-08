@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\DTOs\{{ str($resource->resource)->studly()->plural() }}\IndexCollection;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user, ?string $search): array
    {
        $paginator = $this->get{{ str($resource->resource)->studly()->plural() }}($search);

        $data = IndexCollection::make([
            '{{ str($resource->resource)->lower()->plural() }}' => [
                'data' => $paginator->items(),
                'meta' => $paginator->toArray(),
            ],
            'filters' => [
                'search' => $search,
            ],
        ]);

        return $this->getBaseProps($user, $data->toArray());
    }

    protected function get{{ str($resource->resource)->studly()->plural() }}(?string $search): LengthAwarePaginator
    {
        $query = {{ str($resource->resource)->studly()->singular() }}::query()
            ->select([
@if($resource->fillable)
@foreach($resource->fillable as $fillable)
                '{{ $fillable }}',
@endforeach
@endif
            ]);

        $this->applySearch($query, $search);

        return $query->latest()->paginate(25)->withQueryString();
    }

    protected function applySearch(Builder $query, ?string $search): void
    {
        if ($search === null) {
            return;
        }

        $query->where(function (Builder $query) use ($search): void {
@if(in_array('name', $resource->fillable))
            $query->where('name', 'like', "%{$search}%");
@else
            $query->whereKey($search);
@endif
@if(in_array('description', $resource->fillable))
            $query->orWhere('description', 'like', "%{$search}%");
@endif
        });
    }

    protected function getResourceName(): string
    {
        return '{{ str($resource->resource)->lower()->plural() }}';
    }

    protected function getBreadcrumbs(): array
    {
        return [
            $this->getBaseBreadcrumb(),
        ];
    }

    protected function getPageTitle(): string
    {
        return trans('{{ $resource->language->key('page_titles.index') }}');
    }
}
