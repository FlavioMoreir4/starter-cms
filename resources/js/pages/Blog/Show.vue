<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import PublicLayout from '@/layouts/PublicLayout.vue';
import blog from '@/routes/blog';

defineOptions({ layout: PublicLayout });

defineProps<{
    post: {
        id: number;
        title: string;
        slug: string;
        excerpt: string | null;
        body: string | null;
        published_at: string | null;
        category: { id: number; name: string; slug: string } | null;
        tags: Array<{ id: number; name: string; slug: string }>;
        author: { id: number; name: string } | null;
    };
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

function initials(name: string): string {
    return name
        .split(' ')
        .map((part) => part.charAt(0))
        .slice(0, 2)
        .join('')
        .toUpperCase();
}
</script>

<template>

    <Head :title="post.title" />

    <!-- Article Header -->
    <article>
        <header class="border-b border-border bg-muted/30">
            <div class="mx-auto max-w-3xl px-4 pt-12 pb-8 sm:px-6 sm:pt-16 sm:pb-10 lg:px-8">
                <!-- Category & Date -->
                <div class="mb-4 flex flex-wrap items-center gap-3">
                    <Badge v-if="post.category" variant="secondary">
                        {{ post.category.name }}
                    </Badge>
                    <time v-if="post.published_at" :datetime="post.published_at" class="text-sm text-muted-foreground">
                        {{ formatDate(post.published_at) }}
                    </time>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl lg:text-5xl">
                    {{ post.title }}
                </h1>

                <!-- Excerpt -->
                <p v-if="post.excerpt" class="mt-4 text-lg leading-relaxed text-muted-foreground">
                    {{ post.excerpt }}
                </p>
            </div>
        </header>

        <!-- Article Body -->
        <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
            <div v-if="post.body" class="prose prose-neutral dark:prose-invert max-w-none" v-html="post.body" />

            <!-- Tags -->
            <div v-if="post.tags.length > 0" class="mt-10">
                <Separator class="mb-6" />
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-wider text-muted-foreground">
                    Tags
                </h2>
                <div class="flex flex-wrap gap-2">
                    <Badge v-for="tag in post.tags" :key="tag.id" variant="outline">
                        {{ tag.name }}
                    </Badge>
                </div>
            </div>

            <!-- Author Info -->
            <div v-if="post.author" class="mt-10">
                <Separator class="mb-6" />
                <div class="flex items-center gap-4">
                    <Avatar class="h-12 w-12">
                        <AvatarFallback>
                            {{ initials(post.author.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">
                            Escrito por
                        </p>
                        <p class="font-semibold text-foreground">
                            {{ post.author.name }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Back to Blog -->
            <div class="mt-12">
                <Separator class="mb-6" />
                <Button variant="ghost" as-child>
                    <Link :href="blog.index()">&larr; Voltar ao blog</Link>
                </Button>
            </div>
        </div>
    </article>
</template>
