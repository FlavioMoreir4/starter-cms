<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    data: {
        title?: string;
        description?: string;
        button_text?: string;
        button_url?: string;
        background_color?: string;
    };
}>();
</script>

<template>
    <section class="px-4 py-16 sm:px-6 lg:px-8" :style="{ backgroundColor: data.background_color ?? '#1a1a1a' }">
        <div class="mx-auto max-w-4xl text-center">
            <h2 class="text-3xl font-bold text-white sm:text-4xl">
                {{ data.title }}
            </h2>
            <p v-if="data.description" class="mx-auto mt-4 max-w-2xl text-lg text-white/80">
                {{ data.description }}
            </p>
            <div v-if="data.button_text && data.button_url" class="mt-8">
                <Link :href="data.button_url"
                    class="inline-flex items-center rounded-md bg-white px-6 py-3 text-sm font-semibold text-foreground shadow-sm transition-colors hover:bg-white/90">
                    {{ data.button_text }}
                </Link>
            </div>
        </div>
    </section>
</template>
