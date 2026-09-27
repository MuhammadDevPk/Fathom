<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowRight,
    Bookmark,
    Bot,
    CheckCircle2,
    Clock,
    FileText,
    HelpCircle,
    Layers,
    ListTodo,
    MessageSquare,
    Play,
    Search,
    Shield,
    Sparkles,
    User,
    Video,
    Zap,
} from '@lucide/vue';
import { computed } from 'vue';
import { dashboard, login, register } from '@/routes';

const page = usePage();
const user = computed(() => page.props.auth?.user);

const features = [
    {
        title: 'Instant Playback Sync',
        description:
            'Click any transcript cue to seek video playback to that exact moment with sub-second accuracy. The transcript auto-scrolls and highlights active speakers in real time.',
        icon: Clock,
        color: 'sky',
        badgeClass: 'bg-sky-50 text-sky-700 border-sky-200/70 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60',
        iconClass: 'text-sky-600 dark:text-sky-400',
    },
    {
        title: 'Perspective-Driven Summaries',
        description:
            'Switch dynamically between General Executive, Commercial Sales, and Systems Engineering perspectives using Inertia v3 deferred props without full page reloads.',
        icon: Sparkles,
        color: 'amber',
        badgeClass: 'bg-amber-50 text-amber-700 border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60',
        iconClass: 'text-amber-600 dark:text-amber-400',
    },
    {
        title: 'Ask AI with Video Citations',
        description:
            'Ask natural questions about decisions, deadlines, or technical tradeoffs. Fathom generates verified answers with clickable timestamp citations directly to video playback.',
        icon: Bot,
        color: 'indigo',
        badgeClass: 'bg-indigo-50 text-indigo-700 border-indigo-200/70 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/60',
        iconClass: 'text-indigo-600 dark:text-indigo-400',
    },
    {
        title: 'Automated Action Items',
        description:
            'Extracts actionable follow-ups, owners, and deliverables with speaker attribution badges and interactive session checklists.',
        icon: ListTodo,
        color: 'emerald',
        badgeClass: 'bg-emerald-50 text-emerald-700 border-emerald-200/70 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
        iconClass: 'text-emerald-600 dark:text-emerald-400',
    },
    {
        title: 'Key Moment Bookmarks',
        description:
            'Save critical takeaways and custom notes directly from transcript dialogue cues with category tagging and instant video seeking.',
        icon: Bookmark,
        color: 'purple',
        badgeClass: 'bg-purple-50 text-purple-700 border-purple-200/70 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/60',
        iconClass: 'text-purple-600 dark:text-purple-400',
    },
    {
        title: 'Global Full-Text Search',
        description:
            'Debounced live search filters meetings by title or spoken dialogue across all recordings with zero lag and instant card feedback.',
        icon: Search,
        color: 'rose',
        badgeClass: 'bg-rose-50 text-rose-700 border-rose-200/70 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60',
        iconClass: 'text-rose-600 dark:text-rose-400',
    },
];

const steps = [
    {
        step: 'Step 01',
        title: 'Pick a Meeting',
        description:
            'Select any recorded session from your dashboard to immediately load synchronized video, speaker labels, and timestamped transcripts.',
        pillClass: 'bg-sky-50 text-sky-700 border-sky-200/70 dark:bg-sky-950/40 dark:text-sky-300',
    },
    {
        step: 'Step 02',
        title: 'Review Intelligence',
        description:
            'Scan synthesized key decisions, toggle executive perspective templates, and track assigned action items with one click.',
        pillClass: 'bg-amber-50 text-amber-700 border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-300',
    },
    {
        step: 'Step 03',
        title: 'Ask Anything',
        description:
            'Query meeting discussions with natural language and jump straight to the exact moments cited in video playback.',
        pillClass: 'bg-emerald-50 text-emerald-700 border-emerald-200/70 dark:bg-emerald-950/40 dark:text-emerald-300',
    },
];

const techStack = [
    { name: 'Laravel 13', role: 'Full-Stack Backend' },
    { name: 'Vue 3.5', role: 'Reactive Frontend' },
    { name: 'Inertia v3', role: 'SPA Data Bridge' },
    { name: 'Tailwind CSS v4', role: 'Design Tokens' },
    { name: 'Reka UI', role: 'Headless Primitives' },
    { name: 'Groq Llama 3', role: 'High-Speed LLM' },
];
</script>

