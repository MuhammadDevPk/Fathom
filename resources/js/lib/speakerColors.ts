export interface SpeakerColorClass {
    badge: string;
    pill: string;
    border: string;
    text: string;
}

/**
 * 10 distinct, curated pastel entries adhering to .agent/ui_reference.md design tokens.
 */
export const SPEAKER_PALETTE: SpeakerColorClass[] = [
    {
        badge: 'bg-sky-50 text-sky-700 border-sky-200/70 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60',
        pill: 'bg-sky-100/90 text-sky-800 dark:bg-sky-950/60 dark:text-sky-300',
        border: 'border-l-sky-500',
        text: 'text-sky-700 dark:text-sky-300',
    },
    {
        badge: 'bg-purple-50 text-purple-700 border-purple-200/70 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/60',
        pill: 'bg-purple-100/90 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300',
        border: 'border-l-purple-500',
        text: 'text-purple-700 dark:text-purple-300',
    },
    {
        badge: 'bg-emerald-50 text-emerald-700 border-emerald-200/70 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
        pill: 'bg-emerald-100/90 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300',
        border: 'border-l-emerald-500',
        text: 'text-emerald-700 dark:text-emerald-300',
    },
    {
        badge: 'bg-amber-50 text-amber-700 border-amber-200/70 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60',
        pill: 'bg-amber-100/90 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300',
        border: 'border-l-amber-500',
        text: 'text-amber-700 dark:text-amber-300',
    },
    {
        badge: 'bg-rose-50 text-rose-700 border-rose-200/70 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60',
        pill: 'bg-rose-100/90 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300',
        border: 'border-l-rose-500',
        text: 'text-rose-700 dark:text-rose-300',
    },
    {
        badge: 'bg-indigo-50 text-indigo-700 border-indigo-200/70 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/60',
        pill: 'bg-indigo-100/90 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300',
        border: 'border-l-indigo-500',
        text: 'text-indigo-700 dark:text-indigo-300',
    },
    {
        badge: 'bg-teal-50 text-teal-700 border-teal-200/70 dark:bg-teal-950/40 dark:text-teal-300 dark:border-teal-800/60',
        pill: 'bg-teal-100/90 text-teal-800 dark:bg-teal-950/60 dark:text-teal-300',
        border: 'border-l-teal-500',
        text: 'text-teal-700 dark:text-teal-300',
    },
    {
        badge: 'bg-cyan-50 text-cyan-700 border-cyan-200/70 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-800/60',
        pill: 'bg-cyan-100/90 text-cyan-800 dark:bg-cyan-950/60 dark:text-cyan-300',
        border: 'border-l-cyan-500',
        text: 'text-cyan-700 dark:text-cyan-300',
    },
    {
        badge: 'bg-orange-50 text-orange-700 border-orange-200/70 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800/60',
        pill: 'bg-orange-100/90 text-orange-800 dark:bg-orange-950/60 dark:text-orange-300',
        border: 'border-l-orange-500',
        text: 'text-orange-700 dark:text-orange-300',
    },
    {
        badge: 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200/70 dark:bg-fuchsia-950/40 dark:text-fuchsia-300 dark:border-fuchsia-800/60',
        pill: 'bg-fuchsia-100/90 text-fuchsia-800 dark:bg-fuchsia-950/60 dark:text-fuchsia-300',
        border: 'border-l-fuchsia-500',
        text: 'text-fuchsia-700 dark:text-fuchsia-300',
    },
];

/**
 * Build a deterministic speaker-to-color mapping for the given list of speaker names (or cue objects)
 * in order of their first appearance.
 */
export function buildSpeakerColorMap(
    speakersOrCues: (string | { speaker?: string })[],
): Map<string, SpeakerColorClass> {
    const map = new Map<string, SpeakerColorClass>();
    let index = 0;

    for (const item of speakersOrCues) {
        const name = typeof item === 'string' ? item : item.speaker;
        if (name && typeof name === 'string' && name.trim() !== '' && !map.has(name)) {
            map.set(name, SPEAKER_PALETTE[index % SPEAKER_PALETTE.length]);
            index++;
        }
    }

    return map;
}

/**
 * Get speaker color classes using a precomputed map or falling back to a deterministic string hash.
 */
export function getSpeakerColor(
    speaker: string,
    speakerMap?: Map<string, SpeakerColorClass>,
): SpeakerColorClass {
    if (speakerMap?.has(speaker)) {
        return speakerMap.get(speaker)!;
    }

    // Deterministic string hash fallback
    let hash = 0;
    for (let i = 0; i < speaker.length; i++) {
        hash = (hash << 5) - hash + speaker.charCodeAt(i);
        hash |= 0;
    }
    const idx = Math.abs(hash) % SPEAKER_PALETTE.length;
    return SPEAKER_PALETTE[idx];
}
