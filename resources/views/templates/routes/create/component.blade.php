<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Language, User } from './{{ $resource->componentTypes->filename() }}';

const { breadcrumbs, pageTitle, language } = defineProps<{
    breadcrumbs: BreadcrumbItem[];
    user: User;
    pageTitle: string;
    language: Language;
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

            @{{ language.messages.greeting }}
        </div>
    </AppLayout>
</template>
