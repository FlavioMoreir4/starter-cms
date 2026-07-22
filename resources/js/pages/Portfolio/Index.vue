<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
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
    projects: Project[];
}>();
</script>

<template>

    <Head title="Portfólio" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
                Portfólio
            </h1>
            <p class="mt-3 max-w-2xl text-lg text-muted-foreground">
                Conheça nossos projetos e trabalhos realizados.
            </p>
        </div>

        <div v-if="projects.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <Link v-for="project in projects" :key="project.id" :href="portfolio.show(project.slug)"
                class="group block">
                <Card class="h-full transition-colors hover:border-muted-foreground/25">
                    <CardHeader>
                        <CardTitle class="group-hover:text-primary transition-colors">
                            {{ project.title }}
                        </CardTitle>
                        <CardDescription v-if="project.category">
                            {{ project.category }}
                        </CardDescription>
                    </CardHeader>

                    <CardContent class="flex flex-col gap-4">
                        <p v-if="project.description" class="line-clamp-3 text-sm text-muted-foreground">
                            {{ project.description }}
                        </p>

                        <div v-if="project.technologies?.length" class="flex flex-wrap gap-1.5">
                            <Badge v-for="tech in project.technologies" :key="tech.id" variant="secondary">
                                {{ tech.name }}
                            </Badge>
                        </div>
                    </CardContent>
                </Card>
            </Link>
        </div>

        <div v-else class="flex min-h-[40vh] items-center justify-center">
            <p class="text-muted-foreground">
                Nenhum projeto publicado no momento.
            </p>
        </div>
    </div>
</template>
