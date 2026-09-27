<script setup lang="ts">
import { Sparkles } from '@lucide/vue';
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-xl bg-gradient-to-tr from-sky-500 via-sky-600 to-amber-500 text-white shadow-xs"
    >
        <Sparkles class="size-4.5" />
    </div>
    <div class="ml-2.5 grid flex-1 text-left text-sm">
        <span class="truncate leading-tight font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100">Fathom</span>
    </div>
</template>
