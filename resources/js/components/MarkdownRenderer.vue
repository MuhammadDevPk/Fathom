<script setup lang="ts">
import MarkdownIt from 'markdown-it';
import { computed } from 'vue';

const props = defineProps<{
    content: string;
}>();

const emit = defineEmits<{
    (e: 'seek', seconds: number): void;
}>();

const md = new MarkdownIt({
    html: false,
    linkify: true,
    breaks: true,
});

function replaceTimestampsWithPills(html: string): string {
    // Split into HTML tags and text chunks to only replace timestamps in text nodes
    const parts = html.split(/(<[^>]+>)/g);
    const timestampRegex = /\[?(\b\d{1,2}:\d{2}\b)\]?/g;

    return parts
        .map((part) => {
            if (part.startsWith('<') && part.endsWith('>')) {
                return part;
            }

            return part.replace(timestampRegex, (_match, timeStr: string) => {
                const [mins, secs] = timeStr.split(':').map(Number);
                const totalSeconds = (mins || 0) * 60 + (secs || 0);

                return `<button type="button" data-seek="${totalSeconds}" class="inline-flex items-center gap-1 rounded-md border border-sky-300 bg-sky-50 px-1.5 py-0.5 font-mono text-[11px] font-semibold text-sky-700 transition-colors hover:bg-sky-600 hover:text-white cursor-pointer align-baseline mx-0.5 shadow-2xs dark:border-sky-800/80 dark:bg-sky-950/60 dark:text-sky-300" title="Seek video to ${timeStr}"><svg class="inline-block size-2.5 fill-current" viewBox="0 0 24 24"><polygon points="6 3 20 12 6 21 6 3"></polygon></svg>${timeStr}</button>`;
            });
        })
        .join('');
}

const renderedHtml = computed(() => {
    if (!props.content) {
        return '';
    }
    const rawHtml = md.render(props.content);
    return replaceTimestampsWithPills(rawHtml);
});

function handleClick(event: MouseEvent) {
    const target = (event.target as HTMLElement).closest('[data-seek]');
    if (target) {
        event.preventDefault();
        event.stopPropagation();
        const seconds = Number(target.getAttribute('data-seek'));
        if (!isNaN(seconds)) {
            emit('seek', seconds);
        }
    }
}
</script>

<template>
    <div
        class="markdown-content prose prose-sm prose-slate max-w-none text-xs leading-relaxed text-zinc-800 dark:prose-invert dark:text-zinc-200 break-words"
        @click="handleClick"
        v-html="renderedHtml"
    />
</template>

<style scoped>
.markdown-content :deep(p) {
    margin-bottom: 0.5rem;
}
.markdown-content :deep(p:last-child) {
    margin-bottom: 0;
}
.markdown-content :deep(ul) {
    list-style-type: disc;
    padding-left: 1.25rem;
    margin-top: 0.25rem;
    margin-bottom: 0.5rem;
}
.markdown-content :deep(ol) {
    list-style-type: decimal;
    padding-left: 1.25rem;
    margin-top: 0.25rem;
    margin-bottom: 0.5rem;
}
.markdown-content :deep(li) {
    margin-top: 0.125rem;
    margin-bottom: 0.125rem;
}
.markdown-content :deep(strong) {
    font-weight: 600;
    color: inherit;
}
.markdown-content :deep(em) {
    font-style: italic;
}
.markdown-content :deep(code) {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 0.85em;
    background-color: rgba(244, 244, 245, 0.9);
    padding: 0.1rem 0.25rem;
    border-radius: 0.25rem;
    border: 1px solid rgba(228, 228, 231, 0.8);
}
.markdown-content :deep(pre) {
    background-color: #18181b;
    color: #f4f4f5;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
.markdown-content :deep(pre code) {
    background-color: transparent;
    padding: 0;
    border: none;
    color: inherit;
}
.markdown-content :deep(blockquote) {
    border-left: 3px solid #cbd5e1;
    padding-left: 0.75rem;
    color: #64748b;
    font-style: italic;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
.markdown-content :deep(table) {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.75rem;
}
.markdown-content :deep(th) {
    border: 1px solid rgba(228, 228, 231, 0.8);
    background-color: rgba(244, 244, 245, 0.8);
    padding: 0.35rem 0.5rem;
    font-weight: 600;
    text-align: left;
}
.markdown-content :deep(td) {
    border: 1px solid rgba(228, 228, 231, 0.8);
    padding: 0.35rem 0.5rem;
}
.markdown-content :deep(h1),
.markdown-content :deep(h2),
.markdown-content :deep(h3),
.markdown-content :deep(h4) {
    font-weight: 600;
    color: inherit;
    margin-top: 0.75rem;
    margin-bottom: 0.25rem;
}
.markdown-content :deep(h1) { font-size: 1rem; }
.markdown-content :deep(h2) { font-size: 0.9rem; }
.markdown-content :deep(h3) { font-size: 0.85rem; }
.markdown-content :deep(h4) { font-size: 0.8rem; }
</style>
