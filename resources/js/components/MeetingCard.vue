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
</script>

<template>
    <Link
        :href="`/meetings/${meeting.id}`"
        class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-sky-300 hover:shadow-lg hover:shadow-sky-500/5 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-sky-600"
    >
        <div>
            <!-- Top Badges Row -->
            <div class="mb-4 flex items-center justify-between gap-2">
                <span
                    class="inline-flex items-center gap-1.5 rounded-full border border-sky-200/70 bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 dark:border-sky-800/60 dark:bg-sky-950/40 dark:text-sky-300"
                >
                    <Clock class="size-3.5" />
                    {{ formattedDuration }}
                </span>

                <span
                    v-if="formattedDate"
                    class="inline-flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400"
                >
                    <Calendar class="size-3.5" />
                    {{ formattedDate }}
                </span>
            </div>

            <!-- Title -->
            <h3
                class="line-clamp-2 text-lg font-semibold tracking-tight text-zinc-900 transition-colors group-hover:text-sky-600 dark:text-zinc-100 dark:group-hover:text-sky-400"
            >
                {{ meeting.title }}
            </h3>

            <!-- Speakers Row -->
            <div class="mt-4 flex flex-wrap items-center gap-1.5">
                <span
                    class="inline-flex items-center gap-1 rounded-full border border-zinc-200/80 bg-zinc-50 px-2.5 py-0.5 text-xs text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                >
                    <Users class="size-3 text-zinc-400" />
                    {{ meeting.speaker_count }} {{ meeting.speaker_count === 1 ? 'speaker' : 'speakers' }}
                </span>

                <span
                    v-for="(speaker, index) in meeting.speakers.slice(0, 3)"
                    :key="index"
                    class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"
                >
                    {{ speaker }}
                </span>

                <span
                    v-if="meeting.speakers.length > 3"
                    class="text-xs text-zinc-400"
                >
                    +{{ meeting.speakers.length - 3 }} more
                </span>
            </div>
        </div>

        <!-- Bottom Footer Action -->
        <div class="mt-6 flex items-center justify-between border-t border-zinc-100 pt-4 text-xs font-medium text-zinc-500 group-hover:text-sky-600 dark:border-zinc-800 dark:text-zinc-400 dark:group-hover:text-sky-400">
            <span class="inline-flex items-center gap-1.5">
                <Video class="size-3.5" />
                Video & Transcript
            </span>
            <span class="inline-flex items-center gap-1 transition-transform group-hover:translate-x-0.5">
                View meeting
                <ArrowRight class="size-3.5" />
            </span>
        </div>
    </Link>
</template>
