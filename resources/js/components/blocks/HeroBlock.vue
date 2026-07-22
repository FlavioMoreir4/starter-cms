<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import storage from '@/routes/storage';

defineProps<{
    data: {
        title?: string;
        subtitle?: string;
        background_image?: string;
        button_text?: string;
        button_url?: string;
    };
}>();
</script>

<template>
    <section class="relative flex min-h-125 items-center justify-center overflow-hidden bg-muted">
        <div v-if="data.background_image" class="absolute inset-0 bg-cover bg-center"
            :style="{ backgroundImage: `url(${storage.local.url(data.background_image)})` }" />
        <div class="absolute inset-0 bg-black/50" />
        <div class="relative z-10 mx-auto max-w-4xl px-4 text-center text-white sm:px-6 lg:px-8">
            <h1 v-if="data.title" class="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
                {{ data.title }}
            </h1>
            <p v-if="data.subtitle" class="mx-auto mt-6 max-w-2xl text-lg text-white/80">
                {{ data.subtitle }}
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
