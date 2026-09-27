<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, Clock, Video } from '@lucide/vue';
import { computed } from 'vue';
import SummaryPanel from '@/components/SummaryPanel.vue';
import TranscriptList from '@/components/TranscriptList.vue';
import VideoPlayer from '@/components/VideoPlayer.vue';
import type { MeetingDetail, TranscriptCue } from '@/types';

const props = defineProps<{
    meeting: MeetingDetail;
    transcript: TranscriptCue[];
    summary: string | null;
}>();

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

    <div class="flex flex-1 flex-col gap-6 p-6 md:p-8">
        <!-- Top Nav & Meeting Title Header -->
        <div class="flex flex-col gap-3 border-b border-zinc-200/80 pb-5 dark:border-zinc-800">
            <div>
                <Link
                    href="/meetings"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 transition-colors hover:text-sky-600 dark:text-zinc-400 dark:hover:text-sky-400"
                >
                    <ArrowLeft class="size-3.5" />
                    Back to all meetings
                </Link>
            </div>

            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <h1 class="text-xl font-bold tracking-tight text-zinc-900 md:text-2xl dark:text-zinc-100">
                    {{ meeting.title }}
                </h1>

                <!-- Meta Pills -->
                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-sky-200/70 bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-700 dark:border-sky-800/60 dark:bg-sky-950/40 dark:text-sky-300"
                    >
                        <Clock class="size-3.5" />
                        {{ formattedDuration }}
                    </span>

                    <span
                        v-if="formattedDate"
                        class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200/80 bg-zinc-50 px-2.5 py-1 text-xs font-medium text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                    >
                        <Calendar class="size-3.5 text-zinc-400" />
                        {{ formattedDate }}
                    </span>

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200/70 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:border-emerald-800/60 dark:bg-emerald-950/40 dark:text-emerald-300"
                    >
                        <Video class="size-3.5" />
                        Synced Media
                    </span>
                </div>
            </div>
        </div>

        <!-- Three-Panel Layout:
             Left Column: Video (Top Left) + Summary (Bottom Left, below video)
             Right Column: Transcript (Right)
        -->
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
            <!-- Left Column: Video + Summary -->
            <div class="flex flex-col gap-6 lg:col-span-7">
                <!-- Video Player (Top Left) -->
                <div>
                    <VideoPlayer :src="meeting.video_url" />
                </div>

                <!-- Executive Summary (Bottom Left, below video) -->
                <div class="min-h-[340px]">
                    <SummaryPanel :summary="summary" />
                </div>
            </div>

            <!-- Right Column: Transcript (Right) -->
            <div class="h-[600px] lg:col-span-5 lg:h-[calc(100vh-13rem)] lg:min-h-[640px]">
                <TranscriptList :cues="transcript" />
            </div>
        </div>
    </div>
</template>
