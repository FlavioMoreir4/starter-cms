<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { home } from '@/routes';

defineOptions({ layout: PublicLayout });

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedPosts {
    data: Array<{
        id: number;
        title: string;
        slug: string;
        excerpt: string | null;
        published_at: string | null;
        is_featured: boolean;
        category: { id: number; name: string; slug: string } | null;
        tags: Array<{ id: number; name: string; slug: string }>;
    }>;
    links: PaginationLink[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

const props = defineProps<{
    posts: PaginatedPosts;
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

function goToPage(url: string | null) {
    if (!url) {
        return;
    }

    router.get(url, {}, { preserveState: true, preserveScroll: true });
}
</script>

<template>

    <Head title="Blog" />

    <!-- Page Header -->
    <section class="border-b border-border bg-muted/30">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
                Blog
            </h1>
            <p class="mt-3 text-lg text-muted-foreground">
                Artigos, tutoriais e reflexões sobre desenvolvimento e tecnologia.
            </p>
        </div>
    </section>

    <!-- Posts Grid -->
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <!-- Empty State -->
        <div v-if="props.posts.data.length === 0" class="py-16 text-center">
            <p class="text-lg text-muted-foreground">
                Nenhum artigo publicado ainda.
            </p>
            <Button variant="outline" as-child class="mt-4">
                <Link :href="home()">Voltar ao início</Link>
            </Button>
        </div>

        <!-- Posts List -->
        <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <Link v-for="post in props.posts.data" :key="post.id" :href="`/blog/${post.slug}`" class="group block">
                <Card class="h-full transition-colors group-hover:border-foreground/20">
                    <CardHeader>
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            <Badge v-if="post.category" variant="secondary">
                                {{ post.category.name }}
                            </Badge>
                            <Badge v-if="post.is_featured" variant="default">
                                Destaque
                            </Badge>
                        </div>
                        <CardTitle class="line-clamp-2 text-lg group-hover:text-primary">
                            {{ post.title }}
                        </CardTitle>
                        <CardDescription v-if="post.excerpt" class="line-clamp-3">
                            {{ post.excerpt }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-muted-foreground">
                                {{ formatDate(post.published_at) }}
                            </span>
                            <div v-if="post.tags.length > 0" class="flex gap-1">
                                <Badge v-for="tag in post.tags.slice(0, 2)" :key="tag.id" variant="outline"
                                    class="text-xs">
                                    {{ tag.name }}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </Link>
        </div>

        <!-- Pagination -->
        <nav v-if="props.posts.last_page > 1" class="mt-10 flex items-center justify-center gap-1"
            aria-label="Paginação">
            <!-- Previous -->
            <Button variant="outline" size="sm"
                :disabled="!props.posts.links.find((l) => l.label === '&laquo; Anterior')?.url" @click="
                    goToPage(props.posts.links.find((l) => l.label === '&laquo; Anterior')?.url ?? null)
                    ">
                &laquo; Anterior
            </Button>

            <!-- Page Numbers -->
            <template v-for="link in props.posts.links" :key="link.label">
                <Button
                    v-if="link.url && !link.label.includes('...') && !link.label.includes('Anterior') && !link.label.includes('Próximo')"
                    :variant="link.active ? 'default' : 'outline'" size="sm" class="min-w-9"
                    @click="goToPage(link.url)">
                    {{ link.label }}
                </Button>
            </template>

            <!-- Next -->
            <Button variant="outline" size="sm"
                :disabled="!props.posts.links.find((l) => l.label.includes('Próximo'))?.url" @click="
                    goToPage(props.posts.links.find((l) => l.label.includes('Próximo'))?.url ?? null)
                    ">
                Próximo &raquo;
            </Button>
        </nav>
    </section>
</template>
