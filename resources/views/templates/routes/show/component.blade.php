<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    breadcrumbs: BreadcrumbItem[];
    pageTitle: string;
    {{ str($resource->resource)->camel()->singular() }}: Record<string, unknown>;
    language: {
        fields: Record<string, string>;
    };
}>();

const {{ str($resource->resource)->camel()->singular() }} = props.{{ str($resource->resource)->camel()->singular() }};
</script>

<template>
    <Head :title="props.pageTitle" />
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                @{{ props.pageTitle }}
            </h1>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-950">
@foreach($resource->writableFillable() as $field)
                    <tr>
                        <th class="w-1/3 bg-gray-50 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:bg-gray-900/50 dark:text-gray-200">
                            @php echo "{{ props.language.fields['{$field}'] ?? '".str($field)->apa()->toString()."' }}" @endphp
                        </th>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            @php echo "{{ {$resource->routeParameterKey()}['{$field}'] ?? ' - ' }}" @endphp
                        </td>
                    </tr>
@endforeach
                    <tr>
                        <th class="w-1/3 bg-gray-50 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:bg-gray-900/50 dark:text-gray-200">ID</th>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">@php echo "{{ {$resource->routeParameterKey()}['id'] ?? ' - ' }}" @endphp</td>
                    </tr>
                    <tr>
                        <th class="w-1/3 bg-gray-50 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:bg-gray-900/50 dark:text-gray-200">Created At</th>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">@php echo "{{ {$resource->routeParameterKey()}['created_at'] ?? ' - ' }}" @endphp</td>
                    </tr>
                    <tr>
                        <th class="w-1/3 bg-gray-50 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:bg-gray-900/50 dark:text-gray-200">Updated At</th>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">@php echo "{{ {$resource->routeParameterKey()}['updated_at'] ?? ' - ' }}" @endphp</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
