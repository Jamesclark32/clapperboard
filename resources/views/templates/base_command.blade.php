@php echo '<?php'.PHP_EOL @endphp

namespace App\Http\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseCommand
{
    /**
     * Execute the command with transaction and logging support.
     */
    protected function executeInTransaction(callable $callback): mixed
    {
        return DB::transaction(function () use ($callback) {
            $result = $callback();

            $this->onSuccess($result);

            return $result;
        });
    }

    /**
     * Execute the command with error handling and logging.
     */
    protected function executeWithLogging(callable $callback): mixed
    {
        try {
            $result = $this->executeInTransaction($callback);

            Log::info($this->getLogMessage('success'), $this->getLogContext($result));

            return $result;
        } catch (Throwable $e) {
            $this->onFailure($e);

            Log::error($this->getLogMessage('failed'), [
                'error' => $e->getMessage(),
                'command' => static::class,
            ]);

            throw $e;
        }
    }

    /**
     * Hook called on successful execution.
     */
    protected function onSuccess(mixed $result): void
    {
        // Override in child classes for custom behavior
    }

    /**
     * Hook called on failed execution.
     */
    protected function onFailure(Throwable $exception): void
    {
        // Override in child classes for custom behavior
    }

    /**
     * Get the log message for the given event.
     */
    protected function getLogMessage(string $event): string
    {
        $command = class_basename(static::class);

        return match($event) {
            'success' => "{$command} executed successfully",
            'failed' => "{$command} execution failed",
            default => "{$command} {$event}",
        };
    }

    /**
     * Get the log context for successful execution.
     */
    protected function getLogContext(mixed $result): array
    {
        return [
            'command' => static::class,
            'result_type' => get_debug_type($result),
        ];
    }
}