@php echo '<?php'.PHP_EOL @endphp

namespace App\Http\Queries;

use App\Models\User;

abstract readonly class BaseQuery
{
    /**
     * Get the standardized user data for props.
     */
    protected function getUser(User $user): array
    {
        return $user->only([
            'name',
        ]);
    }

    /**
     * Get filters for the query.
     */
    protected function getFilters(?string $search = null, array $additionalFilters = []): array
    {
        return array_merge([
            'search' => $search,
        ], $additionalFilters);
    }

    /**
     * Get standardized language data for props.
     */
    protected function getLanguage(User $user, array $additionalMessages = []): array
    {
        $baseMessages = [
            'greeting' => trans($this->getLanguageKey('messages.greeting'), ['name' => $user->name]),
        ];

        if ($this->hasLanguageKey('messages.intro')) {
            $baseMessages['intro'] = trans($this->getLanguageKey('messages.intro'));
        }

        return [
            'messages' => array_merge($baseMessages, $additionalMessages),
            'fields' => trans($this->getLanguageKey('fields')),
            'filters' => trans($this->getLanguageKey('filters')),
        ];
    }

    /**
     * Get base breadcrumb for index page.
     */
    protected function getBaseBreadcrumb(): array
    {
        return [
            'title' => trans($this->getLanguageKey('breadcrumbs.index')),
            'href' => route($this->getResourceRoute('index')),
        ];
    }

    /**
     * Get standardized page props structure.
     */
    protected function getBaseProps(User $user, array $additionalProps = []): array
    {
        return array_merge([
            'user' => $this->getUser($user),
            'language' => $this->getLanguage($user),
            'pageTitle' => $this->getPageTitle(),
            'breadcrumbs' => $this->getBreadcrumbs(),
        ], $additionalProps);
    }

    /**
     * Get the resource name for language keys and routes.
     * Should be overridden by generated classes.
     */
    protected function getResourceName(): string
    {
        // Extract from class name - e.g. "PostsIndexQuery" -> "posts"
        $className = class_basename(static::class);
        $resourcePart = str_replace(['Query', 'Index', 'Show', 'Create', 'Edit'], '', $className);

        return strtolower($resourcePart);
    }

    /**
     * Get language key for the resource.
     */
    protected function getLanguageKey(string $key): string
    {
        return $this->getResourceName() . '.' . $key;
    }

    /**
     * Check if a language key exists.
     */
    protected function hasLanguageKey(string $key): bool
    {
        return trans()->has($this->getLanguageKey($key));
    }

    /**
     * Get route name for the resource.
     */
    protected function getResourceRoute(string $action): string
    {
        return $this->getResourceName() . '.' . $action;
    }

    /**
     * Get page title. Should be overridden by generated classes.
     */
    abstract protected function getPageTitle(): string;

    /**
     * Get breadcrumbs. Should be overridden by generated classes.
     */
    abstract protected function getBreadcrumbs(): array;
}
