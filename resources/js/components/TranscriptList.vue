<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Bookmark, BookmarkPlus, Bot, Clock, Sparkles } from '@lucide/vue';
import {
    ScrollAreaCorner,
    ScrollAreaRoot,
    ScrollAreaScrollbar,
    ScrollAreaThumb,
    ScrollAreaViewport,
} from 'reka-ui';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import AskAiPanel from '@/components/AskAiPanel.vue';
import HighlightsList from '@/components/HighlightsList.vue';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import type { HighlightItem, QaItem, TranscriptCue } from '@/types';

const props = withDefaults(
    defineProps<{
        cues: TranscriptCue[];
        activeCueIndex?: number;
        meetingId?: number;
        meetingDuration?: number;
        highlights?: HighlightItem[];
        qaHistory?: QaItem[];
    }>(),
    {
        activeCueIndex: -1,
        meetingId: undefined,
        meetingDuration: 0,
        highlights: () => [],
        qaHistory: () => [],
    },
);

const emit = defineEmits<{
    (e: 'select-cue', cue: TranscriptCue, index: number): void;
    (e: 'seek', seconds: number): void;
}>();

const activeTab = ref<'transcript' | 'ask-ai' | 'highlights'>('transcript');
const isHighlightDialogOpen = ref(false);
const activeBookmarkCue = ref<TranscriptCue | null>(null);

const highlightForm = useForm({
    timestamp_seconds: 0,
    label: 'Key Moment',
    note: '',
});

function openHighlightModal(cue: TranscriptCue) {
    activeBookmarkCue.value = cue;
    highlightForm.timestamp_seconds = Math.floor(cue.start);
    highlightForm.label = 'Key Moment';
    highlightForm.note = cue.text;
    highlightForm.clearErrors();
    isHighlightDialogOpen.value = true;
}

function submitHighlight() {
    if (!props.meetingId) {
        return;
    }

    highlightForm.post(`/meetings/${props.meetingId}/highlights`, {
        preserveScroll: true,
        onSuccess: () => {
            isHighlightDialogOpen.value = false;
            highlightForm.reset();
            activeBookmarkCue.value = null;
            toast.success('Highlight bookmarked successfully.');
        },
        onError: (errors) => {
            const err = errors.note || errors.timestamp_seconds || errors.label || 'Unable to save highlight.';
            toast.error(err);
        },
    });
}

const cueElements = ref<Record<number, HTMLElement>>({});

function setCueRef(el: unknown, index: number) {
    if (el && typeof el === 'object' && '$el' in el) {
        cueElements.value[index] = (el as { $el: HTMLElement }).$el;
    } else if (el instanceof HTMLElement) {
        cueElements.value[index] = el;
    }
}

watch(
    () => props.activeCueIndex,
    (newIndex) => {
        if (
            activeTab.value === 'transcript' &&
            typeof newIndex === 'number' &&
            newIndex >= 0 &&
            cueElements.value[newIndex]
        ) {
            cueElements.value[newIndex].scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'nearest',
            });
        }
    },
);

