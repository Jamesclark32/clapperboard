@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->controller->namespace() }};

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class {{ $resource->controller->baseClassName() }} extends Controller
{
    use AuthorizesRequests;
}
