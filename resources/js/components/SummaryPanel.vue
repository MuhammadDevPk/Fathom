<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import {
    AlertCircle,
    CalendarCheck,
    CheckCircle2,
    Code2,
    FileText,
    Layers,
    ListTodo,
    RefreshCw,
    Sparkles,
    TrendingUp,
} from '@lucide/vue';
import {
    TabsContent,
    TabsList,
    TabsRoot,
    TabsTrigger,
} from 'reka-ui';
import { computed, ref, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        summary: string | null;
        activeTemplate?: string;
        meetingId?: number;
    }>(),
    {
        activeTemplate: 'general',
        meetingId: undefined,
    },
);

const selectedTemplate = ref(props.activeTemplate || 'general');
const isReloading = ref(false);

watch(
    () => props.activeTemplate,
    (newVal) => {
        if (newVal && newVal !== selectedTemplate.value) {
            selectedTemplate.value = newVal;
        }
    },
);

function switchTemplate(template: string) {
    if (selectedTemplate.value === template && !isReloading.value && props.summary) {
        return;
    }

    selectedTemplate.value = template;
    isReloading.value = true;

    // Partial reload of deferred summary prop per .agent/architecture.md
    router.reload({
        only: ['summary', 'active_template'],
        data: { template },
        onFinish: () => {
            isReloading.value = false;
        },
    });
}

function handleTabChange(value: string | number) {
    switchTemplate(String(value));
}

function regenerateSummary() {
    if (!props.meetingId || isReloading.value) {
        return;
    }

    isReloading.value = true;
    router.post(
        `/meetings/${props.meetingId}/summary`,
        { template: selectedTemplate.value },
        {
            preserveScroll: true,
            onFinish: () => {
                // Trigger partial reload to refresh summary prop once job is queued
                router.reload({
                    only: ['summary'],
                    data: { template: selectedTemplate.value },
                    onFinish: () => {
                        isReloading.value = false;
                    },
                });
            },
        },
    );
}

interface SummarySection {
    title: string;
    level: number;
    paragraphs: string[];
    bullets: string[];
}

