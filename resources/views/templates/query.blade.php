@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->query->namespace() }};

@if(str($resource->resource)->substr(0, 1)->lower()->toString() < 'u')
use App\Models\{{ str($resource->resource)->studly()->singular() }};
@endif
use App\Models\User;
@if(str($resource->resource)->substr(0, 1)->lower()->toString() > 'u')
use App\Models\{{ str($resource->resource)->studly()->singular() }};
@endif
@if($resource->verb==='index')
use Illuminate\Pagination\LengthAwarePaginator;
@endif

class {{ $resource->query->className() }}
{
    public function getProps(User $user): array
    {
        return [
            'user' => $this->getUser($user),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageTitle' => $this->getPageTitle(),
@if($resource->verb==='index')
            '{{ str($resource->resource)->lower()->plural() }}' => $this->get{{ str($resource->resource)->studly()->plural() }}(),
@endif
        ];
    }

    protected function getUser(User $user): array
    {
        return $user->only([
            'name',
        ]);
    }

    protected function getBreadcrumbs(): array
    {
        return [
            [
                'title' => '{{ str($resource->resource)->studly()->singular() }}',
                'href' => '{{ str($resource->resource)->lower()->singular()->prepend('/') }}',
            ],
            [
                'title' => '{{ str($resource->verb)->studly()->singular() }}',
                'href' => '#',
            ],
        ];
    }

    protected function getPageTitle(): string
    {
        return '{{ $resource->component->pageTitle() }}';
    }

@if($resource->verb==='index')
    protected function get{{ str($resource->resource)->studly()->plural() }}(): LengthAwarePaginator
    {
        return {{ str($resource->resource)->studly()->singular() }}::query()
            ->select([
@foreach($resource->fillable as $fillable)
                '{{ $fillable }}',
@endforeach
            ])
            ->paginate(20);
    }
@endif

}
