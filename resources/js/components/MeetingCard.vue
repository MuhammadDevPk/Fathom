<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Calendar, Clock, Users, Video } from '@lucide/vue';
import { computed } from 'vue';
import type { MeetingListItem } from '@/types';

const props = defineProps<{
    meeting: MeetingListItem;
}>();

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

const speakerPillColors = [
    'bg-sky-50 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300',
    'bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300',
    'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300',
];
</script>

<template>
    <Link
        :href="`/meetings/${meeting.id}`"
        class="group relative flex flex-col justify-between rounded-3xl bg-white p-8 md:p-9 shadow-sm shadow-slate-200/50 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:shadow-xl hover:shadow-sky-100/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 dark:bg-zinc-900 dark:shadow-none dark:hover:shadow-zinc-950/60"
    >
        <!-- Soft gradient border treatment (low-opacity blue→amber) similar to SenseLab Starter card -->
        <span
            class="pointer-events-none absolute -inset-[1px] rounded-3xl p-[1.5px] bg-gradient-to-br from-sky-400/40 via-indigo-300/25 to-amber-400/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            aria-hidden="true"
        >
            <span class="block size-full rounded-[23px] bg-white dark:bg-zinc-900" />
        </span>

        <!-- Base subtle border when not hovered -->
        <span
            class="pointer-events-none absolute inset-0 rounded-3xl border border-zinc-200/80 group-hover:border-transparent transition-colors duration-300"
            aria-hidden="true"
        />

        <div class="relative z-10">
            <!-- Top Badges Row -->
            <div class="mb-5 flex items-center justify-between gap-2">
                <!-- Duration badge: soft blue tinted background, slightly larger, bolder weight -->
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-sky-50/90 px-3.5 py-1.5 font-mono text-xs font-bold text-sky-700 dark:bg-sky-950/60 dark:text-sky-300"
                >
                    <Clock class="size-3.5 text-sky-600 dark:text-sky-400" />
                    {{ formattedDuration }}
                </span>

                <span
                    v-if="formattedDate"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-400 dark:text-zinc-500"
                >
                    <Calendar class="size-3.5 text-zinc-400" />
                    {{ formattedDate }}
                </span>
            </div>

            <!-- Title -->
            <h3
                class="line-clamp-2 text-xl font-bold tracking-tight text-zinc-900 leading-snug transition-colors duration-200 group-hover:text-sky-600 dark:text-zinc-100 dark:group-hover:text-sky-400"
            >
                {{ meeting.title }}
            </h3>

            <!-- Speakers Row: larger gap between title and chips, tinted pill backgrounds with soft fills -->
            <div class="mt-6 flex flex-wrap items-center gap-2">
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100/90 px-3 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"
                >
                    <Users class="size-3 text-zinc-400" />
                    {{ meeting.speaker_count }} {{ meeting.speaker_count === 1 ? 'speaker' : 'speakers' }}
                </span>

                <span
                    v-for="(speaker, index) in meeting.speakers.slice(0, 3)"
                    :key="index"
                    :class="[
                        'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium',
                        speakerPillColors[index % speakerPillColors.length],
                    ]"
                >
                    {{ speaker }}
                </span>

                <span
                    v-if="meeting.speakers.length > 3"
                    class="text-xs font-medium text-zinc-400"
                >
                    +{{ meeting.speakers.length - 3 }} more
                </span>
            </div>
        </div>

        <!-- Bottom Footer Action -->
        <div class="relative z-10 mt-8 flex items-center justify-between border-t border-zinc-100/90 pt-4 text-xs font-medium text-zinc-400 transition-colors duration-200 group-hover:text-sky-600 dark:border-zinc-800/80 dark:text-zinc-500 dark:group-hover:text-sky-400">
            <span class="inline-flex items-center gap-1.5">
                <Video class="size-3.5" />
                Video & Synced Transcript
            </span>
            <span class="inline-flex items-center gap-1 font-semibold transition-transform duration-200 group-hover:translate-x-1">
                View meeting
                <ArrowRight class="size-3.5" />
            </span>
        </div>
    </Link>
</template>
