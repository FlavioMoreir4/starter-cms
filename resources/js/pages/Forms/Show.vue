<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DynamicForm from '@/components/DynamicForm.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({
    layout: PublicLayout,
});

interface FormField {
    type?: string;
    title?: string;
    description?: string;
    format?: string;
    'x-filament'?: Record<string, unknown>;
}

interface FormSchema {
    type?: string;
    properties?: Record<string, FormField>;
    required?: string[];
}

interface Form {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    schema: FormSchema;
    success_message: string | null;
}

const props = defineProps<{
    form: Form;
}>();

const submissionUrl = `/formularios/${props.form.slug}`;
</script>

<template>

    <Head :title="form.name || 'Formulário'" />

    <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <div class="space-y-2">
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    {{ form.name }}
                </h1>
                <p v-if="form.description" class="text-muted-foreground">
                    {{ form.description }}
                </p>
            </div>

            <DynamicForm :schema="form.schema" :action="submissionUrl"
                :success-message="form.success_message ?? undefined" />
        </div>
    </div>
</template>
