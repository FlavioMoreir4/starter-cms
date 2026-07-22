<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { home } from '@/routes';
import admin from '@/routes/filament/admin';
import storage from '@/routes/storage';

const page = usePage();
const site = computed(() => page.props.site as { site_name: string; logo: string | null; primary_color: string });
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-border bg-background">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <Link :href="home()" class="flex items-center gap-2">
                    <img v-if="site.logo" :src="storage.local.url(site.logo)" :alt="site.site_name"
                        class="h-8 w-auto" />
                    <span class="text-lg font-semibold text-foreground">{{ site.site_name }}</span>
                </Link>

                <nav class="flex items-center gap-6">
                    <slot name="nav" />
                    <a v-if="$page.props.auth?.user" :href="admin.pages.dashboard.url()"
                        class="text-sm text-muted-foreground transition-colors hover:text-foreground">
                        Dashboard
                    </a>
                    <template v-else>
                        <a :href="admin.auth.login.url()"
                            class="text-sm text-muted-foreground transition-colors hover:text-foreground">
                            Log in
                        </a>
                        <a :href="admin.auth.register.url()"
                            class="rounded-md bg-foreground px-4 py-2 text-sm font-medium text-background transition-colors hover:opacity-90">
                            Register
                        </a>
                    </template>
                </nav>
            </div>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="border-t border-border bg-background">
            <div
                class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 text-sm text-muted-foreground sm:px-6 lg:px-8">
                <span>&copy; {{ new Date().getFullYear() }} {{ site.site_name }}</span>
                <slot name="footer" />
            </div>
        </footer>
    </div>
</template>