<template>
    <Head title="Fathom - AI Meeting Intelligence Platform">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="relative min-h-screen bg-white font-sans text-zinc-900 selection:bg-sky-500 selection:text-white dark:bg-zinc-950 dark:text-zinc-100">
        <!-- Ambient Mesh Background Washes -->
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[600px] overflow-hidden">
            <div class="absolute -left-40 -top-40 size-[550px] rounded-full bg-gradient-to-br from-sky-200/30 to-sky-400/10 blur-3xl dark:from-sky-900/20 dark:to-transparent" />
            <div class="absolute -right-40 -top-40 size-[550px] rounded-full bg-gradient-to-bl from-amber-200/25 to-rose-300/10 blur-3xl dark:from-amber-900/15 dark:to-transparent" />
        </div>

        <!-- 1. Top Nav Header -->
        <header class="sticky top-0 z-50 border-b border-zinc-200/60 bg-white/80 backdrop-blur-md dark:border-zinc-800/80 dark:bg-zinc-950/80">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2.5 transition-opacity hover:opacity-90">
                    <div class="flex size-9 items-center justify-center rounded-xl bg-gradient-to-tr from-sky-500 via-sky-600 to-amber-500 text-white shadow-xs">
                        <Sparkles class="size-5" />
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100">Fathom</span>
                </Link>

                <!-- Center Nav Links -->
                <nav class="hidden items-center gap-8 md:flex">
                    <a
                        href="#hero"
                        class="text-xs font-semibold text-zinc-600 transition-colors duration-200 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
                    >
                        Home
                    </a>
                    <a
                        href="#features"
                        class="text-xs font-semibold text-zinc-600 transition-colors duration-200 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
                    >
                        Features
                    </a>
                    <a
                        href="#how-it-works"
                        class="text-xs font-semibold text-zinc-600 transition-colors duration-200 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
                    >
                        How it works
                    </a>
                    <Link
                        href="/meetings"
                        class="text-xs font-semibold text-sky-600 transition-colors duration-200 hover:text-sky-700 dark:text-sky-400"
                    >
                        Demo Meetings
                    </Link>
                </nav>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    <template v-if="user">
                        <Link
                            :href="dashboard()"
                            class="inline-flex items-center gap-2 rounded-full bg-zinc-950 px-4 py-2 text-xs font-semibold text-white shadow-xs transition-all duration-200 hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            <span>Dashboard</span>
                            <ArrowRight class="size-3" />
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="rounded-full px-4 py-2 text-xs font-semibold text-zinc-700 transition-colors duration-200 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-white"
                        >
                            Sign in
                        </Link>
                        <Link
                            href="/meetings"
                            class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500 px-4 py-2 text-xs font-semibold text-white shadow-xs transition-all duration-200 hover:opacity-95 active:scale-95"
                        >
                            <span>Try the Demo</span>
                            <ArrowRight class="size-3" />
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main>
            <!-- 2. Hero Section -->
            <section id="hero" class="relative overflow-hidden pt-16 pb-20 md:pt-24 md:pb-28">
                <div class="mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
                    <!-- Pill Badge -->
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-sky-200/80 bg-sky-50/90 px-4 py-1.5 text-xs font-semibold text-sky-700 shadow-2xs backdrop-blur-xs dark:border-sky-800/60 dark:bg-sky-950/50 dark:text-sky-300">
                        <Sparkles class="size-3.5" />
                        <span>AI Meeting Intelligence</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-5xl font-extrabold tracking-tight text-zinc-900 sm:text-6xl md:text-7xl leading-[1.08] dark:text-zinc-50">
                        Turn Meetings Into<br />
                        <span class="bg-gradient-to-r from-sky-600 via-sky-500 to-amber-500 bg-clip-text text-transparent">
                            Actionable Intelligence.
                        </span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="mx-auto mt-6 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg dark:text-zinc-400">
                        Fathom transcribes multi-speaker meetings with sub-second video sync, synthesizes tailored executive briefs, and lets you query your calls with timestamp video citations.
                    </p>

                    <!-- Dual CTAs -->
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                        <Link
                            href="/meetings"
                            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500 px-6 py-3.5 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:shadow-lg hover:shadow-sky-500/20 active:scale-95 cursor-pointer"
                        >
                            <span>Try the Demo</span>
                            <ArrowRight class="size-4" />
                        </Link>

                        <a
                            href="#how-it-works"
                            class="inline-flex items-center gap-2 rounded-full border border-zinc-200/90 bg-white px-6 py-3.5 text-sm font-semibold text-zinc-800 shadow-2xs transition-all duration-200 hover:bg-zinc-50 active:scale-95 cursor-pointer dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            <Sparkles class="size-4 text-amber-500" />
                            <span>How it works</span>
                        </a>
                    </div>
                </div>

                <!-- Product Preview Mockup Window -->
                <div class="relative mx-auto mt-14 max-w-6xl px-4 sm:px-6 lg:px-8">
                    <!-- Overlapping Floating Satellite Badges -->
                    <div class="pointer-events-none absolute -top-5 left-10 z-20 hidden items-center gap-2 rounded-2xl border border-sky-200/80 bg-white/95 px-3.5 py-2 text-xs font-semibold text-sky-700 shadow-xl backdrop-blur-md sm:flex dark:border-sky-800 dark:bg-zinc-900/95 dark:text-sky-300">
                        <Zap class="size-4 text-sky-500" />
                        <span>Sub-50ms Video Scrubber</span>
                    </div>

                    <div class="pointer-events-none absolute -bottom-5 right-10 z-20 hidden items-center gap-2 rounded-2xl border border-amber-200/80 bg-white/95 px-3.5 py-2 text-xs font-semibold text-amber-700 shadow-xl backdrop-blur-md sm:flex dark:border-amber-800 dark:bg-zinc-900/95 dark:text-amber-300">
                        <CheckCircle2 class="size-4 text-amber-500" />
                        <span>Clickable Timestamp Citations</span>
                    </div>

                    <!-- Main App Window Frame -->
                    <div class="overflow-hidden rounded-3xl border border-zinc-200/90 bg-white shadow-2xl shadow-slate-300/40 dark:border-zinc-800 dark:bg-zinc-900 dark:shadow-zinc-950/60">
                        <!-- Mockup Top Bar -->
                        <div class="flex items-center justify-between border-b border-zinc-100 bg-zinc-50/70 px-5 py-3.5 dark:border-zinc-800 dark:bg-zinc-900/80">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-rose-400" />
                                <span class="size-3 rounded-full bg-amber-400" />
                                <span class="size-3 rounded-full bg-emerald-400" />
                                <span class="ml-3 font-mono text-xs font-semibold text-zinc-500">fathom.test/meetings/1</span>
                            </div>

                            <div class="hidden items-center gap-2 sm:flex">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 border border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300">
                                    <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse" />
                                    Live Sync
                                </span>
                            </div>
                        </div>

                        <!-- Mockup Interior (3-Panel Preview) -->
                        <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-12">
                            <!-- Left: Video Showcase + Summary Preview -->
                            <div class="flex flex-col gap-6 lg:col-span-7">
                                <!-- Dark Video Container -->
                                <div class="relative flex aspect-video w-full items-center justify-center overflow-hidden rounded-3xl bg-zinc-950 p-6 shadow-md">
                                    <div class="pointer-events-none absolute inset-0 bg-radial from-sky-500/10 via-transparent to-transparent opacity-50" />
                                    <div class="flex flex-col items-center gap-3 text-center">
                                        <div class="flex size-14 items-center justify-center rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white shadow-xl transition-transform hover:scale-105 cursor-pointer">
                                            <Play class="size-6 fill-current pl-1" />
                                        </div>
                                        <p class="font-mono text-xs font-semibold text-zinc-300">
                                            Product & Engineering Sync (02:05)
                                        </p>
                                    </div>
                                </div>

                                <!-- Summary Card Preview -->
                                <div class="rounded-3xl border border-zinc-200/80 bg-zinc-50/60 p-5 dark:border-zinc-800 dark:bg-zinc-800/40">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                                            <Sparkles class="size-3.5 text-sky-500" />
                                            Executive Intelligence
                                        </span>
                                        <div class="flex gap-1">
                                            <span class="rounded-lg bg-white px-2 py-0.5 text-[10px] font-semibold text-zinc-800 shadow-2xs border border-zinc-200/60 dark:bg-zinc-700 dark:text-zinc-200">
                                                General
                                            </span>
                                            <span class="rounded-lg px-2 py-0.5 text-[10px] font-medium text-zinc-400">
                                                Sales
                                            </span>
                                            <span class="rounded-lg px-2 py-0.5 text-[10px] font-medium text-zinc-400">
                                                Engineering
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-zinc-600 leading-relaxed dark:text-zinc-400">
                                        • Standardized on headless Reka UI primitives for accessible dialogues and modals.<br />
                                        • Leveraged Inertia v3 deferred props for sub-50ms initial paints on large transcripts.
                                    </p>
                                </div>
                            </div>

                            <!-- Right: Synced Transcript Column -->
                            <div class="lg:col-span-5">
                                <div class="h-full rounded-3xl border border-zinc-200/80 bg-white p-4 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                                    <div class="mb-3 flex items-center justify-between border-b border-zinc-100 pb-2.5 dark:border-zinc-800">
                                        <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Transcript Cues</span>
                                        <span class="text-[10px] text-zinc-400">Click cue to jump</span>
                                    </div>
                                    <div class="space-y-2.5">
                                        <div class="rounded-2xl border-l-4 border-l-sky-500 bg-sky-50/80 p-3 shadow-2xs dark:bg-sky-950/40">
                                            <div class="flex items-center justify-between text-xs mb-1">
                                                <span class="rounded-full bg-sky-100 px-2 py-0.5 text-[10px] font-semibold text-sky-700">Alex Chen</span>
                                                <span class="font-mono text-[10px] text-sky-600 font-semibold">00:45</span>
                                            </div>
                                            <p class="text-xs text-zinc-800 font-medium leading-relaxed dark:text-zinc-200">
                                                "We decided to lock in the final architecture for our meeting intelligence workspace."
                                            </p>
                                        </div>
                                        <div class="rounded-2xl p-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                            <div class="flex items-center justify-between text-xs mb-1">
                                                <span class="rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-semibold text-purple-700">Maya Patel</span>
                                                <span class="font-mono text-[10px] text-zinc-400">01:12</span>
                                            </div>
                                            <p class="text-xs text-zinc-600 leading-relaxed dark:text-zinc-400">
                                                "From the frontend side, keyboard navigation and accessibility are buttery smooth now."
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Features Bento Grid Section -->
            <section id="features" class="border-t border-zinc-100 bg-zinc-50/50 py-20 md:py-28 dark:border-zinc-800/80 dark:bg-zinc-900/40">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <!-- Section Header -->
                    <div class="mx-auto max-w-2xl text-center">
                        <div class="mb-3 inline-flex items-center gap-1.5 rounded-full border border-sky-200/70 bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 shadow-2xs dark:border-sky-800/60 dark:bg-sky-950/40 dark:text-sky-300">
                            Features
                        </div>
                        <h2 class="text-3xl font-extrabold tracking-tight text-zinc-900 sm:text-4xl dark:text-zinc-100">
                            Engineered for High-Velocity Teams
                        </h2>
                        <p class="mt-3 text-sm text-zinc-600 leading-relaxed dark:text-zinc-400">
                            Extract high-signal decisions from sales calls, engineering post-mortems, and product reviews.
                        </p>
                    </div>

                    <!-- 3-Column Bento Grid -->
                    <div class="mt-14 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="feature in features"
                            :key="feature.title"
                            class="group relative flex flex-col justify-between rounded-3xl border border-zinc-200/80 bg-white p-8 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-zinc-200/50 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:shadow-zinc-950/60"
                        >
                            <div>
                                <!-- Icon Pill Badge -->
                                <div class="mb-5 inline-flex size-11 items-center justify-center rounded-2xl border shadow-2xs" :class="feature.badgeClass">
                                    <component :is="feature.icon" class="size-5" :class="feature.iconClass" />
                                </div>

                                <h3 class="text-lg font-bold tracking-tight text-zinc-900 transition-colors duration-200 group-hover:text-sky-600 dark:text-zinc-100 dark:group-hover:text-sky-400">
                                    {{ feature.title }}
                                </h3>

                                <p class="mt-2.5 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                                    {{ feature.description }}
                                </p>
                            </div>

                            <div class="mt-6 flex items-center gap-1 text-xs font-semibold text-sky-600 opacity-0 transition-all duration-200 group-hover:opacity-100 dark:text-sky-400">
                                <span>Learn more</span>
                                <ArrowRight class="size-3.5 transition-transform duration-200 group-hover:translate-x-0.5" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. How It Works Section -->
            <section id="how-it-works" class="py-20 md:py-28">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <!-- Section Header -->
                    <div class="mx-auto max-w-2xl text-center">
                        <div class="mb-3 inline-flex items-center gap-1.5 rounded-full border border-amber-200/70 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 shadow-2xs dark:border-amber-800/60 dark:bg-amber-950/40 dark:text-amber-300">
                            How it works
                        </div>
                        <h2 class="text-3xl font-extrabold tracking-tight text-zinc-900 sm:text-4xl dark:text-zinc-100">
                            Three Steps to Meeting Clarity
                        </h2>
                        <p class="mt-3 text-sm text-zinc-600 leading-relaxed dark:text-zinc-400">
                            From recorded video to searchable decisions in under 5 minutes.
                        </p>
                    </div>

                    <!-- Step Cards Grid -->
                    <div class="mt-14 grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div
                            v-for="(step, idx) in steps"
                            :key="step.step"
                            class="relative flex flex-col justify-between rounded-3xl border border-zinc-200/80 bg-white p-8 shadow-xs transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-zinc-200/50 dark:border-zinc-800 dark:bg-zinc-900"
                        >
                            <div>
                                <div class="mb-5 flex items-center justify-between">
                                    <span
                                        class="inline-flex rounded-full border px-3 py-1 font-mono text-xs font-semibold shadow-2xs"
                                        :class="step.pillClass"
                                    >
                                        {{ step.step }}
                                    </span>
                                    <span class="font-mono text-xs font-bold text-zinc-300 dark:text-zinc-700">
                                        0{{ idx + 1 }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold tracking-tight text-zinc-900 dark:text-zinc-100">
                                    {{ step.title }}
                                </h3>

                                <p class="mt-2.5 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                                    {{ step.description }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center gap-1.5 text-xs font-medium text-zinc-400">
                                <CheckCircle2 class="size-3.5 text-emerald-500" />
                                <span>Verified & Instant</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5. Works with Your Stack Section -->
            <section class="border-t border-zinc-100 bg-zinc-50/50 py-16 dark:border-zinc-800/80 dark:bg-zinc-900/30">
                <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                        Built with modern open architecture
                    </p>

                    <!-- Tech Badges Row -->
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                        <div
                            v-for="item in techStack"
                            :key="item.name"
                            class="inline-flex items-center gap-2 rounded-2xl border border-zinc-200/80 bg-white px-4 py-2 shadow-2xs transition-all duration-200 hover:border-sky-300 dark:border-zinc-800 dark:bg-zinc-900"
                        >
                            <span class="size-2 rounded-full bg-gradient-to-r from-sky-500 to-amber-500" />
                            <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">{{ item.name }}</span>
                            <span class="text-[10px] text-zinc-400">({{ item.role }})</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-zinc-200/80 bg-white py-12 dark:border-zinc-800 dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                    <!-- Brand -->
                    <div class="flex items-center gap-2.5">
                        <div class="flex size-8 items-center justify-center rounded-xl bg-gradient-to-tr from-sky-500 via-sky-600 to-amber-500 text-white shadow-xs">
                            <Sparkles class="size-4" />
                        </div>
                        <span class="text-lg font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Fathom</span>
                        <span class="ml-2 text-xs text-zinc-400">
                            AI Meeting Intelligence Platform
                        </span>
                    </div>

                    <!-- Links -->
                    <div class="flex flex-wrap items-center gap-6 text-xs text-zinc-500 dark:text-zinc-400">
                        <Link href="/meetings" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">
                            Meetings
                        </Link>
                        <Link :href="dashboard()" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">
                            Dashboard
                        </Link>
                        <a href="https://github.com/laravel/vue-starter-kit" target="_blank" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">
                            GitHub
                        </a>
                    </div>
                </div>

                <div class="mt-8 border-t border-zinc-100 pt-6 text-center text-xs text-zinc-400 dark:border-zinc-850 dark:text-zinc-500">
                    &copy; {{ new Date().getFullYear() }} Fathom. Built with Laravel 13, Vue 3.5, and Inertia v3.
                </div>
            </div>
        </footer>
    </div>
</template>
