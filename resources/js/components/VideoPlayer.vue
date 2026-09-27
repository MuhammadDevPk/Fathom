<script setup lang="ts">
import { VideoOff } from '@lucide/vue';
import { ref } from 'vue';

defineProps<{
    src: string | null;
}>();

const videoRef = ref<HTMLVideoElement | null>(null);

defineExpose({
    videoElement: videoRef,
});
</script>

<template>
    <div
        class="relative aspect-video w-full max-h-[220px] xl:max-h-[250px] 2xl:max-h-[280px] overflow-hidden rounded-2xl border border-zinc-200/80 bg-zinc-950 shadow-md dark:border-zinc-800 flex items-center justify-center mx-auto shrink-0"
    >
        <video
            v-if="src"
            ref="videoRef"
            :src="src"
            controls
            playsinline
            preload="metadata"
            class="h-full w-full max-h-[220px] xl:max-h-[250px] 2xl:max-h-[280px] object-contain"
        >
            Your browser does not support the video tag.
        </video>

        <div
            v-else
            class="flex h-full w-full flex-col items-center justify-center gap-1.5 p-4 text-center text-zinc-400"
        >
            <div class="flex size-10 items-center justify-center rounded-full bg-zinc-900 text-zinc-500">
                <VideoOff class="size-5" />
            </div>
            <p class="text-xs font-medium">No video source provided</p>
            <p class="text-[11px] text-zinc-500">This meeting has transcript intelligence only</p>
        </div>
    </div>
</template>
