@php echo '<?php'.PHP_EOL @endphp

namespace App\Policies;

use App\Models\{{ $resource->model->className() }};
use App\Models\User;

class {{ $resource->policy->className() }} extends BasePolicy
{
    public function index(User $user): bool
    {
        return true;
    }

    public function show(User $user, {{ $resource->model->className() }} ${{ str($resource->resource)->camel()->singular() }}): bool
    {
        return true;
    }

    public function store(User $user): bool
    {
        return true;
    }

    public function update(User $user, {{ $resource->model->className() }} ${{ str($resource->resource)->camel()->singular() }}): bool
    {
        return true;
    }

    public function destroy(User $user, {{ $resource->model->className() }} ${{ str($resource->resource)->camel()->singular() }}): bool
    {
        return true;
    }
}
