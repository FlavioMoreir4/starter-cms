<script setup lang="ts">
defineProps<{
    data: {
        items?: Array<{
            icon?: string;
            title?: string;
            description?: string;
        }>;
    };
}>();
</script>

<template>
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="(item, index) in data.items" :key="index"
                class="flex flex-col items-start gap-4 rounded-lg border border-border p-6">
                <div v-if="item.icon" class="flex h-12 w-12 items-center justify-center rounded-md bg-muted text-xl">
                    {{ item.icon }}
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-foreground">
                        {{ item.title }}
                    </h3>
                    <p v-if="item.description" class="mt-2 text-sm text-muted-foreground">
                        {{ item.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
