<script setup lang="ts">
import { Bookmark, Clock, Play } from '@lucide/vue';
import type { HighlightItem } from '@/types';

withDefaults(
    defineProps<{
        highlights?: HighlightItem[];
    }>(),
    {
        highlights: () => [],
    },
);

const emit = defineEmits<{
    (e: 'seek', seconds: number): void;
}>();

function formatTime(seconds: number): string {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

const labelColors: Record<string, string> = {
    'Architecture Decision': 'bg-sky-50 text-sky-700 border-sky-200/70 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60',
    'Technical Decision': 'bg-indigo-50 text-indigo-700 border-indigo-200/70 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/60',
    'Action Item': 'bg-amber-50 text-amber-700 border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60',
    'Design Token Standard': 'bg-purple-50 text-purple-700 border-purple-200/70 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/60',
    'Executive Approval': 'bg-emerald-50 text-emerald-700 border-emerald-200/70 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
    'Customer Feedback': 'bg-rose-50 text-rose-700 border-rose-200/70 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60',
    'Root Cause Analysis': 'bg-orange-50 text-orange-700 border-orange-200/70 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800/60',
};

function getLabelBadgeClass(label: string): string {
    return labelColors[label] || 'bg-zinc-100 text-zinc-700 border-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700';
}
</script>

<template>
    <div class="flex h-full flex-col">
        <!-- Header -->
        <div class="mb-4 flex items-center justify-between border-b border-zinc-100 pb-3 dark:border-zinc-800">
            <span class="rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-700 border border-sky-200/70 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60">
                {{ highlights.length }} Saved Bookmarks
            </span>
            <span class="text-xs text-zinc-400">
                Click highlight to jump video
            </span>
        </div>

        <!-- Highlights List -->
        <div v-if="highlights.length > 0" class="space-y-3 overflow-y-auto pr-1">
            <div
                v-for="highlight in highlights"
                :key="highlight.id"
                class="group flex items-start gap-3 rounded-xl border border-zinc-200/80 bg-white p-3.5 transition-all duration-200 hover:border-sky-300 hover:bg-sky-50/30 hover:shadow-2xs cursor-pointer dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-sky-800/60"
                @click="emit('seek', highlight.timestamp_seconds)"
            >
                <!-- Timestamp Pill with Play Icon -->
                <button
                    type="button"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-sky-200/70 bg-sky-50 px-2 py-1 font-mono text-xs font-semibold text-sky-700 transition-colors group-hover:bg-sky-600 group-hover:text-white dark:border-sky-800/60 dark:bg-sky-950/40 dark:text-sky-300"
                    title="Seek to this moment"
                >
                    <Play class="size-2.5 fill-current" />
                    {{ formatTime(highlight.timestamp_seconds) }}
                </button>

                <!-- Label & Note -->
                <div class="min-w-0 flex-1">
                    <div class="mb-1 flex items-center gap-2">
                        <span
                            :class="[
                                'inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium',
                                getLabelBadgeClass(highlight.label),
                            ]"
                        >
                            {{ highlight.label }}
                        </span>
                    </div>

                    <p v-if="highlight.note" class="text-xs leading-relaxed text-zinc-700 dark:text-zinc-300">
                        {{ highlight.note }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="flex h-40 flex-col items-center justify-center gap-2 text-center text-zinc-400"
        >
            <Bookmark class="size-7 text-zinc-300 dark:text-zinc-600" />
            <p class="text-sm font-medium">No highlights saved yet</p>
            <p class="text-xs text-zinc-400">Click the bookmark icon on any transcript cue to save a highlight</p>
        </div>
    </div>
</template>
