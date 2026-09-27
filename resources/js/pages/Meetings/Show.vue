<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    ArrowLeft,
    ArrowRight,
    Bookmark,
    Calendar,
    Clock,
    ListTodo,
    Loader2,
    Share2,
    Sparkles,
    Trash2,
    Video,
} from '@lucide/vue';
import {
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogOverlay,
    AlertDialogPortal,
    AlertDialogRoot,
    AlertDialogTitle,
    AlertDialogTrigger,
    TabsContent,
    TabsList,
    TabsRoot,
    TabsTrigger,
} from 'reka-ui';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import ActionItemsList from '@/components/ActionItemsList.vue';
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
        action_item_state?: Record<string, boolean>;
        share_url?: string;
        isDemo?: boolean;
    }>(),
    {
        active_template: 'general',
        highlights: () => [],
        action_items: () => [],
        qa_history: () => [],
        action_item_state: () => ({}),
        share_url: undefined,
        isDemo: false,
    },
);

const page = usePage();
const user = computed(() => page.props.auth?.user);
const isGuest = computed(() => Boolean(props.isDemo) && !user.value);

const isDeleteDialogOpen = ref(false);
const isDeleting = ref(false);

function confirmDelete() {
    isDeleting.value = true;
    router.delete(`/meetings/${props.meeting.id}`, {
        onFinish: () => {
            isDeleting.value = false;
            isDeleteDialogOpen.value = false;
        },
    });
}

