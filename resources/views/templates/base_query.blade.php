@php echo '<?php'.PHP_EOL @endphp

namespace App\Http\Queries;

use App\Models\User;

abstract readonly class BaseQuery
{
    abstract protected function getPageTitle(): string;
}
