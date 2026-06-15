<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs, pageTitle, {{ str($resource->resource)->lower()->plural() }}, language } = defineProps<{
    breadcrumbs: BreadcrumbItem[];
    pageTitle: string;
    {{ str($resource->resource)->lower()->plural() }}: {
        data: Record<string, unknown>[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    language: {
        messages: {
            intro: string;
        };
        fields: Record<string, string>;
        filters: {
            search: string;
        };
    };
}>();
</script>

<template>
    <Head :title="pageTitle" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    @{{ pageTitle }}
                </h1>
            </div>

            @{{ language.messages.intro }}

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
@foreach($resource->writableFillable() as $fillable)
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">@{{ language.fields.{{ $fillable }} }}</th>
@endforeach
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">ID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-950">
                        <tr v-for="row in {{ str($resource->resource)->lower()->plural() }}.data" :key="String(row.id)">
@foreach($resource->writableFillable() as $fillable)
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">@{{ row.{{ $fillable }} ?? ' - ' }}</td>
@endforeach
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                <Link :href="`/{{ str($resource->resource)->lower()->plural() }}/${row.id}`" class="underline">
                                    @{{ row.id }}
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-sm text-gray-600 dark:text-gray-300">
                Page @{{ {{ str($resource->resource)->lower()->plural() }}.current_page }} of @{{ {{ str($resource->resource)->lower()->plural() }}.last_page }}
                • @{{ {{ str($resource->resource)->lower()->plural() }}.total }} total
                • @{{ {{ str($resource->resource)->lower()->plural() }}.per_page }} per page
            </div>
        </div>
    </AppLayout>
</template>
