<script setup lang="ts">
import { Sparkles } from '@lucide/vue';
import {
    TabsContent,
    TabsList,
    TabsRoot,
    TabsTrigger,
} from 'reka-ui';

defineProps<{
    summary: string | null;
}>();
</script>

<template>
    <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200/80 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <!-- Top Bar with Reka UI Tabs -->
        <TabsRoot default-value="general" class="flex h-full flex-col">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800">
                <div class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-gradient-to-tr from-sky-500 to-amber-500 text-white shadow-sm">
                        <Sparkles class="size-4" />
                    </div>
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                        Executive Summary
                    </h3>
                </div>

                <TabsList class="inline-flex rounded-xl bg-zinc-100/80 p-1 dark:bg-zinc-800">
                    <TabsTrigger
                        value="general"
                        class="rounded-lg px-3 py-1 text-xs font-medium text-zinc-600 transition-all data-[state=active]:bg-white data-[state=active]:text-zinc-900 data-[state=active]:shadow-sm dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-zinc-100"
                    >
                        General
                    </TabsTrigger>
                    <TabsTrigger
                        value="sales"
                        class="rounded-lg px-3 py-1 text-xs font-medium text-zinc-600 transition-all data-[state=active]:bg-white data-[state=active]:text-zinc-900 data-[state=active]:shadow-sm dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-zinc-100"
                    >
                        Sales
                    </TabsTrigger>
                    <TabsTrigger
                        value="engineering"
                        class="rounded-lg px-3 py-1 text-xs font-medium text-zinc-600 transition-all data-[state=active]:bg-white data-[state=active]:text-zinc-900 data-[state=active]:shadow-sm dark:text-zinc-400 dark:data-[state=active]:bg-zinc-700 dark:data-[state=active]:text-zinc-100"
                    >
                        Engineering
                    </TabsTrigger>
                </TabsList>
            </div>

            <!-- Tabs Content -->
            <TabsContent value="general" class="flex-1 overflow-y-auto">
                <div v-if="summary" class="prose prose-zinc prose-sm dark:prose-invert max-w-none space-y-3 leading-relaxed text-zinc-700 dark:text-zinc-300">
                    <div class="whitespace-pre-line text-sm">
                        {{ summary }}
                    </div>
                </div>
                <div v-else class="flex h-32 items-center justify-center text-center text-sm text-zinc-400">
                    Summary intelligence is currently processing...
                </div>
            </TabsContent>

            <TabsContent value="sales" class="flex-1 overflow-y-auto">
                <div class="rounded-xl border border-dashed border-zinc-200 p-6 text-center text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                    <p class="text-sm font-medium">Sales Intelligence Perspective</p>
                    <p class="mt-1 text-xs text-zinc-400">Template switching and dynamic re-synthesis will be enabled in Phase 4.</p>
                </div>
            </TabsContent>

            <TabsContent value="engineering" class="flex-1 overflow-y-auto">
                <div class="rounded-xl border border-dashed border-zinc-200 p-6 text-center text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                    <p class="text-sm font-medium">Engineering Intelligence Perspective</p>
                    <p class="mt-1 text-xs text-zinc-400">Technical decision extraction and PR mapping will be enabled in Phase 4.</p>
                </div>
            </TabsContent>
        </TabsRoot>
    </div>
</template>
