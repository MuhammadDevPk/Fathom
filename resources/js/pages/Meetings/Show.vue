<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Bookmark,
    Bot,
    Calendar,
    Clock,
    ListTodo,
    Sparkles,
    Video,
} from '@lucide/vue';
import {
    TabsContent,
    TabsList,
    TabsRoot,
    TabsTrigger,
} from 'reka-ui';
import { computed, ref } from 'vue';
import ActionItemsList from '@/components/ActionItemsList.vue';
import AskAiPanel from '@/components/AskAiPanel.vue';
import HighlightsList from '@/components/HighlightsList.vue';
import SummaryPanel from '@/components/SummaryPanel.vue';
import TranscriptList from '@/components/TranscriptList.vue';
import VideoPlayer from '@/components/VideoPlayer.vue';
import { useTranscriptSync } from '@/composables/useTranscriptSync';
import type { ActionItem, HighlightItem, MeetingDetail, QaItem, TranscriptCue } from '@/types';

const props = withDefaults(
    defineProps<{
        meeting: MeetingDetail;
        transcript: TranscriptCue[];
        summary: string | null;
        active_template?: string;
        highlights?: HighlightItem[];
        action_items?: ActionItem[];
        qa_history?: QaItem[];
    }>(),
    {
        active_template: 'general',
        highlights: () => [],
        action_items: () => [],
        qa_history: () => [],
    },
);

const videoPlayerRef = ref<InstanceType<typeof VideoPlayer> | null>(null);

const { activeCueIndex, seekToCue } = useTranscriptSync(
    computed(() => videoPlayerRef.value?.videoElement ?? null),
    () => props.transcript,
);

function seekToTimestamp(seconds: number) {
    const list = props.transcript || [];
    const cue = list.find((c) => seconds >= c.start && seconds <= c.end) || {
        start: seconds,
        end: seconds + 3,
        speaker: 'Meeting',
        text: '',
    };
    seekToCue(cue);
}

const activePanelTab = ref('summary');

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Meetings',
                href: '/meetings',
            },
            {
                title: 'Meeting Intelligence',
                href: '#',
            },
        ],
    },
});

const formattedDuration = computed(() => {
    const totalSeconds = props.meeting.duration_seconds;
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    if (minutes === 0) {
        return `${seconds}s`;
    }
    return `${minutes}m ${seconds.toString().padStart(2, '0')}s`;
});

const formattedDate = computed(() => {
    if (!props.meeting.created_at) {
        return '';
    }
    const d = new Date(props.meeting.created_at);
    return d.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
});
</script>

