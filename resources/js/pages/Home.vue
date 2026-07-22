<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import blog from '@/routes/blog';
import portfolio from '@/routes/portfolio';

defineOptions({ layout: PublicLayout });

defineProps<{
    homepage: {
        id: number;
        title: string;
        slug: string;
        content: Record<string, unknown> | null;
    };
    featuredPosts: Array<{
        id: number;
        title: string;
        slug: string;
        excerpt: string | null;
        published_at: string | null;
        category: { id: number; name: string; slug: string } | null;
        author: { id: number; name: string } | null;
    }>;
    featuredProjects: Array<{
        id: number;
        title: string;
        slug: string;
        description: string | null;
        technologies: Array<{ id: number; name: string }>;
        repository_url: string | null;
        demo_url: string | null;
    }>;
}>();

function formatDate(dateString: string | null): string {
    if (!dateString) {
        return '';
    }

    return new Date(dateString).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}
</script>

<template>

    <Head :title="homepage?.title ?? 'Inicio'" />

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-linear-to-b from-background to-muted/30">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="text-4xl font-bold tracking-tight text-foreground sm:text-5xl lg:text-6xl">
                    {{ homepage?.title ?? 'Bem-vindo' }}
                </h1>
                <p class="mt-6 text-lg leading-8 text-muted-foreground">
                    Explore nossos artigos, projetos e conteúdos exclusivos.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-4">
                    <Button as-child>
                        <Link :href="blog.index()">Ver Blog</Link>
                    </Button>
                    <Button variant="outline" as-child>
                        <Link :href="portfolio.index()">Projetos</Link>
                    </Button>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts -->
    <section v-if="featuredPosts.length > 0" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h2 class="text-2xl font-bold tracking-tight text-foreground sm:text-3xl">
                Artigos em Destaque
            </h2>
            <p class="mt-2 text-muted-foreground">
                Confira nossos conteúdos mais recentes.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <Link v-for="post in featuredPosts" :key="post.id" :href="blog.show(post.slug)" class="group block">
                <Card class="h-full transition-colors group-hover:border-foreground/20">
                    <CardHeader>
                        <div class="mb-2 flex items-center gap-2">
                            <Badge v-if="post.category" variant="secondary">
                                {{ post.category.name }}
                            </Badge>
                            <span v-if="post.published_at" class="text-xs text-muted-foreground">
                                {{ formatDate(post.published_at) }}
                            </span>
                        </div>
                        <CardTitle class="line-clamp-2 text-lg group-hover:text-primary">
                            {{ post.title }}
                        </CardTitle>
                        <CardDescription v-if="post.excerpt" class="line-clamp-3">
                            {{ post.excerpt }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent v-if="post.author">
                        <p class="text-sm text-muted-foreground">
                            Por {{ post.author.name }}
                        </p>
                    </CardContent>
                </Card>
            </Link>
        </div>

        <div class="mt-8 text-center">
            <Button variant="ghost" as-child>
                <Link :href="blog.index()">Ver todos os artigos &rarr;</Link>
            </Button>
        </div>
    </section>

    <!-- Featured Projects -->
    <section v-if="featuredProjects.length > 0" id="projetos" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h2 class="text-2xl font-bold tracking-tight text-foreground sm:text-3xl">
                Projetos em Destaque
            </h2>
            <p class="mt-2 text-muted-foreground">
                Conheça nossos trabalhos e soluções mais relevantes.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="project in featuredProjects" :key="project.id" class="group">
                <Card class="h-full transition-colors group-hover:border-foreground/20">
                    <CardHeader>
                        <CardTitle class="text-lg">
                            {{ project.title }}
                        </CardTitle>
                        <CardDescription v-if="project.description" class="line-clamp-3">
                            {{ project.description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="mt-auto">
                        <div v-if="project.technologies.length > 0" class="mb-4 flex flex-wrap gap-1.5">
                            <Badge v-for="tech in project.technologies" :key="tech.id" variant="outline"
                                class="text-xs">
                                {{ tech.name }}
                            </Badge>
                        </div>
                        <div class="flex gap-3">
                            <a v-if="project.demo_url" :href="project.demo_url" target="_blank"
                                rel="noopener noreferrer"
                                class="text-sm font-medium text-primary underline-offset-4 hover:underline">
                                Demo ao vivo
                            </a>
                            <a v-if="project.repository_url" :href="project.repository_url" target="_blank"
                                rel="noopener noreferrer"
                                class="text-sm font-medium text-muted-foreground underline-offset-4 hover:underline">
                                Repositório
                            </a>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </section>
</template>
