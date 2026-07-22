<script setup lang="ts">
defineProps<{
    data: {
        content?: string;
    };
}>();
</script>

<template>
    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <div v-if="data.content" class="prose prose-lg max-w-none dark:prose-invert" v-html="data.content" />
    </section>
</template>
