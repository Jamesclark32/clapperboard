<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3';
@if($resource->verb==='index')
    import DataPagination from '@/components/DataPagination.vue';
    import DataTable, { type Column } from '@/components/DataTable.vue'
@endif
    import AppLayout from '@/layouts/AppLayout.vue';
    import type { BreadcrumbItem } from '@/types';
@if($resource->verb==='index')
    import type { PaginatedData } from '@/types/pagination';
@endif
    import type { User } from './{{ $resource->componentTypes->filename() }}';

    const { breadcrumbs, user, pageTitle @if($resource->verb==='index'), {{ str($resource->resource)->lower()->plural() }}@endif } = defineProps < {
        breadcrumbs: BreadcrumbItem[];
        user: User;
        pageTitle: string;
@if($resource->verb==='index')
        {{ str($resource->resource)->lower()->plural() }}: PaginatedData<{{ str($resource->resource)->studly()->singular() }}>;
@endif
    }>();

    const columns: Column<{{ str($resource->resource)->studly()->singular() }}>[] = [
@foreach($resource->fillable as $fillable)
        { key: '{{ $fillable }}', label: '{{ str($fillable)->apa() }}'},
@endforeach
    ]

</script>

<template>
    <Head title="pageTitle" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    @{{ pageTitle }}
                </h1>
            </div>

            Hello, @{{user.name}}. Here's all the @{{str($resource->resource)->title()->plural()}}.

@if($resource->verb==='index')
                <DataTable :columns="columns" :data="{{ str($resource->resource)->lower()->plural() }}.data">
                    <template #title="{ row }">
                        <Link :href="`/v/${row.id}`" class="underline">
                                todo, populate me
                        </Link>
                    </template>
                </DataTable>

                <DataPagination
                    :current-page="{{ str($resource->resource)->lower()->plural() }}.current_page"
                    :last-page="{{ str($resource->resource)->lower()->plural() }}.last_page"
                    :per-page="{{ str($resource->resource)->lower()->plural() }}.per_page"
                    :total="{{ str($resource->resource)->lower()->plural() }}.total"
                />
@endif
        </div>
    </AppLayout>
</template>
