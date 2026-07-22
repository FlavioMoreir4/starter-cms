<script setup lang="ts">
import { computed } from 'vue';
import type { Component } from 'vue';
import ContactFormBlock from '@/components/blocks/ContactFormBlock.vue';
import CTABlock from '@/components/blocks/CTABlock.vue';
import FAQBlock from '@/components/blocks/FAQBlock.vue';
import FeaturesBlock from '@/components/blocks/FeaturesBlock.vue';
import FooterBlock from '@/components/blocks/FooterBlock.vue';
import GalleryBlock from '@/components/blocks/GalleryBlock.vue';
import HeroBlock from '@/components/blocks/HeroBlock.vue';
import RichTextBlock from '@/components/blocks/RichTextBlock.vue';
import TestimonialsBlock from '@/components/blocks/TestimonialsBlock.vue';

const props = defineProps<{
    blocks: Array<{
        type: string;
        data: Record<string, unknown>;
    }>;
}>();

const blockComponents: Record<string, Component> = {
    hero: HeroBlock,
    rich_text: RichTextBlock,
    gallery: GalleryBlock,
    features: FeaturesBlock,
    testimonials: TestimonialsBlock,
    cta: CTABlock,
    faq: FAQBlock,
    contact_form: ContactFormBlock,
    footer: FooterBlock,
};

function getBlockComponent(type: string) {
    return blockComponents[type] ?? null;
}

const validBlocks = computed(() =>
    props.blocks.filter((block) => getBlockComponent(block.type) !== null),
);
</script>

<template>
    <div>
        <template v-for="block in validBlocks" :key="block.type + JSON.stringify(block.data)">
            <component :is="getBlockComponent(block.type)" :data="block.data" />
        </template>
    </div>
</template>
