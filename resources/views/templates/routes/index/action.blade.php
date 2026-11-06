@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->action->namespace() }};

use App\Http\Queries\BaseQuery;
use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final readonly class {{ $resource->action->className() }} extends BaseQuery
{
    public function getProps(User $user, ?string $search = null): array
    {
        $paginator = $this->get{{ str($resource->resource)->studly()->plural() }}($user, $search);

        return [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageTitle' => $this->getPageTitle(),
            '{{ str($resource->resource)->lower()->plural() }}' => [
                'data' => collect($paginator->items())
                    ->map(fn ({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}) => [
@foreach($resource->writableFillable() as $fillable)
                        '{{ $fillable }}' => ${{ str($resource->resource)->camel()->singular() }}->{{ $fillable }},
@endforeach
                        'id' => ${{ str($resource->resource)->camel()->singular() }}->id,
                        'created_at' => ${{ str($resource->resource)->camel()->singular() }}->created_at?->toISOString(),
                        'updated_at' => ${{ str($resource->resource)->camel()->singular() }}->updated_at?->toISOString(),
                    ])
                    ->values()
                    ->all(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'language' => $this->getLanguage(),
        ];
    }

    protected function get{{ str($resource->resource)->studly()->plural() }}(User $user, ?string $search): LengthAwarePaginator
    {
        $query = {{ str($resource->resource)->studly()->singular() }}::query()
            ->select([
@foreach($resource->fillable as $fillable)
                '{{ $fillable }}',
@endforeach
                'created_at',
                'updated_at',
@if($resource->isOwned())
            ])
            ->where('user_id', $user->id);
@else
            ]);
@endif

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

    protected function getBreadcrumbs(): array
    {
        return [
            [
                'title' => trans('{{ $resource->language->key('breadcrumbs.resource') }}'),
                'href' => '#',
            ],
        ];
    }

    protected function getLanguage(): array
    {
        return [
            'messages' => [
                'intro' => trans('{{ $resource->language->key('messages.intro') }}'),
            ],
            'fields' => trans('{{ $resource->language->key('fields') }}'),
            'filters' => trans('{{ $resource->language->key('filters') }}'),
        ];
    }

    protected function getPageTitle(): string
    {
        return trans('{{ $resource->language->key('page_title') }}');
    }
}
