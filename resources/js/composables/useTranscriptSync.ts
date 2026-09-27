import { computed, ref, toValue, watch, type MaybeRefOrGetter } from 'vue';
import { useMagicKeys, useMediaControls, whenever } from '@vueuse/core';
import type { TranscriptCue } from '@/types';

export interface UseTranscriptSyncOptions {
    autoScroll?: boolean;
}

export function useTranscriptSync(
    videoTarget: MaybeRefOrGetter<HTMLVideoElement | null | undefined>,
    cuesTarget: MaybeRefOrGetter<TranscriptCue[]>,
    options: UseTranscriptSyncOptions = {},
) {
    const videoRef = computed(() => toValue(videoTarget));
    const cues = computed(() => toValue(cuesTarget) || []);

    const { currentTime, playing, duration } = useMediaControls(videoRef);

    const activeCueIndex = ref<number>(-1);

    // Active cue detection: simple timestamp range check per .agent/patterns.md
    // current_time >= start && current_time <= end
    watch(currentTime, (time) => {
        const list = cues.value;
        if (!list || list.length === 0) {
            return;
        }

        const foundIndex = list.findIndex(
            (cue) => time >= cue.start && time <= cue.end,
        );

        if (foundIndex !== -1) {
            activeCueIndex.value = foundIndex;
        }
        // When paused or between small pauses, keep the current cue highlight frozen
    });

    // Click to seek: seek to cue start and trigger playback
    function seekToCue(cue: TranscriptCue, index?: number) {
        const el = videoRef.value;
        if (el) {
            el.currentTime = cue.start;
            const playPromise = el.play();
            if (playPromise !== undefined) {
                playPromise.catch(() => {
                    // Autoplay policy or user gesture handling
                });
            }
        } else {
            currentTime.value = cue.start;
            playing.value = true;
        }

        if (typeof index === 'number') {
            activeCueIndex.value = index;
        } else {
            const idx = cues.value.findIndex(
                (c) => c.start === cue.start && c.end === cue.end,
            );
            if (idx !== -1) {
                activeCueIndex.value = idx;
            }
        }
    }

    // Toggle play/pause
    function togglePlay() {
        const el = videoRef.value;
        if (el) {
            if (el.paused) {
                el.play().catch(() => {});
            } else {
                el.pause();
            }
        } else {
            playing.value = !playing.value;
        }
    }

    // Space key shortcut via useMagicKeys (no custom key handlers)
    const { space } = useMagicKeys({
        passive: false,
        onEventFired(e) {
            if (e.code === 'Space') {
                const target = e.target as HTMLElement | null;
                const isInput =
                    target &&
                    (['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName) ||
                        target.isContentEditable);
                if (!isInput) {
                    e.preventDefault();
                }
            }
        },
    });

    whenever(space, () => {
        const activeEl = document.activeElement as HTMLElement | null;
        const isInput =
            activeEl &&
            (['INPUT', 'TEXTAREA', 'SELECT'].includes(activeEl.tagName) ||
                activeEl.isContentEditable);
        if (!isInput) {
            togglePlay();
        }
    });

    return {
        videoRef,
        currentTime,
        playing,
        duration,
        activeCueIndex,
        seekToCue,
        togglePlay,
    };
}
