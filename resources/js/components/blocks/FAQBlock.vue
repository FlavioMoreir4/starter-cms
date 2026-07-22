<script setup lang="ts">
import { ref } from 'vue';

defineProps<{
    data: {
        items?: Array<{
            question?: string;
            answer?: string;
        }>;
    };
}>();

const openIndex = ref<number | null>(null);

function toggle(index: number) {
    openIndex.value = openIndex.value === index ? null : index;
}
</script>

<template>
    <section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="divide-y divide-border">
            <div v-for="(item, index) in data.items" :key="index">
                <button
                    class="flex w-full items-center justify-between py-4 text-left text-sm font-medium text-foreground transition-colors hover:text-foreground/80"
                    @click="toggle(index)">
                    <span>{{ item.question }}</span>
                    <svg class="h-5 w-5 shrink-0 text-muted-foreground transition-transform duration-200"
                        :class="{ 'rotate-180': openIndex === index }" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div v-if="openIndex === index" class="pb-4 text-sm text-muted-foreground">
                    {{ item.answer }}
                </div>
            </div>
        </div>
    </section>
</template>
