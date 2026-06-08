@php echo '<?php'.PHP_EOL @endphp

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @@method bool index(User $user)
 * @@method bool show(User $user, Model $instance)
 * @@method bool store(User $user)
 * @@method bool update(User $user, Model $instance)
 * @@method bool destroy(User $user, Model $instance)
 */
class BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->index($user);
    }

    public function view(User $user, Model $instance): bool
    {
        return $this->show($user, $instance);
    }

    public function create(User $user): bool
    {
        return $this->store($user);
    }

    public function edit(User $user, Model $instance): bool
    {
        return $this->update($user, $instance);
    }

    public function delete(User $user, Model $instance): bool
    {
        return $this->destroy($user, $instance);
    }
}
