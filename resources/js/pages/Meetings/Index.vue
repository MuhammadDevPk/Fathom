<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Calendar, Search, Sparkles, Video, X } from '@lucide/vue';
import { useDebounceFn } from '@vueuse/core';
import { ref, watch } from 'vue';
import MeetingCard from '@/components/MeetingCard.vue';
import type { PaginatedMeetings } from '@/types';

const props = defineProps<{
    meetings: PaginatedMeetings;
    filters?: {
        search?: string;
    };
}>();

const searchTerm = ref(props.filters?.search ?? '');

const performSearch = useDebounceFn((term: string) => {
    router.reload({
        only: ['meetings'],
        data: { search: term.trim() ? term.trim() : undefined },
        replace: true,
    });
}, 300);

watch(searchTerm, (newVal) => {
    performSearch(newVal);
});

function clearSearch() {
    searchTerm.value = '';
    performSearch('');
}

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Meetings',
                href: '/meetings',
            },
        ],
    },
});
</script>

<template>
    <Head title="Meetings - Fathom" />

    <div class="flex flex-1 flex-col gap-6 p-6 md:p-8">
        <!-- Header Banner with Light-Mode Ambient Glow -->
        <div class="relative overflow-hidden rounded-3xl border border-zinc-200/80 bg-gradient-to-br from-white via-sky-50/30 to-amber-50/20 p-6 shadow-sm md:p-8 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <div class="mb-2 inline-flex items-center gap-1.5 rounded-full border border-sky-200/60 bg-sky-50 px-3 py-0.5 text-xs font-medium text-sky-700 dark:border-sky-800/60 dark:bg-sky-950/40 dark:text-sky-300">
                        <Sparkles class="size-3.5" />
                        AI Meeting Intelligence
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 md:text-3xl dark:text-zinc-100">
                        Meetings
                    </h1>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                        Select a meeting to review the synchronized transcript, video playback, and AI executive summary.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-xl border border-zinc-200/80 bg-white px-3 py-1.5 text-xs font-semibold text-zinc-700 shadow-xs dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                        <Video class="size-3.5 text-sky-500" />
                        {{ meetings.total }} Total Meetings
                    </span>
                </div>
            </div>
        </div>

        <!-- Global Search Bar -->
        <div class="relative">
            <div class="relative flex items-center">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-zinc-400">
                    <Search class="size-4" />
                </div>
                <input
                    v-model="searchTerm"
                    type="text"
                    placeholder="Search meetings by title, speaker dialogue, or keywords..."
                    class="w-full rounded-2xl border border-zinc-200/90 bg-white py-3 pl-11 pr-10 text-xs text-zinc-900 shadow-2xs transition-all placeholder:text-zinc-400 focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-100 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-100 dark:focus:ring-sky-950/50"
                />
                <button
                    v-if="searchTerm"
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-pointer"
                    title="Clear search"
                    @click="clearSearch"
                >
                    <X class="size-4" />
                </button>
            </div>
        </div>

        <!-- Meeting Cards Grid -->
        <div
            v-if="meetings.data.length > 0"
            class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
        >
            <MeetingCard
                v-for="meeting in meetings.data"
                :key="meeting.id"
                :meeting="meeting"
            />
        </div>

        <!-- Empty Search State -->
        <div
            v-else-if="searchTerm.trim()"
            class="flex min-h-[320px] flex-col items-center justify-center rounded-3xl border border-dashed border-zinc-200 bg-white p-8 text-center dark:border-zinc-800 dark:bg-zinc-900"
        >
            <div class="flex size-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">
                <Search class="size-7" />
            </div>
            <h3 class="mt-4 text-base font-semibold text-zinc-900 dark:text-zinc-100">
                No meetings matching "{{ searchTerm }}"
            </h3>
            <p class="mt-1 max-w-sm text-xs text-zinc-500 leading-relaxed dark:text-zinc-400">
                We couldn't find any meeting titles or transcript dialogue matching your search. Try searching for a different keyword or speaker name.
            </p>
            <button
                type="button"
                class="mt-4 inline-flex items-center gap-1.5 rounded-xl border border-zinc-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-zinc-700 shadow-xs transition-colors hover:bg-zinc-50 cursor-pointer dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                @click="clearSearch"
            >
                Clear search
            </button>
        </div>

        <!-- Default Empty State -->
        <div
            v-else
            class="flex min-h-[300px] flex-col items-center justify-center rounded-3xl border border-dashed border-zinc-300 bg-white p-8 text-center dark:border-zinc-800 dark:bg-zinc-900"
        >
            <div class="flex size-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400">
                <Calendar class="size-7" />
            </div>
            <h3 class="mt-4 text-base font-semibold text-zinc-900 dark:text-zinc-100">
                No meetings recorded yet
            </h3>
            <p class="mt-1 max-w-sm text-xs text-zinc-500 dark:text-zinc-400">
                Run the database seeder or import new meeting recordings to populate your intelligence workspace.
            </p>
        </div>

        <!-- Pagination Controls -->
        <div
            v-if="meetings.last_page > 1"
            class="flex items-center justify-center gap-2 pt-4"
        >
            <template v-for="(link, index) in meetings.links" :key="index">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="[
                        'rounded-lg px-3 py-1.5 text-xs font-medium transition-colors',
                        link.active
                            ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900'
                            : 'border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
                    ]"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="px-2 text-xs text-zinc-400"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
