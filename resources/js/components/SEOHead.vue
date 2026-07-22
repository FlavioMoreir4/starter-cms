<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import storage from '@/routes/storage'

interface SeoData {
    title?: string
    description?: string
    robots?: string
    canonical_url?: string
    open_graph_title?: string
    open_graph_description?: string
    open_graph_image?: string
    open_graph_url?: string
    open_graph_type?: string
    site_name?: string
    twitter_card?: string
    twitter_title?: string
    twitter_description?: string
    twitter_image?: string
    schema?: Record<string, unknown>
}

defineProps<{
    seo: SeoData
}>()
</script>

<template>

    <Head :title="seo.title">
        <meta v-if="seo.description" name="description" :content="seo.description" />
        <meta v-if="seo.robots" name="robots" :content="seo.robots" />
        <link v-if="seo.canonical_url" rel="canonical" :href="seo.canonical_url" />

        <!-- Open Graph -->
        <meta property="og:title" :content="seo.open_graph_title || seo.title" />
        <meta property="og:description" :content="seo.open_graph_description || seo.description" />
        <meta v-if="seo.open_graph_image" property="og:image" :content="seo.open_graph_image" />
        <meta v-if="seo.open_graph_url" property="og:url" :content="storage.local.url(seo.open_graph_url)" />
        <meta property="og:type" :content="seo.open_graph_type || 'website'" />
        <meta v-if="seo.site_name" property="og:site_name" :content="seo.site_name" />

        <!-- Twitter Card -->
        <meta name="twitter:card" :content="seo.twitter_card || 'summary_large_image'" />
        <meta name="twitter:title" :content="seo.twitter_title || seo.title" />
        <meta name="twitter:description" :content="seo.twitter_description || seo.description" />
        <meta v-if="seo.twitter_image || seo.open_graph_image" name="twitter:image"
            :content="seo.twitter_image || seo.open_graph_image" />

        <!-- JSON-LD -->
        <script v-if="seo.schema" type="application/ld+json" v-html="JSON.stringify(seo.schema)" />
    </Head>
</template>
