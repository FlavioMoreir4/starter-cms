<script setup lang="ts">
import DynamicForm from '@/components/DynamicForm.vue';

defineProps<{
    data: {
        title?: string;
        form_id?: number;
    };
}>();
</script>

<template>
    <section class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
        <h2
            v-if="data.title"
            class="mb-8 text-center text-2xl font-bold text-foreground"
        >
            {{ data.title }}
        </h2>
        <DynamicForm
            v-if="data.form_id"
            :form-id="data.form_id"
        />
    </section>
</template>
