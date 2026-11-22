@php echo '<?php'.PHP_EOL @endphp

namespace App\Policies;

use App\Models\{{ $resource->model->className() }};
use App\Models\User;

class {{ $resource->policy->className() }} extends BasePolicy
{
    public function index(User $user): bool
    {
        return {{ $resource->isOwned() || $resource->isTeamOwned() ? 'true' : 'false' }};
    }

    public function show(User $user, {{ $resource->model->className() }} ${{ str($resource->resource)->camel()->singular() }}): bool
    {
@if($resource->isTeamOwned())
        return $user->teams()->whereKey(${{ str($resource->resource)->camel()->singular() }}->team_id)->exists();
@elseif($resource->isOwned())
        return $user->id === ${{ str($resource->resource)->camel()->singular() }}->user_id;
@else
        return false;
@endif
    }

    public function store(User $user): bool
    {
        return {{ $resource->isOwned() || $resource->isTeamOwned() ? 'true' : 'false' }};
    }

    public function update(User $user, {{ $resource->model->className() }} ${{ str($resource->resource)->camel()->singular() }}): bool
    {
@if($resource->isTeamOwned())
        return $user->teams()->whereKey(${{ str($resource->resource)->camel()->singular() }}->team_id)->exists();
@elseif($resource->isOwned())
        return $user->id === ${{ str($resource->resource)->camel()->singular() }}->user_id;
@else
        return false;
@endif
    }

    public function destroy(User $user, {{ $resource->model->className() }} ${{ str($resource->resource)->camel()->singular() }}): bool
    {
@if($resource->isTeamOwned())
        return $user->teams()->whereKey(${{ str($resource->resource)->camel()->singular() }}->team_id)->exists();
@elseif($resource->isOwned())
        return $user->id === ${{ str($resource->resource)->camel()->singular() }}->user_id;
@else
        return false;
@endif
    }
}