function formatTime(seconds: number): string {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

const speakerColors: Record<string, string> = {
    'Alex Chen': 'bg-sky-100/80 text-sky-800 dark:bg-sky-950/60 dark:text-sky-300',
    'Maya Patel': 'bg-purple-100/80 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300',
    'Marcus Brody': 'bg-emerald-100/80 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
    'Elena Rostova': 'bg-amber-100/80 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
    'Sarah Jenkins': 'bg-pink-100/80 text-pink-800 dark:bg-pink-950/60 dark:text-pink-300',
    'David Kim': 'bg-indigo-100/80 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300',
    'Rachel Adams': 'bg-rose-100/80 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300',
    'Jordan Miller': 'bg-blue-100/80 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300',
    'Samantha Wu': 'bg-teal-100/80 text-teal-800 dark:bg-teal-950/60 dark:text-teal-300',
    'Devante Washington': 'bg-violet-100/80 text-violet-800 dark:bg-violet-950/60 dark:text-violet-300',
    'Priya Sharma': 'bg-orange-100/80 text-orange-800 dark:bg-orange-950/60 dark:text-orange-300',
    'Liam O\'Connor': 'bg-cyan-100/80 text-cyan-800 dark:bg-cyan-950/60 dark:text-cyan-300',
    'Carlos Gomez': 'bg-lime-100/80 text-lime-800 dark:bg-lime-950/60 dark:text-lime-300',
    'Thomas Wright': 'bg-fuchsia-100/80 text-fuchsia-800 dark:bg-fuchsia-950/60 dark:text-fuchsia-300',
};

function getSpeakerBadgeClass(speaker: string): string {
    return speakerColors[speaker] || 'bg-zinc-100/90 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200';
}
</script>

<template>
    <div class="flex h-full flex-col overflow-hidden rounded-3xl border border-zinc-200/80 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <!-- Header with View Toggle -->
        <div class="flex items-center justify-between border-b border-zinc-100 px-4 py-2.5 dark:border-zinc-800 shrink-0">
            <div class="inline-flex rounded-full bg-zinc-100/80 p-1 dark:bg-zinc-800">
                <button
                    type="button"
                    :class="[
                        'rounded-full px-3 py-1 text-xs font-semibold transition-all cursor-pointer',
                        activeTab === 'transcript'
                            ? 'bg-white text-sky-700 shadow-xs dark:bg-zinc-700 dark:text-sky-300'
                            : 'text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200',
                    ]"
                    @click="activeTab = 'transcript'"
                >
                    Transcript
                    <span class="ml-1 rounded-full bg-sky-100/80 px-1.5 py-0.2 text-[10px] font-semibold text-sky-700 dark:bg-sky-950 dark:text-sky-300">
                        {{ cues.length }}
                    </span>
                </button>
                <button
                    type="button"
                    :class="[
                        'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold transition-all cursor-pointer',
                        activeTab === 'ask-ai'
                            ? 'bg-white text-sky-700 shadow-xs dark:bg-zinc-700 dark:text-sky-300'
                            : 'text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200',
                    ]"
                    @click="activeTab = 'ask-ai'"
                >
                    <Bot class="size-3 text-sky-500" />
                    <span>Ask AI</span>
                    <span
                        v-if="qaHistory.length > 0"
                        class="rounded-full bg-sky-100/80 px-1.5 py-0.2 text-[10px] font-semibold text-sky-700 dark:bg-sky-950 dark:text-sky-300"
                    >
                        {{ qaHistory.length }}
                    </span>
                </button>
                <button
                    type="button"
                    :class="[
                        'rounded-full px-3 py-1 text-xs font-semibold transition-all cursor-pointer',
                        activeTab === 'highlights'
                            ? 'bg-white text-indigo-700 shadow-xs dark:bg-zinc-700 dark:text-indigo-300'
                            : 'text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200',
                    ]"
                    @click="activeTab = 'highlights'"
                >
                    Highlights
                    <span class="ml-1 rounded-full bg-indigo-100/80 px-1.5 py-0.2 text-[10px] font-semibold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">
                        {{ highlights.length }}
                    </span>
                </button>
            </div>

            <div class="hidden sm:block text-[11px] text-zinc-400">
                {{ activeTab === 'transcript' ? 'Click cue to jump video' : activeTab === 'ask-ai' ? 'Ask AI copilot' : 'Click bookmark to seek' }}
            </div>
        </div>

        <!-- Tab 1: Transcript Cues -->
        <div v-if="activeTab === 'transcript'" class="flex-1 overflow-hidden">
            <ScrollAreaRoot class="relative h-full overflow-hidden" type="auto">
                <ScrollAreaViewport class="h-full w-full p-4">
                    <div v-if="cues.length > 0" class="space-y-6">
                        <div
                            v-for="(cue, index) in cues"
                            :key="index"
                            :ref="(el) => setCueRef(el, index)"
                            :class="[
                                'group relative flex cursor-pointer items-start gap-3 rounded-xl p-3.5 transition-all duration-200 border-l-4',
                                index === activeCueIndex
                                    ? 'border-l-sky-500 bg-sky-50/90 shadow-xs dark:border-l-sky-400 dark:bg-sky-950/40'
                                    : 'border-l-transparent hover:border-l-sky-300 hover:bg-sky-50/25 dark:hover:border-l-sky-700 dark:hover:bg-sky-950/20',
                            ]"
                            @click="emit('select-cue', cue, index)"
                        >
                            <!-- Timestamp Badge -->
                            <span
                                :class="[
                                    'inline-flex shrink-0 items-center gap-1 rounded-lg px-2.5 py-1 font-mono text-xs tracking-tight transition-colors',
                                    index === activeCueIndex
                                        ? 'bg-sky-600 text-white font-bold shadow-xs'
                                        : 'bg-sky-50/90 text-sky-700 font-medium group-hover:bg-sky-100 group-hover:text-sky-800 dark:bg-sky-950/60 dark:text-sky-300',
                                ]"
                            >
                                <Clock class="size-3" />
                                {{ formatTime(cue.start) }}
                            </span>

                            <!-- Speaker and Text Content -->
                            <div class="min-w-0 flex-1">
                                <div class="mb-1 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <span
                                            :class="[
                                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                                getSpeakerBadgeClass(cue.speaker),
                                            ]"
                                        >
                                            {{ cue.speaker }}
                                        </span>
                                        <span
                                            v-if="index === activeCueIndex"
                                            class="inline-flex size-1.5 rounded-full bg-sky-500 animate-pulse"
                                            title="Active Dialogue"
                                        />
                                    </div>

                                    <!-- Bookmark Action Button -->
                                    <button
                                        v-if="meetingId"
                                        type="button"
                                        class="opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity p-1 rounded-md text-zinc-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40 shrink-0"
                                        title="Bookmark this moment"
                                        @click.stop="openHighlightModal(cue)"
                                    >
                                        <BookmarkPlus class="size-4" />
                                    </button>
                                </div>

                                <p
                                    :class="[
                                        'text-sm leading-relaxed transition-colors',
                                        index === activeCueIndex
                                            ? 'font-medium text-zinc-900 dark:text-zinc-50'
                                            : 'text-zinc-700 dark:text-zinc-300',
                                    ]"
                                >
                                    {{ cue.text }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="flex h-48 flex-col items-center justify-center p-6 text-center text-zinc-400"
                    >
                        <p class="text-sm">No transcript cues available for this meeting.</p>
                    </div>
                </ScrollAreaViewport>

                <ScrollAreaScrollbar
                    class="flex touch-none select-none p-0.5 transition-colors duration-150 ease-out data-[orientation=vertical]:w-2.5"
                    orientation="vertical"
                >
                    <ScrollAreaThumb
                        class="relative flex-1 rounded-full bg-zinc-300 transition-colors hover:bg-zinc-400 dark:bg-zinc-700 dark:hover:bg-zinc-600"
                    />
                </ScrollAreaScrollbar>
                <ScrollAreaCorner />
            </ScrollAreaRoot>
        </div>

        <!-- Tab 2: Ask AI Copilot -->
        <div v-else-if="activeTab === 'ask-ai'" class="flex-1 min-h-0 overflow-hidden">
            <AskAiPanel
                v-if="meetingId"
                :meeting-id="meetingId"
                :qa-history="qaHistory"
                @seek="emit('seek', $event)"
            />
            <div
                v-else
                class="flex h-48 flex-col items-center justify-center p-6 text-center text-zinc-400"
            >
                <p class="text-sm">Meeting ID is required for AI queries.</p>
            </div>
        </div>

        <!-- Tab 3: Highlights Side Panel View -->
        <div v-else class="flex-1 min-h-0 overflow-y-auto p-4">
            <HighlightsList
                :highlights="highlights"
                @seek="emit('seek', $event)"
            />
        </div>

        <!-- Reka UI Dialog: Add Highlight / Note -->
        <Dialog v-model:open="isHighlightDialogOpen">
            <DialogContent class="sm:max-w-[440px] rounded-2xl p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xl">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2 text-base font-semibold text-zinc-900 dark:text-zinc-100">
                        <div class="flex size-7 items-center justify-center rounded-lg bg-amber-50 text-amber-600 border border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-400">
                            <Bookmark class="size-4" />
                        </div>
                        <span>Save Meeting Highlight</span>
                    </DialogTitle>
                    <DialogDescription class="text-xs text-zinc-500 dark:text-zinc-400 pt-1">
                        Bookmark playback moment at
                        <span class="font-mono font-semibold text-zinc-800 dark:text-zinc-200">
                            {{ formatTime(highlightForm.timestamp_seconds) }}
                        </span>
                        <span v-if="activeBookmarkCue">
                            by {{ activeBookmarkCue.speaker }}
                        </span>
                    </DialogDescription>
                </DialogHeader>

                <form class="space-y-4 pt-3" @submit.prevent="submitHighlight">
                    <!-- Category / Label Selection -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Category Label
                        </label>
                        <select
                            v-model="highlightForm.label"
                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs text-zinc-900 focus:border-sky-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                        >
                            <option value="Key Moment">Key Moment</option>
                            <option value="Architecture Decision">Architecture Decision</option>
                            <option value="Technical Decision">Technical Decision</option>
                            <option value="Action Item">Action Item</option>
                            <option value="Customer Feedback">Customer Feedback</option>
                            <option value="Design Token Standard">Design Token Standard</option>
                        </select>
                        <p v-if="highlightForm.errors.label" class="mt-1 text-xs text-rose-500">
                            {{ highlightForm.errors.label }}
                        </p>
                    </div>

                    <!-- Note Textarea (Required) -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Highlight Note / Quote <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            v-model="highlightForm.note"
                            rows="3"
                            required
                            placeholder="Add contextual takeaway or note..."
                            class="w-full rounded-xl border border-zinc-200 bg-white p-3 text-xs text-zinc-900 focus:border-sky-500 focus:outline-none leading-relaxed dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                        ></textarea>
                        <p v-if="highlightForm.errors.note" class="mt-1 text-xs text-rose-500">
                            {{ highlightForm.errors.note }}
                        </p>
                        <p v-if="highlightForm.errors.timestamp_seconds" class="mt-1 text-xs text-rose-500">
                            {{ highlightForm.errors.timestamp_seconds }}
                        </p>
                    </div>

                    <DialogFooter class="flex items-center justify-end gap-2 pt-2">
                        <button
                            type="button"
                            class="rounded-xl border border-zinc-200 px-3.5 py-1.5 text-xs font-medium text-zinc-600 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800"
                            @click="isHighlightDialogOpen = false"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="highlightForm.processing"
                            class="rounded-xl bg-gradient-to-r from-sky-500 to-amber-500 px-4 py-1.5 text-xs font-semibold text-white shadow-xs transition-opacity hover:opacity-95 disabled:opacity-50"
                        >
                            {{ highlightForm.processing ? 'Saving...' : 'Save Bookmark' }}
                        </button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
