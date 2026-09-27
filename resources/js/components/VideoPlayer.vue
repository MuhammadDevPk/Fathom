<script setup lang="ts">
import { Play, VideoOff } from '@lucide/vue';
import { ref } from 'vue';

defineProps<{
    src: string | null;
}>();

const videoRef = ref<HTMLVideoElement | null>(null);
const isPlaying = ref(false);

function togglePlay() {
    if (!videoRef.value) {
        return;
    }
    if (videoRef.value.paused) {
        void videoRef.value.play();
    } else {
        videoRef.value.pause();
    }
}

defineExpose({
    videoElement: videoRef,
});
</script>

<template>
    <div
        class="relative aspect-video w-full h-[300px] sm:h-[330px] md:h-[360px] lg:h-[340px] xl:h-[385px] 2xl:h-[425px] max-w-full overflow-hidden rounded-2xl border border-zinc-200/80 bg-zinc-950 shadow-md dark:border-zinc-800 flex items-center justify-center mx-auto shrink-0 group/player"
    >
        <video
            v-if="src"
            ref="videoRef"
            :src="src"
            controls
            playsinline
            preload="metadata"
            class="h-full w-full object-contain"
            @play="isPlaying = true"
            @pause="isPlaying = false"
            @ended="isPlaying = false"
        >
            Your browser does not support the video tag.
        </video>

        <!-- Glassmorphic Play Button Overlay (Visible when paused) -->
        <button
            v-if="src && !isPlaying"
            type="button"
            @click="togglePlay"
            class="group absolute inset-0 z-10 flex items-center justify-center bg-black/20 backdrop-blur-[1px] transition-all hover:bg-black/10 cursor-pointer"
            aria-label="Play video"
        >
            <span class="flex size-14 sm:size-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white shadow-2xl transition-all duration-300 group-hover:scale-110 group-hover:bg-white/30">
                <Play class="size-6 sm:size-7 fill-white translate-x-0.5" />
            </span>
        </button>

        <div
            v-else-if="!src"
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
