<script setup lang="ts">
import storage from '@/routes/storage';

defineProps<{
    data: {
        items?: Array<{
            quote?: string;
            author_name?: string;
            author_role?: string;
            avatar?: string;
        }>;
    };
}>();
</script>

<template>
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="(item, index) in data.items" :key="index"
                class="flex flex-col gap-4 rounded-lg border border-border p-6">
                <p class="text-sm italic text-foreground">
                    "{{ item.quote }}"
                </p>
                <div class="flex items-center gap-3">
                    <img v-if="item.avatar" :src="storage.local.url(item.avatar)" :alt="item.author_name"
                        class="h-10 w-10 rounded-full object-cover" />
                    <div v-else
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-muted text-sm font-medium text-muted-foreground">
                        {{ item.author_name?.charAt(0) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-foreground">
                            {{ item.author_name }}
                        </p>
                        <p v-if="item.author_role" class="text-xs text-muted-foreground">
                            {{ item.author_role }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