<template>
    <Head :title="`${meeting.title} - Fathom`" />

    <div class="relative flex flex-1 flex-col gap-8 p-6 md:p-10 bg-gradient-to-b from-sky-50/30 via-transparent to-transparent">
        <!-- Top Nav & Meeting Title Header -->
        <div class="flex flex-col gap-4 border-b border-zinc-200/80 pb-6 dark:border-zinc-800">
            <div class="mb-1">
                <Link
                    href="/meetings"
                    class="inline-flex items-center gap-1.5 text-[11px] font-medium text-zinc-400 transition-colors duration-200 hover:text-sky-600 dark:text-zinc-500 dark:hover:text-sky-400"
                >
                    <ArrowLeft class="size-3" />
                    Back to all meetings
                </Link>
            </div>

            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 md:text-4xl leading-tight dark:text-zinc-100">
                    {{ meeting.title }}
                </h1>

                <!-- Meta Pills -->
                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-sky-50/90 px-3.5 py-1 font-mono text-xs font-bold text-sky-700 dark:bg-sky-950/60 dark:text-sky-300"
                    >
                        <Clock class="size-3.5 text-sky-600 dark:text-sky-400" />
                        {{ formattedDuration }}
                    </span>

                    <span
                        v-if="formattedDate"
                        class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100/90 px-3.5 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"
                    >
                        <Calendar class="size-3.5 text-zinc-400" />
                        {{ formattedDate }}
                    </span>

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50/90 px-3.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300"
                    >
                        <Video class="size-3.5 text-emerald-600 dark:text-emerald-400" />
                        Synced Media
                    </span>
                </div>
            </div>
        </div>

        <!-- Three-Panel Layout:
             Left Column: Video (Top Left) + Intelligence Tabs (Bottom Left, below video)
             Right Column: Transcript & Highlights Side Panel (Right)
        -->
        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
            <!-- Left Column: Video + Intelligence Tabs -->
            <div class="flex flex-col gap-8 lg:col-span-7">
                <!-- Video Player (Top Left) -->
                <div>
                    <VideoPlayer ref="videoPlayerRef" :src="meeting.video_url" />
                </div>

                <!-- Intelligence Tabs (Bottom Left, below video) -->
                <div class="min-h-[380px]">
                    <TabsRoot v-model="activePanelTab" class="flex flex-col">
                        <!-- Top Navigation Tabs -->
                        <div class="mb-4 flex items-center justify-between border-b border-zinc-200/80 pb-3 dark:border-zinc-800">
                            <TabsList class="inline-flex rounded-full bg-zinc-100/80 p-1 dark:bg-zinc-800/80">
                                <TabsTrigger
                                    value="summary"
                                    class="flex items-center gap-2 rounded-full px-3.5 py-1.5 text-xs font-semibold text-zinc-500 transition-all duration-200 cursor-pointer data-[state=active]:bg-white data-[state=active]:text-sky-700 data-[state=active]:shadow-xs dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-sky-300"
                                >
                                    <Sparkles class="size-3.5 text-sky-500" />
                                    <span>Summary</span>
                                </TabsTrigger>

                                <TabsTrigger
                                    value="action-items"
                                    class="flex items-center gap-2 rounded-full px-3.5 py-1.5 text-xs font-semibold text-zinc-500 transition-all duration-200 cursor-pointer data-[state=active]:bg-white data-[state=active]:text-amber-700 data-[state=active]:shadow-xs dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-amber-300"
                                >
                                    <ListTodo class="size-3.5 text-amber-500" />
                                    <span>Action Items</span>
                                    <span class="rounded-full bg-amber-100/80 px-1.5 py-0.2 text-[10px] text-amber-700 dark:bg-amber-950 dark:text-amber-300">
                                        {{ action_items.length }}
                                    </span>
                                </TabsTrigger>

                                <TabsTrigger
                                    value="highlights"
                                    class="flex items-center gap-2 rounded-full px-3.5 py-1.5 text-xs font-semibold text-zinc-500 transition-all duration-200 cursor-pointer data-[state=active]:bg-white data-[state=active]:text-indigo-700 data-[state=active]:shadow-xs dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-indigo-300"
                                >
                                    <Bookmark class="size-3.5 text-indigo-500" />
                                    <span>Highlights</span>
                                    <span class="rounded-full bg-indigo-100/80 px-1.5 py-0.2 text-[10px] text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">
                                        {{ highlights.length }}
                                    </span>
                                </TabsTrigger>

                                <TabsTrigger
                                    value="ask-ai"
                                    class="flex items-center gap-2 rounded-full px-3.5 py-1.5 text-xs font-semibold text-zinc-500 transition-all duration-200 cursor-pointer data-[state=active]:bg-white data-[state=active]:text-sky-700 data-[state=active]:shadow-xs dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-sky-300"
                                >
                                    <Bot class="size-3.5 text-sky-500" />
                                    <span>Ask AI</span>
                                    <span
                                        v-if="qa_history.length > 0"
                                        class="rounded-full bg-sky-100/80 px-1.5 py-0.2 text-[10px] text-sky-700 dark:bg-sky-950 dark:text-sky-300"
                                    >
                                        {{ qa_history.length }}
                                    </span>
                                </TabsTrigger>
                            </TabsList>
                        </div>

                        <!-- Tab 1: Executive Summary -->
                        <TabsContent value="summary" class="focus:outline-none transition-all duration-200">
                            <SummaryPanel
                                :summary="summary"
                                :active-template="active_template"
                                :meeting-id="meeting.id"
                            />
                        </TabsContent>

                        <!-- Tab 2: Action Items -->
                        <TabsContent value="action-items" class="focus:outline-none transition-all duration-200">
                            <div class="rounded-3xl border border-zinc-200/80 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                                <ActionItemsList :action-items="action_items" />
                            </div>
                        </TabsContent>

                        <!-- Tab 3: Highlights -->
                        <TabsContent value="highlights" class="focus:outline-none transition-all duration-200">
                            <div class="rounded-3xl border border-zinc-200/80 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                                <HighlightsList
                                    :highlights="highlights"
                                    @seek="seekToTimestamp"
                                />
                            </div>
                        </TabsContent>

                        <!-- Tab 4: Ask AI Assistant -->
                        <TabsContent value="ask-ai" class="focus:outline-none transition-all duration-200">
                            <AskAiPanel
                                :meeting-id="meeting.id"
                                :qa-history="qa_history"
                                @seek="seekToTimestamp"
                            />
                        </TabsContent>
                    </TabsRoot>
                </div>
            </div>

            <!-- Right Column: Transcript & Highlights Side Panel -->
            <div class="h-[600px] lg:col-span-5 lg:h-[calc(100vh-13rem)] lg:min-h-[640px]">
                <TranscriptList
                    :cues="transcript"
                    :active-cue-index="activeCueIndex"
                    :meeting-id="meeting.id"
                    :meeting-duration="meeting.duration_seconds"
                    :highlights="highlights"
                    @select-cue="seekToCue"
                    @seek="seekToTimestamp"
                />
            </div>
        </div>
    </div>
</template>
