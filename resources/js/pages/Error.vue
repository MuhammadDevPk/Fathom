<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Home, Sparkles } from '@lucide/vue';
import { computed } from 'vue';

const props = defineProps<{
    status: number;
}>();

const title = computed(() => {
    return (
        {
            503: '503: Service Unavailable',
            500: '500: Server Error',
            404: '404: Page Not Found',
            403: '403: Forbidden',
        }[props.status] || `${props.status}: Error`
    );
});

const description = computed(() => {
    return (
        {
            503: 'Sorry, we are doing some maintenance on Fathom. Please check back in a few minutes.',
            500: 'Whoops, something went wrong on our servers. Our engineering team has been notified.',
            404: 'Sorry, the meeting recording, transcript, or intelligence page you are looking for could not be found.',
            403: 'Sorry, you do not have permission to access this meeting or resource.',
        }[props.status] || 'An unexpected error occurred. Please try again or return to your meetings.'
    );
});
</script>

<template>
    <Head :title="title" />

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

        <!-- Top Header -->
        <header
            class="relative z-10 mx-auto flex w-full max-w-5xl items-center justify-between"
        >
            <Link
                href="/"
                class="group inline-flex items-center gap-2 rounded-full border border-zinc-200/80 bg-white/80 px-3.5 py-1.5 text-xs font-semibold text-zinc-600 shadow-2xs backdrop-blur-md transition-all duration-200 hover:border-zinc-300 hover:bg-white hover:text-zinc-900 dark:border-zinc-800 dark:bg-zinc-900/80 dark:text-zinc-400 dark:hover:text-zinc-100"
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

        <!-- Center Error Card Container -->
        <main class="relative z-10 mx-auto my-auto w-full max-w-lg py-8 text-center">
            <!-- Brand Badge -->
            <div class="mb-6 flex flex-col items-center">
                <Link
                    href="/"
                    class="group flex items-center gap-3 transition-transform duration-200 hover:scale-105"
                >
                    <div
                        class="flex size-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-sky-500 via-sky-600 to-amber-500 text-white shadow-lg shadow-sky-500/25"
                    >
                        <Sparkles class="size-6" />
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
                class="rounded-3xl border border-zinc-200/80 bg-white/95 p-8 shadow-2xl shadow-slate-200/60 backdrop-blur-md sm:p-10 dark:border-zinc-800 dark:bg-zinc-900/90 dark:shadow-black/50"
            >
                <!-- Status Pill -->
                <div class="mb-4 inline-flex items-center gap-1.5 rounded-full border border-rose-200/70 bg-rose-50 px-3 py-1 font-mono text-xs font-semibold text-rose-700 dark:border-rose-900/50 dark:bg-rose-950/50 dark:text-rose-300">
                    <span>HTTP {{ status }}</span>
                </div>

                <h1
                    class="text-3xl font-extrabold tracking-tight text-zinc-900 sm:text-4xl dark:text-zinc-100"
                >
                    {{ title.split(': ')[1] || title }}
                </h1>

                <p
                    class="mt-3 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400"
                >
                    {{ description }}
                </p>

                <!-- Actions -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <Link
                        href="/meetings"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500 px-5 py-2.5 text-xs font-semibold text-white shadow-md shadow-sky-500/25 transition-all duration-200 hover:brightness-105 hover:shadow-lg hover:shadow-sky-500/35 active:scale-[0.99]"
                    >
                        <span>View Meetings</span>
                    </Link>

                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-5 py-2.5 text-xs font-semibold text-zinc-700 shadow-2xs transition-all duration-200 hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800"
                    >
                        <Home class="size-3.5" />
                        <span>Fathom Home</span>
                    </Link>
                </div>
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
