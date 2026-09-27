<script setup lang="ts">
import { Clock } from '@lucide/vue';
import {
    ScrollAreaCorner,
    ScrollAreaRoot,
    ScrollAreaScrollbar,
    ScrollAreaThumb,
    ScrollAreaViewport,
} from 'reka-ui';
import type { TranscriptCue } from '@/types';

defineProps<{
    cues: TranscriptCue[];
}>();

function formatTime(seconds: number): string {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

const speakerColors: Record<string, string> = {
    'Alex Chen': 'bg-sky-50 text-sky-700 border-sky-200/70 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60',
    'Maya Patel': 'bg-purple-50 text-purple-700 border-purple-200/70 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/60',
    'Marcus Brody': 'bg-emerald-50 text-emerald-700 border-emerald-200/70 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
    'Elena Rostova': 'bg-amber-50 text-amber-700 border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60',
    'Sarah Jenkins': 'bg-pink-50 text-pink-700 border-pink-200/70 dark:bg-pink-950/40 dark:text-pink-300 dark:border-pink-800/60',
    'David Kim': 'bg-indigo-50 text-indigo-700 border-indigo-200/70 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/60',
    'Rachel Adams': 'bg-rose-50 text-rose-700 border-rose-200/70 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60',
    'Jordan Miller': 'bg-blue-50 text-blue-700 border-blue-200/70 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/60',
    'Samantha Wu': 'bg-teal-50 text-teal-700 border-teal-200/70 dark:bg-teal-950/40 dark:text-teal-300 dark:border-teal-800/60',
    'Devante Washington': 'bg-violet-50 text-violet-700 border-violet-200/70 dark:bg-violet-950/40 dark:text-violet-300 dark:border-violet-800/60',
    'Priya Sharma': 'bg-orange-50 text-orange-700 border-orange-200/70 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800/60',
    'Liam O\'Connor': 'bg-cyan-50 text-cyan-700 border-cyan-200/70 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-800/60',
    'Carlos Gomez': 'bg-lime-50 text-lime-700 border-lime-200/70 dark:bg-lime-950/40 dark:text-lime-300 dark:border-lime-800/60',
    'Thomas Wright': 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200/70 dark:bg-fuchsia-950/40 dark:text-fuchsia-300 dark:border-fuchsia-800/60',
};

function getSpeakerBadgeClass(speaker: string): string {
    return speakerColors[speaker] || 'bg-zinc-100 text-zinc-700 border-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700';
}
</script>

<template>
    <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200/80 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                    Transcript
                </h3>
                <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    {{ cues.length }} cues
                </span>
            </div>
        </div>

        <!-- Scrollable Cues Container with Reka UI ScrollArea -->
        <ScrollAreaRoot class="relative flex-1 overflow-hidden" type="auto">
            <ScrollAreaViewport class="h-full w-full p-4">
                <div v-if="cues.length > 0" class="space-y-2">
                    <div
                        v-for="(cue, index) in cues"
                        :key="index"
                        class="group flex cursor-pointer items-start gap-3 rounded-xl p-3 transition-colors hover:bg-zinc-50/80 dark:hover:bg-zinc-800/50"
                    >
                        <!-- Timestamp Badge -->
                        <span
                            class="inline-flex shrink-0 items-center gap-1 rounded-md border border-zinc-200/70 bg-zinc-50 px-2 py-1 font-mono text-xs font-medium text-zinc-500 transition-colors group-hover:border-sky-300 group-hover:text-sky-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:group-hover:text-sky-400"
                        >
                            <Clock class="size-3" />
                            {{ formatTime(cue.start) }}
                        </span>

                        <!-- Speaker and Text Content -->
                        <div class="min-w-0 flex-1">
                            <div class="mb-1 flex items-center gap-2">
                                <span
                                    :class="[
                                        'inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium',
                                        getSpeakerBadgeClass(cue.speaker),
                                    ]"
                                >
                                    {{ cue.speaker }}
                                </span>
                            </div>
                            <p class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
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
</template>
