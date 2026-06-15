<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Form from './Form.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    breadcrumbs: BreadcrumbItem[];
    form: Record<string, unknown>;
    options: Record<string, unknown>;
    pageTitle: string;
    language: {
        fields: Record<string, string>;
        form: {
            submit: string;
        };
    };
    submit: {
        method: 'put';
        url: string;
    };
}>();

const form = useForm({ ...props.form });

function submit(): void {
    form.submit(props.submit.method, props.submit.url);
}
</script>

<template>
    <Head :title="props.pageTitle" />

    <AppLayout :breadcrumbs="props.breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    @{{ props.pageTitle }}
                </h1>
            </div>

            <Form :form="form" :language="props.language" :options="props.options" @submit="submit" />
        </div>
    </AppLayout>
</template>
