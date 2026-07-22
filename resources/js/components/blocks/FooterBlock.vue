<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    data: {
        columns?: Array<{
            title?: string;
            links?: Array<{
                label?: string;
                url?: string;
            }>;
        }>;
    };
}>();
</script>

<template>
    <section class="border-t border-border bg-background px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:grid-cols-4">
                <div v-for="(column, index) in data.columns" :key="index">
                    <h3 v-if="column.title" class="mb-4 text-sm font-semibold text-foreground">
                        {{ column.title }}
                    </h3>
                    <ul v-if="column.links?.length" class="space-y-2">
                        <li v-for="(link, linkIndex) in column.links" :key="linkIndex">
                            <Link :href="link.url ?? '#'"
                                class="text-sm text-muted-foreground transition-colors hover:text-foreground">
                                {{ link.label }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>
