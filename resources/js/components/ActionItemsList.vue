<script setup lang="ts">
import { CheckCircle2, Circle, ListTodo, User } from '@lucide/vue';
import { computed, reactive } from 'vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import type { ActionItem } from '@/types';

const props = withDefaults(
    defineProps<{
        actionItems?: ActionItem[];
    }>(),
    {
        actionItems: () => [],
    },
);

// Session-only local UI state for checkbox toggle per .agent/architecture.md
const localState = reactive<Record<number, boolean>>({});

function isCompleted(item: ActionItem, index: number): boolean {
    const key = item.id ?? index;
    if (key in localState) {
        return localState[key];
    }
    return Boolean(item.completed);
}

function toggleItem(item: ActionItem, index: number) {
    const key = item.id ?? index;
    localState[key] = !isCompleted(item, index);
}

const completedCount = computed(() => {
    return props.actionItems.filter((item, index) => isCompleted(item, index)).length;
});

const progressPercent = computed(() => {
    if (!props.actionItems.length) {
        return 0;
    }
    return Math.round((completedCount.value / props.actionItems.length) * 100);
});

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
    <div class="flex h-full flex-col">
        <!-- Progress Bar & Counter Header -->
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 border border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60">
                    {{ completedCount }} / {{ actionItems.length }} Completed
                </span>
            </div>

            <!-- Progress Bar -->
            <div class="flex items-center gap-2">
                <div class="h-2 w-28 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                    <div
                        class="h-full rounded-full bg-gradient-to-r from-amber-500 to-emerald-500 transition-all duration-300"
                        :style="{ width: `${progressPercent}%` }"
                    />
                </div>
                <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    {{ progressPercent }}%
                </span>
            </div>
        </div>

        <!-- Action Items List -->
        <div v-if="actionItems.length > 0" class="space-y-3 overflow-y-auto pr-1">
            <div
                v-for="(item, index) in actionItems"
                :key="item.id ?? index"
                :class="[
                    'group flex items-start gap-3 rounded-xl border p-3.5 transition-all duration-200 cursor-pointer',
                    isCompleted(item, index)
                        ? 'border-zinc-200/60 bg-zinc-50/60 opacity-75 dark:border-zinc-800 dark:bg-zinc-900/40'
                        : 'border-zinc-200/80 bg-white hover:border-amber-300/80 hover:bg-amber-50/20 shadow-2xs dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-amber-800/60',
                ]"
                @click="toggleItem(item, index)"
            >
                <!-- Checkbox -->
                <div class="pt-0.5" @click.stop>
                    <Checkbox
                        :model-value="isCompleted(item, index)"
                        class="cursor-pointer"
                        @update:model-value="toggleItem(item, index)"
                    />
                </div>

                <!-- Content & Assignee -->
                <div class="min-w-0 flex-1">
                    <p
                        :class="[
                            'text-sm leading-relaxed transition-colors',
                            isCompleted(item, index)
                                ? 'line-through text-zinc-400 dark:text-zinc-500'
                                : 'font-medium text-zinc-800 dark:text-zinc-200',
                        ]"
                    >
                        {{ (item as any).task || (item as any).text }}
                    </p>

                    <!-- Assignee Pill -->
                    <div class="mt-2 flex items-center gap-2">
                        <span
                            v-if="item.assignee"
                            :class="[
                                'inline-flex items-center gap-1.5 rounded-full border px-2 py-0.5 text-xs font-medium',
                                getSpeakerBadgeClass(item.assignee),
                            ]"
                        >
                            <User class="size-3" />
                            {{ item.assignee }}
                        </span>
                        <span v-else class="text-xs text-zinc-400">
                            Unassigned
                        </span>

                        <span
                            v-if="isCompleted(item, index)"
                            class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 dark:text-emerald-400"
                        >
                            <CheckCircle2 class="size-3" />
                            Done
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
            <ListTodo class="size-7 text-zinc-300 dark:text-zinc-600" />
            <p class="text-sm font-medium">No action items extracted</p>
            <p class="text-xs text-zinc-400">Action items will be detected automatically during meeting processing</p>
        </div>
    </div>
</template>
