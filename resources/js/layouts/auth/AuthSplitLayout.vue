<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Sparkles } from '@lucide/vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center bg-slate-50/60 font-sans text-zinc-900 selection:bg-sky-500 selection:text-white sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 dark:bg-zinc-950 dark:text-zinc-100"
    >
        <div
            class="relative hidden h-full flex-col justify-between overflow-hidden bg-zinc-950 p-12 text-white lg:flex"
        >
            <div
                class="absolute -left-20 -top-20 size-[500px] rounded-full bg-gradient-to-br from-sky-500/20 to-sky-700/10 blur-3xl pointer-events-none"
            />
            <div
                class="absolute -right-20 -bottom-20 size-[500px] rounded-full bg-gradient-to-bl from-amber-500/15 to-orange-700/10 blur-3xl pointer-events-none"
            />

            <Link
                :href="home()"
                class="relative z-20 flex items-center gap-3 text-xl font-extrabold tracking-tight"
            >
                <div
                    class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-tr from-sky-500 via-sky-600 to-amber-500 text-white shadow-md shadow-sky-500/25"
                >
                    <Sparkles class="size-5" />
                </div>
                <span>Fathom</span>
            </Link>

            <div class="relative z-20 max-w-md">
                <p class="text-xl font-bold leading-relaxed text-zinc-100">
                    "Fathom turns multi-hour meeting recordings into synchronized,
                    searchable transcripts and executive summaries in seconds."
                </p>
                <div class="mt-4 flex items-center gap-3">
                    <div
                        class="flex size-9 items-center justify-center rounded-full bg-sky-500/20 text-xs font-bold text-sky-400"
                    >
                        AI
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-200">
                            Fathom Intelligence Copilot
                        </p>
                        <p class="text-xs text-zinc-400">
                            Enterprise Meeting Platform
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative z-20 text-xs text-zinc-500">
                &copy; {{ new Date().getFullYear() }} Fathom AI Inc. All rights reserved.
            </div>
        </div>

        <div class="p-6 lg:p-12">
            <div
                class="mx-auto flex w-full max-w-md flex-col justify-center space-y-6"
            >
                <div
                    v-if="title || description"
                    class="flex flex-col space-y-2 text-center lg:text-left"
                >
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
        </div>
    </div>
</template>
