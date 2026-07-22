<script setup lang="ts">
import storage from '@/routes/storage';

defineProps<{
    data: {
        images?: string[];
        caption?: string;
    };
}>();
</script>

<template>
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div v-if="data.images?.length" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="(image, index) in data.images" :key="index" class="overflow-hidden rounded-lg">
                <img :src="storage.local.url(image)" :alt="data.caption ?? `Imagem ${index + 1}`"
                    class="h-full w-full object-cover" />
            </div>
        </div>
        <p v-if="data.caption" class="mt-4 text-center text-sm text-muted-foreground">
            {{ data.caption }}
        </p>
    </section>
</template>
