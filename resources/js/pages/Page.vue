<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import BlockRenderer from '@/components/BlockRenderer.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });

defineProps<{
    page: {
        id: number;
        title: string;
        slug: string;
        content: Array<{
            type: string;
            data: Record<string, unknown>;
        }>;
        seo?: {
            title?: string;
            description?: string;
            image?: string;
        };
    };
}>();
</script>

<template>

    <Head :title="page.title" />

    <div class="min-h-screen">
        <BlockRenderer v-if="page.content?.length" :blocks="page.content" />
    </div>
</template>
