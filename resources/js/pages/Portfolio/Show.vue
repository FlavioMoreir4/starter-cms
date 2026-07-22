<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import PublicLayout from '@/layouts/PublicLayout.vue';
import portfolio from '@/routes/portfolio';

defineOptions({ layout: PublicLayout });

interface Technology {
    id: number;
    name: string;
}

interface Project {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    body: string | null;
    client: string | null;
    category: string | null;
    repository_url: string | null;
    demo_url: string | null;
    status: string;
    is_featured: boolean;
    sort_order: number;
    technologies: Technology[];
}

defineProps<{
    project: Project;
}>();
</script>

<template>

    <Head :title="project.title || 'Portfólio'" />

    <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <Link :href="portfolio.index()"
            class="mb-8 inline-flex items-center gap-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Voltar ao portfólio
        </Link>

        <article>
            <header class="mb-8">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
                        {{ project.title }}
                    </h1>
                    <Badge v-if="project.category" variant="secondary">
                        {{ project.category }}
                    </Badge>
                </div>

                <p v-if="project.description" class="mt-4 max-w-2xl text-lg text-muted-foreground">
                    {{ project.description }}
                </p>
            </header>

            <Separator class="mb-8" />

            <div v-if="project.body" class="prose prose-neutral dark:prose-invert max-w-none">
                <div v-html="project.body" />
            </div>

            <Separator v-if="project.body" class="my-8" />

            <div class="space-y-8">
                <div v-if="project.technologies?.length">
                    <h2 class="mb-3 text-lg font-semibold text-foreground">
                        Tecnologias
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <Badge v-for="tech in project.technologies" :key="tech.id" variant="secondary">
                            {{ tech.name }}
                        </Badge>
                    </div>
                </div>

                <div v-if="project.client">
                    <h2 class="mb-3 text-lg font-semibold text-foreground">
                        Cliente
                    </h2>
                    <p class="text-muted-foreground">
                        {{ project.client }}
                    </p>
                </div>

                <div v-if="project.repository_url || project.demo_url" class="flex flex-wrap gap-3">
                    <Button v-if="project.repository_url" as="a" :href="project.repository_url" target="_blank"
                        rel="noopener noreferrer" variant="outline">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                            <path
                                d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4" />
                            <path d="M9 18c-4.51 2-5-2-7-2" />
                        </svg>
                        Repositório
                    </Button>

                    <Button v-if="project.demo_url" as="a" :href="project.demo_url" target="_blank"
                        rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                        Demonstração
                    </Button>
                </div>
            </div>
        </article>
    </div>
</template>
