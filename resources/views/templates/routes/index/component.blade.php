<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DataPagination from '@/components/DataPagination.vue';
import DataTable, { type Column } from '@/components/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { PaginatedData } from '@/types/pagination';
import type { Language, {{ str($resource->resource)->studly()->singular() }}, User } from './{{ $resource->componentTypes->filename() }}';

const { breadcrumbs, pageTitle, {{ str($resource->resource)->lower()->plural() }}, language } = defineProps<{
    breadcrumbs: BreadcrumbItem[];
    user: User;
    pageTitle: string;
    {{ str($resource->resource)->lower()->plural() }}: PaginatedData<{{ str($resource->resource)->studly()->singular() }}>;
    filters: {
        search: string | null;
    };
    language: Language;
}>();

const columns: Column<{{ str($resource->resource)->studly()->singular() }}>[] = [
@foreach($resource->fillable as $fillable)
    { key: '{{ $fillable }}', label: language.fields.{{ $fillable }} },
@endforeach
];
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

            @{{ language.messages.greeting }} @{{ language.messages.intro }}

            <DataTable :columns="columns" :data="{{ str($resource->resource)->lower()->plural() }}.data">
                <template #title="{ row }">
                    <Link :href="`/{{ str($resource->resource)->lower()->plural() }}/${row.id}`" class="underline">
                        @{{ row.id }}
                    </Link>
                </template>
            </DataTable>

            <DataPagination
                :current-page="{{ str($resource->resource)->lower()->plural() }}.current_page"
                :last-page="{{ str($resource->resource)->lower()->plural() }}.last_page"
                :per-page="{{ str($resource->resource)->lower()->plural() }}.per_page"
                :total="{{ str($resource->resource)->lower()->plural() }}.total"
            />
        </div>
    </AppLayout>
</template>
