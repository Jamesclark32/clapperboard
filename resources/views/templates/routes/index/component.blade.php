<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { create, destroy, edit, show } from '@/routes/{{ str($resource->resource)->lower()->plural() }}';
import type { BreadcrumbItem } from '@/types';

type Row = Record<string, unknown> & {
    id: number | string;
};

const props = defineProps<{
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

const {{ str($resource->resource)->lower()->plural() }} = props.{{ str($resource->resource)->lower()->plural() }};
const language = props.language;
const pendingDelete = ref<Row | null>(null);

function openDeleteModal(row: Record<string, unknown>): void {
    pendingDelete.value = row as Row;
}

function closeDeleteModal(): void {
    pendingDelete.value = null;
}

function destroyRow(): void {
    if (!pendingDelete.value) {
        return;
    }

    router.delete(destroy.url({ widget: pendingDelete.value.id }), {
        preserveScroll: true,
        onFinish: closeDeleteModal,
    });
}
</script>

<template>
    <Head :title="props.pageTitle" />
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                @{{ props.pageTitle }}
            </h1>

            <Link
                :href="create()"
                class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"
            >
                Create new {{ str($resource->resource)->lower()->singular() }}
            </Link>
        </div>

        @{{ language.messages.description }}

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
@foreach($resource->writableFillable() as $fillable)
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">@php echo "{{ language.fields['{$fillable}'] }}" @endphp</th>
@endforeach
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-950">
                    <tr v-for="row in {{ str($resource->resource)->lower()->plural() }}.data" :key="String(row.id)">
@foreach($resource->writableFillable() as $fillable)
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">@php echo "{{ row['{$fillable}'] ?? ' - ' }}" @endphp</td>
@endforeach
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            <Link :href="show.url({ widget: row.id as string | number })" class="underline">
                                @{{ row.id }}
                            </Link>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <Link
                                    :href="show.url({ widget: row.id as string | number })"
                                    class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-900"
                                >
                                    Show
                                </Link>

                                <Link
                                    :href="edit.url({ widget: row.id as string | number })"
                                    class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-900"
                                >
                                    Edit
                                </Link>

                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-red-300 px-2.5 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50 dark:border-red-900 dark:text-red-300 dark:hover:bg-red-950/50"
                                    @click="openDeleteModal(row)"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-sm text-gray-600 dark:text-gray-300">
            Page @php echo '{{ '.str($resource->resource)->lower()->plural()->toString().'.current_page }}' @endphp of @php echo '{{ '.str($resource->resource)->lower()->plural()->toString().'.last_page }}' @endphp
            • @php echo '{{ '.str($resource->resource)->lower()->plural()->toString().'.total }}' @endphp total
            • @php echo '{{ '.str($resource->resource)->lower()->plural()->toString().'.per_page }}' @endphp per page
        </div>

        <div v-if="pendingDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl dark:bg-gray-900">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Delete {{ str($resource->resource)->title()->singular() }}?</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    This action cannot be undone.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                        @click="closeDeleteModal"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-500"
                        @click="destroyRow"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
