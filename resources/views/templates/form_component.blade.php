<script setup lang="ts">
const props = defineProps<{
    form: any;
    options: Record<string, unknown>;
    language: {
        fields: Record<string, string>;
        form: {
            submit: string;
        };
    };
}>();

const emit = defineEmits<{
    submit: [];
}>();

function optionValue(option: unknown): string | number | null {
    if (typeof option === 'object' && option !== null && 'value' in option) {
        return option.value as string | number | null;
    }

    return option as string | number | null;
}

function optionLabel(option: unknown): string {
    if (typeof option === 'object' && option !== null && 'label' in option) {
        return String(option.label);
    }

    return String(optionValue(option) ?? '');
}
</script>

<template>
    <form class="space-y-6" @submit.prevent="emit('submit')">
@foreach($resource->writableFillable() as $field)
        <div class="space-y-2">
@if(str($field)->startsWith(['is_', 'has_', 'can_', 'should_', 'will_', 'was_']))
            <label class="flex items-center gap-3 rounded-lg border border-gray-200 px-3 py-2 dark:border-gray-800">
                <input id="{{ $field }}" v-model="props.form.{{ $field }}" type="checkbox" class="h-4 w-4 rounded border-gray-300" />
                <span class="text-sm text-gray-700 dark:text-gray-200">@{{ props.language.fields.{{ $field }} ?? '{{ str($field)->apa()->toString() }}' }}</span>
            </label>
@else
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200" for="{{ $field }}">
                @{{ props.language.fields.{{ $field }} ?? '{{ str($field)->apa()->toString() }}' }}
            </label>

            <select
                v-if="Array.isArray(props.options.{{ $field }})"
                id="{{ $field }}"
                v-model="props.form.{{ $field }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900"
            >
                <option :value="null"></option>
                <option v-for="(option, index) in props.options.{{ $field }}" :key="`${index}-${optionLabel(option)}`" :value="optionValue(option)">
                    @{{ optionLabel(option) }}
                </option>
            </select>
            <textarea
                v-else-if="'{{ $field }}' === 'description'"
                id="{{ $field }}"
                v-model="props.form.{{ $field }}"
                rows="4"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900"
            />
            <input
                v-else
                id="{{ $field }}"
                v-model="props.form.{{ $field }}"
                type="text"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900"
            />
@endif

            <p v-if="props.form.errors.{{ $field }}" class="text-sm text-red-600 dark:text-red-400">
                @{{ props.form.errors.{{ $field }} }}
            </p>
        </div>
@endforeach

        <div>
            <button
                type="submit"
                :disabled="props.form.processing"
                class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white disabled:opacity-50 dark:bg-gray-100 dark:text-gray-900"
            >
                @{{ props.language.form.submit }}
            </button>
        </div>
    </form>
</template>
