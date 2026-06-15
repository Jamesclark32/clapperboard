<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs, pageTitle, {{ str($resource->resource)->camel()->singular() }}, language } = defineProps<{
    breadcrumbs: BreadcrumbItem[];
    pageTitle: string;
    {{ str($resource->resource)->camel()->singular() }}: Record<string, unknown>;
    language: {
        fields: Record<string, string>;
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
            <pre>@{{ {{ str($resource->resource)->camel()->singular() }} }}</pre>
        </div>
    </AppLayout>
</template>