function parseMarkdownSummary(markdown: string | null): SummarySection[] {
    if (!markdown || !markdown.trim()) {
        return [];
    }

    const lines = markdown.split('\n');
    const sections: SummarySection[] = [];
    let currentSection: SummarySection = {
        title: 'Executive Overview',
        level: 2,
        paragraphs: [],
        bullets: [],
    };

    for (const rawLine of lines) {
        const line = rawLine.trim();
        if (!line) {
            continue;
        }

        if (line.startsWith('#')) {
            const match = line.match(/^(#+)\s+(.+)$/);
            if (match) {
                if (currentSection.paragraphs.length > 0 || currentSection.bullets.length > 0) {
                    sections.push(currentSection);
                }
                currentSection = {
                    title: match[2].trim(),
                    level: match[1].length,
                    paragraphs: [],
                    bullets: [],
                };
                continue;
            }
        }

        if (line.startsWith('- ') || line.startsWith('* ')) {
            currentSection.bullets.push(line.slice(2).trim());
        } else {
            currentSection.paragraphs.push(line);
        }
    }

    if (currentSection.paragraphs.length > 0 || currentSection.bullets.length > 0) {
        sections.push(currentSection);
    }

    return sections;
}

const parsedSections = computed(() => parseMarkdownSummary(props.summary));

function getSectionIcon(title: string) {
    const lower = title.toLowerCase();
    if (lower.includes('decision') || lower.includes('tradeoff')) {
        return CheckCircle2;
    }
    if (lower.includes('action') || lower.includes('step') || lower.includes('task')) {
        return ListTodo;
    }
    if (lower.includes('pain') || lower.includes('risk') || lower.includes('blocker') || lower.includes('objection')) {
        return AlertCircle;
    }
    if (lower.includes('sale') || lower.includes('commercial') || lower.includes('deal') || lower.includes('pricing')) {
        return TrendingUp;
    }
    if (lower.includes('tech') || lower.includes('arch') || lower.includes('engineer')) {
        return Code2;
    }
    return Sparkles;
}

function getSectionIconBg(title: string): string {
    const lower = title.toLowerCase();
    if (lower.includes('decision') || lower.includes('tradeoff')) {
        return 'bg-emerald-50 text-emerald-600 border border-emerald-200/70 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800/60';
    }
    if (lower.includes('action') || lower.includes('step') || lower.includes('task')) {
        return 'bg-amber-50 text-amber-600 border border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800/60';
    }
    if (lower.includes('pain') || lower.includes('risk') || lower.includes('blocker') || lower.includes('objection')) {
        return 'bg-rose-50 text-rose-600 border border-rose-200/70 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-800/60';
    }
    if (lower.includes('sale') || lower.includes('commercial')) {
        return 'bg-purple-50 text-purple-600 border border-purple-200/70 dark:bg-purple-950/40 dark:text-purple-400 dark:border-purple-800/60';
    }
    return 'bg-sky-50 text-sky-600 border border-sky-200/70 dark:bg-sky-950/40 dark:text-sky-400 dark:border-sky-800/60';
}
</script>

<template>
    <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200/80 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <!-- Reka UI Tabs Header & Template Switcher -->
        <TabsRoot
            :model-value="selectedTemplate"
            class="flex h-full flex-col"
            @update:model-value="handleTabChange"
        >
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800">
                <div class="flex items-center gap-2.5">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-gradient-to-tr from-sky-500 to-amber-500 text-white shadow-xs">
                        <Sparkles class="size-4" />
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                            Executive Intelligence
                        </h3>
                    </div>
                </div>

                <!-- Template Switcher Pills -->
                <div class="flex items-center gap-2">
                    <TabsList class="inline-flex rounded-xl bg-zinc-100/80 p-1 dark:bg-zinc-800">
                        <TabsTrigger
                            value="general"
                            :disabled="isReloading"
                            class="flex items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-medium text-zinc-600 transition-all cursor-pointer data-[state=active]:bg-white data-[state=active]:text-zinc-900 data-[state=active]:shadow-xs disabled:opacity-60 dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-zinc-100"
                        >
                            <span>General</span>
                        </TabsTrigger>
                        <TabsTrigger
                            value="sales"
                            :disabled="isReloading"
                            class="flex items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-medium text-zinc-600 transition-all cursor-pointer data-[state=active]:bg-white data-[state=active]:text-zinc-900 data-[state=active]:shadow-xs disabled:opacity-60 dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-zinc-100"
                        >
                            <span>Sales</span>
                        </TabsTrigger>
                        <TabsTrigger
                            value="engineering"
                            :disabled="isReloading"
                            class="flex items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-medium text-zinc-600 transition-all cursor-pointer data-[state=active]:bg-white data-[state=active]:text-zinc-900 data-[state=active]:shadow-xs disabled:opacity-60 dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-zinc-100"
                        >
                            <span>Engineering</span>
                        </TabsTrigger>
                    </TabsList>

                    <!-- Regenerate / Re-run Action -->
                    <button
                        v-if="meetingId"
                        type="button"
                        :disabled="isReloading"
                        class="inline-flex size-7 items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 transition-colors hover:bg-zinc-50 hover:text-zinc-800 disabled:opacity-50 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                        title="Regenerate summary with AI"
                        @click="regenerateSummary"
                    >
                        <RefreshCw :class="['size-3.5', isReloading ? 'animate-spin text-sky-600' : '']" />
                    </button>
                </div>
            </div>

            <!-- Tab Content Viewport -->
            <div class="relative flex-1 overflow-y-auto pr-1">
                <!-- Loading Skeleton (matches .agent/ui_reference.md light mode design) -->
                <div
                    v-if="isReloading || summary === null"
                    class="space-y-4 py-2"
                >
                    <div class="flex items-center gap-2 text-xs font-medium text-sky-600 dark:text-sky-400">
                        <Sparkles class="size-3.5 animate-spin" />
                        <span>Synthesizing {{ selectedTemplate }} intelligence...</span>
                    </div>

                    <!-- Skeleton Card 1 -->
                    <div class="rounded-xl border border-zinc-200/80 bg-zinc-50/70 p-4 space-y-3 dark:border-zinc-800 dark:bg-zinc-800/40">
                        <div class="flex items-center gap-2">
                            <div class="size-6 rounded-lg bg-zinc-200/80 animate-pulse dark:bg-zinc-700" />
                            <div class="h-4 w-40 rounded-md bg-zinc-200/80 animate-pulse dark:bg-zinc-700" />
                        </div>
                        <div class="space-y-2 pt-1">
                            <div class="h-3.5 w-full rounded bg-zinc-200/60 animate-pulse dark:bg-zinc-700/60" />
                            <div class="h-3.5 w-11/12 rounded bg-zinc-200/60 animate-pulse dark:bg-zinc-700/60" />
                            <div class="h-3.5 w-4/5 rounded bg-zinc-200/60 animate-pulse dark:bg-zinc-700/60" />
                        </div>
                    </div>

                    <!-- Skeleton Card 2 -->
                    <div class="rounded-xl border border-zinc-200/80 bg-zinc-50/70 p-4 space-y-3 dark:border-zinc-800 dark:bg-zinc-800/40">
                        <div class="flex items-center gap-2">
                            <div class="size-6 rounded-lg bg-zinc-200/80 animate-pulse dark:bg-zinc-700" />
                            <div class="h-4 w-32 rounded-md bg-zinc-200/80 animate-pulse dark:bg-zinc-700" />
                        </div>
                        <div class="space-y-2 pt-1">
                            <div class="h-3 w-5/6 rounded bg-zinc-200/60 animate-pulse dark:bg-zinc-700/60" />
                            <div class="h-3 w-3/4 rounded bg-zinc-200/60 animate-pulse dark:bg-zinc-700/60" />
                        </div>
                    </div>
                </div>

                <!-- Polished Card-Based Summary Layout -->
                <div v-else-if="parsedSections.length > 0" class="space-y-4 pb-2">
                    <div
                        v-for="(section, sIndex) in parsedSections"
                        :key="sIndex"
                        class="rounded-xl border border-zinc-200/80 bg-zinc-50/50 p-4 transition-all hover:bg-zinc-50 hover:shadow-2xs dark:border-zinc-800 dark:bg-zinc-800/30 dark:hover:bg-zinc-800/50"
                    >
                        <!-- Section Header -->
                        <div class="mb-3 flex items-center gap-2.5">
                            <div
                                :class="[
                                    'flex size-6 shrink-0 items-center justify-center rounded-md',
                                    getSectionIconBg(section.title),
                                ]"
                            >
                                <component :is="getSectionIcon(section.title)" class="size-3.5" />
                            </div>
                            <h4 class="text-sm font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                                {{ section.title }}
                            </h4>
                        </div>

                        <!-- Paragraphs -->
                        <div
                            v-if="section.paragraphs.length > 0"
                            class="space-y-2 text-xs leading-relaxed text-zinc-600 dark:text-zinc-300"
                        >
                            <p
                                v-for="(para, pIndex) in section.paragraphs"
                                :key="pIndex"
                                class="whitespace-pre-line"
                            >
                                {{ para }}
                            </p>
                        </div>

                        <!-- Structured Bullets (Decision items, Action items, etc.) -->
                        <div
                            v-if="section.bullets.length > 0"
                            :class="[
                                'space-y-2',
                                section.paragraphs.length > 0 ? 'mt-3 border-t border-zinc-200/60 pt-3 dark:border-zinc-700/60' : '',
                            ]"
                        >
                            <div
                                v-for="(bullet, bIndex) in section.bullets"
                                :key="bIndex"
                                class="flex items-start gap-2.5 text-xs text-zinc-700 dark:text-zinc-300"
                            >
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-sky-500 dark:bg-sky-400" />
                                <span class="leading-relaxed">
                                    {{ bullet }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="flex h-40 flex-col items-center justify-center gap-2 text-center text-zinc-400"
                >
                    <FileText class="size-7 text-zinc-300 dark:text-zinc-600" />
                    <p class="text-sm font-medium">No executive summary available</p>
                    <p class="text-xs text-zinc-400">Select a template above to generate summary intelligence</p>
                </div>
            </div>
        </TabsRoot>
    </div>
</template>