async function copyShareLink() {
    if (!props.share_url) {
        return;
    }
    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(props.share_url);
        } else {
            const textArea = document.createElement('textarea');
            textArea.value = props.share_url;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            document.execCommand('copy');
            textArea.remove();
        }
        toast.success('Link copied');
    } catch {
        toast.error('Failed to copy link');
    }
}

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

    <div :class="['relative flex flex-1 flex-col overflow-hidden p-4 md:p-5 lg:p-6 bg-gradient-to-b from-sky-50/30 via-transparent to-transparent', isGuest ? 'h-screen max-h-screen' : 'h-[calc(100vh-4rem)] max-h-[calc(100vh-4rem)]']">
        <!-- Demo Banner -->
        <div
            v-if="isGuest"
            class="mb-3 flex shrink-0 items-center justify-between gap-3 rounded-2xl border border-sky-200/90 bg-gradient-to-r from-sky-50 via-sky-50/70 to-amber-50/70 px-4 py-2 shadow-2xs dark:border-sky-800/80 dark:bg-zinc-900"
        >
            <div class="flex items-center gap-2 min-w-0">
                <span class="flex size-6 shrink-0 items-center justify-center rounded-lg bg-sky-500 text-white shadow-xs">
                    <Sparkles class="size-3.5" />
                </span>
                <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100">
                    Demo
                </span>
                <span class="hidden text-xs text-zinc-600 sm:inline truncate dark:text-zinc-400">
                    — Demo — sign up to save your own meetings, bookmark highlights, and query with AI.
                </span>
            </div>

            <a
                href="/register"
                target="_top"
                class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500 px-3.5 py-1 text-xs font-semibold text-white shadow-xs transition-all hover:opacity-95"
            >
                <span>Sign up</span>
                <ArrowRight class="size-3" />
            </a>
        </div>

        <!-- Top Nav & Meeting Title Header -->
        <div class="flex shrink-0 flex-col gap-2 border-b border-zinc-200/80 pb-3 dark:border-zinc-800">
            <div v-if="!isDemo || user">
                <Link
                    href="/meetings"
                    class="inline-flex items-center gap-1.5 text-[11px] font-medium text-zinc-400 transition-colors duration-200 hover:text-sky-600 dark:text-zinc-500 dark:hover:text-sky-400"
                >
                    <ArrowLeft class="size-3" />
                    Back to all meetings
                </Link>
            </div>

            <div class="flex flex-col justify-between gap-3 md:flex-row md:items-center">
                <h1 class="text-xl md:text-2xl lg:text-3xl font-extrabold tracking-tight text-zinc-900 leading-snug truncate dark:text-zinc-100">
                    {{ meeting.title }}
                </h1>

                <!-- Meta Pills -->
                <div class="flex shrink-0 flex-wrap items-center gap-2">
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

                    <!-- Copy share link button (authenticated view only) -->
                    <button
                        v-if="user && share_url"
                        type="button"
                        @click="copyShareLink"
                        class="inline-flex cursor-pointer items-center gap-1.5 rounded-full border border-zinc-200/90 bg-white px-3.5 py-1 text-xs font-semibold text-zinc-700 shadow-2xs transition-all hover:bg-zinc-50 hover:text-zinc-900 active:scale-95 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
                        title="Copy public share link"
                    >
                        <Share2 class="size-3.5 text-sky-600 dark:text-sky-400" />
                        <span>Copy share link</span>
                    </button>

                    <!-- Delete Action (Any authenticated user) -->
                    <AlertDialogRoot v-if="user && !isDemo" v-model:open="isDeleteDialogOpen">
                        <AlertDialogTrigger as-child>
                            <button
                                type="button"
                                class="inline-flex cursor-pointer items-center gap-1.5 rounded-full border border-rose-200/90 bg-rose-50/70 px-3.5 py-1 text-xs font-semibold text-rose-700 shadow-2xs transition-all hover:bg-rose-100 hover:text-rose-900 hover:border-rose-300 active:scale-95 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-400"
                                title="Delete meeting"
                            >
                                <Trash2 class="size-3.5 text-rose-600 dark:text-rose-400" />
                                <span>Delete</span>
                            </button>
                        </AlertDialogTrigger>
                        <AlertDialogPortal>
                            <AlertDialogOverlay class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs transition-opacity" />
                            <AlertDialogContent class="fixed left-1/2 top-1/2 z-50 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-3xl border border-zinc-200/80 bg-white p-6 shadow-2xl dark:border-zinc-800 dark:bg-zinc-900">
                                <AlertDialogTitle class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                    Delete Meeting
                                </AlertDialogTitle>
                                <AlertDialogDescription class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                                    Are you sure you want to permanently delete <strong class="text-zinc-900 dark:text-zinc-200">"{{ meeting.title }}"</strong>? This action cannot be undone.
                                </AlertDialogDescription>
                                <div class="mt-6 flex items-center justify-end gap-3">
                                    <AlertDialogCancel as-child>
                                        <button
                                            type="button"
                                            class="cursor-pointer rounded-full border border-zinc-200 px-4 py-2 text-xs font-semibold text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300"
                                        >
                                            Cancel
                                        </button>
                                    </AlertDialogCancel>
                                    <AlertDialogAction as-child>
                                        <button
                                            type="button"
                                            :disabled="isDeleting"
                                            @click="confirmDelete"
                                            class="inline-flex cursor-pointer items-center gap-1.5 rounded-full bg-rose-600 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-rose-700 disabled:opacity-50"
                                        >
                                            <Loader2 v-if="isDeleting" class="size-3.5 animate-spin" />
                                            <span>Delete permanently</span>
                                        </button>
                                    </AlertDialogAction>
                                </div>
                            </AlertDialogContent>
                        </AlertDialogPortal>
                    </AlertDialogRoot>
                </div>
            </div>
        </div>

        <!-- Three-Panel Layout:
             Left Column: Video (Top Left) + Intelligence Tabs (Bottom Left, below video)
             Right Column: Transcript, Ask AI & Highlights Side Panel (Right)
        -->
        <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-12 gap-5 overflow-hidden pt-1">
            <!-- Left Column: Video + Intelligence Tabs -->
            <div class="flex flex-col h-full min-h-0 gap-3.5 lg:col-span-7 overflow-hidden">
                <!-- Video Player (Top Left) -->
                <div class="shrink-0 flex items-center justify-center">
                    <VideoPlayer ref="videoPlayerRef" :src="meeting.video_url" />
                </div>

                <!-- Intelligence Tabs (Bottom Left, below video) -->
                <div class="flex-1 min-h-0 flex flex-col overflow-hidden">
                    <TabsRoot v-model="activePanelTab" class="flex h-full min-h-0 flex-col overflow-hidden">
                        <!-- Top Navigation Tabs -->
                        <div class="shrink-0 mb-2 flex items-center justify-between border-b border-zinc-200/80 pb-2 dark:border-zinc-800">
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
                            </TabsList>
                        </div>

                        <!-- Tab 1: Executive Summary -->
                        <TabsContent value="summary" class="flex-1 min-h-0 overflow-hidden focus:outline-none transition-all duration-200">
                            <SummaryPanel
                                :summary="summary"
                                :active-template="active_template"
                                :meeting-id="meeting.id"
                                :is-demo="isDemo"
                                class="h-full"
                            />
                        </TabsContent>

                        <!-- Tab 2: Action Items -->
                        <TabsContent value="action-items" class="flex-1 min-h-0 overflow-hidden focus:outline-none transition-all duration-200">
                            <div class="h-full min-h-0 overflow-y-auto rounded-2xl border border-zinc-200/80 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                                <ActionItemsList
                                    :action-items="action_items"
                                    :action-item-state="action_item_state"
                                    :meeting-id="meeting.id"
                                    :is-demo="isDemo"
                                />
                            </div>
                        </TabsContent>

                        <!-- Tab 3: Highlights -->
                        <TabsContent value="highlights" class="flex-1 min-h-0 overflow-hidden focus:outline-none transition-all duration-200">
                            <div class="h-full min-h-0 overflow-y-auto rounded-2xl border border-zinc-200/80 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                                <HighlightsList
                                    :highlights="highlights"
                                    @seek="seekToTimestamp"
                                />
                            </div>
                        </TabsContent>
                    </TabsRoot>
                </div>
            </div>

            <!-- Right Column: Transcript, Ask AI & Highlights Side Panel -->
            <div class="h-full min-h-0 lg:col-span-5 flex flex-col overflow-hidden">
                <TranscriptList
                    :cues="transcript"
                    :active-cue-index="activeCueIndex"
                    :meeting-id="meeting.id"
                    :meeting-duration="meeting.duration_seconds"
                    :highlights="highlights"
                    :qa-history="qa_history"
                    :is-demo="isDemo"
                    @select-cue="seekToCue"
                    @seek="seekToTimestamp"
                />
            </div>
        </div>
    </div>
</template>
