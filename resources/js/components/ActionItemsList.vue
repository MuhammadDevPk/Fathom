<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { CheckCircle2, Circle, ListTodo, User } from '@lucide/vue';
import { computed, reactive, watch } from 'vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import { buildSpeakerColorMap, getSpeakerColor } from '@/lib/speakerColors';
import type { ActionItem } from '@/types';

const props = withDefaults(
    defineProps<{
        actionItems?: ActionItem[];
        actionItemState?: Record<string, boolean>;
        meetingId?: number;
        isDemo?: boolean;
    }>(),
    {
        actionItems: () => [],
        actionItemState: () => ({}),
        meetingId: undefined,
        isDemo: false,
    },
);

// Local state for optimistic UI updates
const localState = reactive<Record<number, boolean>>({});

// Sync local state when actionItemState prop updates from session
watch(
    () => props.actionItemState,
    (newState) => {
        if (newState) {
            Object.keys(newState).forEach((key) => {
                const match = key.match(/^item_index_(\d+)$/);
                if (match) {
                    const idx = Number(match[1]);
                    localState[idx] = Boolean(newState[key]);
                }
            });
        }
    },
    { immediate: true, deep: true },
);

function isCompleted(item: ActionItem, index: number): boolean {
    if (index in localState) {
        return localState[index];
    }
    const sessionKey = `item_index_${index}`;
    if (props.actionItemState && sessionKey in props.actionItemState) {
        return Boolean(props.actionItemState[sessionKey]);
    }
    return Boolean(item.completed);
}

function toggleItem(item: ActionItem, index: number) {
    const nextChecked = !isCompleted(item, index);
    localState[index] = nextChecked;

    // In demo mode or without meetingId, maintain local-only state per specs
    if (props.isDemo || !props.meetingId) {
        return;
    }

    router.post(
        `/meetings/${props.meetingId}/action-items/toggle`,
        {
            index,
            checked: nextChecked,
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
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

const speakerColorMap = computed(() => {
    const assignees = props.actionItems
        .map((item) => item.assignee)
        .filter((s): s is string => typeof s === 'string' && s.trim() !== '');
    return buildSpeakerColorMap(assignees);
});

function getSpeakerBadgeClass(speaker: string): string {
    return getSpeakerColor(speaker, speakerColorMap.value).badge;
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
