<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, Sparkles } from '@lucide/vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col justify-between overflow-hidden bg-slate-50/60 p-4 font-sans text-zinc-900 selection:bg-sky-500 selection:text-white sm:p-6 lg:p-8 dark:bg-zinc-950 dark:text-zinc-100"
    >
        <!-- Ambient Mesh Background Washes -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div
                class="absolute -left-20 -top-20 size-[500px] rounded-full bg-gradient-to-br from-sky-200/30 to-sky-400/10 blur-3xl dark:from-sky-900/20 dark:to-transparent"
            />
            <div
                class="absolute -bottom-20 -right-20 size-[500px] rounded-full bg-gradient-to-bl from-amber-200/25 to-rose-300/10 blur-3xl dark:from-amber-900/15 dark:to-transparent"
            />
            <div
                class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:24px_24px] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_70%,transparent_100%)] opacity-40 dark:bg-[radial-gradient(#334155_1px,transparent_1px)] dark:opacity-20"
            />
        </div>

        <!-- Top Navigation Bar -->
        <header
            class="relative z-10 mx-auto flex w-full max-w-5xl items-center justify-between"
        >
            <Link
                :href="home()"
                class="group inline-flex items-center gap-2 rounded-full border border-zinc-200/80 bg-white/80 px-3.5 py-1.5 text-xs font-semibold text-zinc-600 shadow-2xs backdrop-blur-md transition-all duration-200 hover:border-zinc-300 hover:bg-white hover:text-zinc-900 hover:shadow-xs dark:border-zinc-800 dark:bg-zinc-900/80 dark:text-zinc-400 dark:hover:text-zinc-100"
            >
                <ArrowLeft
                    class="size-3.5 transition-transform duration-200 group-hover:-translate-x-0.5"
                />
                <span>Back to Fathom</span>
            </Link>

            <div
                class="inline-flex items-center gap-1.5 rounded-full border border-sky-200/60 bg-sky-50/80 px-2.5 py-1 text-[11px] font-semibold text-sky-700 dark:border-sky-800/60 dark:bg-sky-950/40 dark:text-sky-300"
            >
                <span class="size-1.5 rounded-full bg-sky-500 animate-pulse" />
                <span>AI Meeting Intelligence</span>
            </div>
        </header>

        <!-- Center Auth Card Container -->
        <main class="relative z-10 mx-auto my-auto w-full max-w-md py-6 sm:py-8">
            <!-- Brand Badge -->
            <div class="mb-6 flex flex-col items-center text-center">
                <Link
                    :href="home()"
                    class="group flex items-center gap-3 transition-transform duration-200 hover:scale-105"
                >
                    <div
                        class="flex size-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-sky-500 via-sky-600 to-amber-500 text-white shadow-lg shadow-sky-500/25"
                    >
                        <Sparkles class="size-5" />
                    </div>
                    <span
                        class="text-2xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100"
                    >
                        Fathom
                    </span>
                </Link>
            </div>

            <!-- Elevated SaaS Card -->
            <div
                class="rounded-3xl border border-zinc-200/80 bg-white/95 p-7 shadow-2xl shadow-slate-200/60 backdrop-blur-md sm:p-9 dark:border-zinc-800 dark:bg-zinc-900/90 dark:shadow-black/50"
            >
                <div v-if="title || description" class="mb-6 space-y-1.5 text-center">
                    <h1
                        v-if="title"
                        class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100"
                    >
                        {{ title }}
                    </h1>
                    <p
                        v-if="description"
                        class="text-sm text-zinc-500 dark:text-zinc-400"
                    >
                        {{ description }}
                    </p>
                </div>

                <slot />
            </div>
        </main>

        <!-- Bottom Footer -->
        <footer
            class="relative z-10 mx-auto w-full max-w-5xl py-2 text-center text-xs text-zinc-400 dark:text-zinc-500"
        >
            <p>
                Protected by enterprise session authentication &bull; &copy;
                {{ new Date().getFullYear() }} Fathom AI Inc.
            </p>
        </footer>
    </div>
</template>
