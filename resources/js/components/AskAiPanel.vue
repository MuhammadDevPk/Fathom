<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import {
    Bot,
    Clock,
    HelpCircle,
    MessageSquare,
    Play,
    Send,
    Sparkles,
    User,
} from '@lucide/vue';
import { nextTick, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import type { QaItem } from '@/types';

const props = withDefaults(
    defineProps<{
        meetingId: number;
        qaHistory?: QaItem[];
    }>(),
    {
        qaHistory: () => [],
    },
);

const emit = defineEmits<{
    (e: 'seek', seconds: number): void;
}>();

const form = useForm({
    question: '',
});

const chatContainerRef = ref<HTMLElement | null>(null);

function scrollChatToBottom() {
    nextTick(() => {
        if (chatContainerRef.value) {
            chatContainerRef.value.scrollTop = chatContainerRef.value.scrollHeight;
        }
    });
}

watch(
    () => props.qaHistory.length,
    () => {
        scrollChatToBottom();
    },
);

function submitQuestion(customQuestion?: string) {
    if (customQuestion) {
        form.question = customQuestion;
    }

    if (!form.question.trim() || form.processing) {
        return;
    }

    form.post(`/meetings/${props.meetingId}/ask`, {
        preserveScroll: true,
        onError: (errors) => {
            const message = errors.question || 'Unable to generate an answer. Please try again.';
            toast.error(message);
        },
        onSuccess: () => {
            form.reset('question');
            scrollChatToBottom();
        },
    });
}

function handleKeydown(event: KeyboardEvent) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        if (!form.processing) {
            submitQuestion();
        }
    }
}

interface ParsedToken {
    type: 'citation' | 'text';
    text: string;
    seconds?: number;
}

function parseAnswerTokens(answer: string): ParsedToken[] {
    const tokens: ParsedToken[] = [];
    const timestampRegex = /\[?(\b\d{1,2}:\d{2}\b)\]?/g;

    let lastIndex = 0;
    let match: RegExpExecArray | null;

    while ((match = timestampRegex.exec(answer)) !== null) {
        const matchStart = match.index;
        const matchEnd = timestampRegex.lastIndex;

        if (matchStart > lastIndex) {
            tokens.push({
                type: 'text',
                text: answer.slice(lastIndex, matchStart),
            });
        }

        const timeStr = match[1];
        const [mins, secs] = timeStr.split(':').map(Number);
        const totalSeconds = (mins || 0) * 60 + (secs || 0);

        tokens.push({
            type: 'citation',
            text: timeStr,
            seconds: totalSeconds,
        });

        lastIndex = matchEnd;
    }

    if (lastIndex < answer.length) {
        tokens.push({
            type: 'text',
            text: answer.slice(lastIndex),
        });
    }

    return tokens;
}

const quickPrompts = [
    'What were the key decisions made?',
    'List all action items with owners',
    'Summarize the technical discussion',
];
</script>

<template>
    <div class="flex h-full min-h-0 flex-col overflow-hidden bg-white dark:bg-zinc-900">
        <!-- Header -->
        <div class="flex shrink-0 items-center justify-between border-b border-zinc-100 px-4 py-2.5 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <div class="flex size-6 items-center justify-center rounded-lg bg-gradient-to-tr from-sky-500 to-amber-500 text-white shadow-xs">
                    <Sparkles class="size-3.5" />
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-zinc-900 dark:text-zinc-100">
                        Ask Fathom AI
                    </h3>
                    <p class="text-[10px] text-zinc-400">
                        Answers with timestamp video citations
                    </p>
                </div>
            </div>

            <span class="rounded-full bg-sky-50 px-2 py-0.5 text-[10px] font-medium text-sky-700 border border-sky-200/70 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60">
                {{ qaHistory.length }} {{ qaHistory.length === 1 ? 'Question' : 'Questions' }}
            </span>
        </div>

        <!-- Chat History Scroll Container -->
        <div
            ref="chatContainerRef"
            class="flex-1 min-h-0 space-y-4 overflow-y-auto p-4"
        >
            <!-- Empty State / Welcome Guide -->
            <div
                v-if="qaHistory.length === 0 && !form.processing"
                class="flex flex-col items-center justify-center py-8 text-center"
            >
                <div class="flex size-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-sky-100 to-amber-100 text-sky-600 dark:from-sky-950 dark:to-amber-950 dark:text-sky-400">
                    <MessageSquare class="size-6" />
                </div>
                <h4 class="mt-3 text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    Query This Meeting
                </h4>
                <p class="mt-1 max-w-sm text-xs text-zinc-500 leading-relaxed dark:text-zinc-400">
                    Ask questions about topics, decisions, or timeline commitments. Citations in answers link directly to video moments.
                </p>

                <!-- Quick Prompts -->
                <div class="mt-5 flex flex-wrap justify-center gap-2 max-w-md">
                    <button
                        v-for="prompt in quickPrompts"
                        :key="prompt"
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200/80 bg-zinc-50/70 px-3 py-1.5 text-xs text-zinc-700 transition-all hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 cursor-pointer dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:border-sky-700 dark:hover:text-sky-300"
                        @click="submitQuestion(prompt)"
                    >
                        <HelpCircle class="size-3 text-sky-500" />
                        {{ prompt }}
                    </button>
                </div>
            </div>

            <!-- Q&A Conversation Flow -->
            <template v-for="item in qaHistory" :key="item.id">
                <!-- User Question Bubble -->
                <div class="flex justify-end">
                    <div class="flex max-w-[85%] items-start gap-2.5">
                        <div class="rounded-2xl rounded-tr-xs bg-zinc-900 px-4 py-2.5 text-xs text-white shadow-xs dark:bg-zinc-100 dark:text-zinc-900 break-words">
                            {{ item.question }}
                        </div>
                        <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-zinc-200 text-zinc-600 text-[10px] font-semibold dark:bg-zinc-700 dark:text-zinc-200">
                            <User class="size-3.5" />
                        </div>
                    </div>
                </div>

                <!-- AI Response Card -->
                <div class="flex justify-start">
                    <div class="flex max-w-[90%] items-start gap-2.5">
                        <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-sky-500 to-amber-500 text-white shadow-xs">
                            <Bot class="size-3.5" />
                        </div>
                        <div class="rounded-2xl rounded-tl-xs border border-zinc-200/80 bg-zinc-50/70 p-4 text-xs leading-relaxed text-zinc-800 shadow-2xs dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200">
                            <div class="mb-2 flex items-center justify-between border-b border-zinc-200/60 pb-1.5 dark:border-zinc-800">
                                <span class="font-semibold text-zinc-900 dark:text-zinc-100 text-[11px] flex items-center gap-1">
                                    <Sparkles class="size-3 text-sky-500" />
                                    Fathom AI Assistant
                                </span>
                                <span class="text-[10px] text-zinc-400">
                                    Transcript Verified
                                </span>
                            </div>

                            <!-- Tokenized Markdown Text with Clickable Citations -->
                            <div class="space-y-2 leading-relaxed">
                                <template v-for="(token, tIdx) in parseAnswerTokens(item.answer)" :key="tIdx">
                                    <button
                                        v-if="token.type === 'citation'"
                                        type="button"
                                        class="inline-flex items-center gap-1 rounded-md border border-sky-300 bg-sky-50 px-1.5 py-0.5 font-mono text-[11px] font-semibold text-sky-700 transition-colors hover:bg-sky-600 hover:text-white cursor-pointer align-baseline mx-0.5 shadow-2xs dark:border-sky-800/80 dark:bg-sky-950/60 dark:text-sky-300"
                                        title="Seek video to this moment"
                                        @click="emit('seek', token.seconds!)"
                                    >
                                        <Play class="size-2.5 fill-current" />
                                        {{ token.text }}
                                    </button>
                                    <span v-else class="whitespace-pre-wrap break-words">{{ token.text }}</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Loading State Skeleton -->
            <div v-if="form.processing" class="flex justify-start">
                <div class="flex max-w-[85%] items-start gap-2.5">
                    <div class="flex size-6 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-sky-500 to-amber-500 text-white shadow-xs animate-spin">
                        <Sparkles class="size-3.5" />
                    </div>
                    <div class="w-72 rounded-2xl rounded-tl-xs border border-zinc-200/80 bg-zinc-50/70 p-4 shadow-2xs dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-medium text-sky-600 dark:text-sky-400 animate-pulse">
                                Analyzing meeting transcript...
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="h-2.5 w-full rounded-full bg-zinc-200 dark:bg-zinc-800 animate-pulse"></div>
                            <div class="h-2.5 w-5/6 rounded-full bg-zinc-200 dark:bg-zinc-800 animate-pulse"></div>
                            <div class="h-2.5 w-2/3 rounded-full bg-zinc-200 dark:bg-zinc-800 animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Bar Footer -->
        <div class="shrink-0 border-t border-zinc-100 p-3 dark:border-zinc-800">
            <form class="flex items-center gap-2" @submit.prevent="submitQuestion()">
                <div class="relative flex-1">
                    <input
                        v-model="form.question"
                        type="text"
                        placeholder="Ask a question about this meeting (e.g. What was agreed upon?)..."
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50/60 px-3.5 py-2 text-xs text-zinc-900 placeholder:text-zinc-400 focus:border-sky-500 focus:bg-white focus:outline-none dark:border-zinc-700 dark:bg-zinc-800/80 dark:text-zinc-100 dark:focus:bg-zinc-800"
                        :disabled="form.processing"
                        @keydown="handleKeydown"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="!form.question.trim() || form.processing"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-sky-500 to-amber-500 px-4 py-2 text-xs font-semibold text-white shadow-xs transition-all hover:opacity-95 disabled:opacity-50 cursor-pointer shrink-0"
                >
                    <Send class="size-3.5" />
                    <span>Ask</span>
                </button>
            </form>
        </div>
    </div>
</template>
