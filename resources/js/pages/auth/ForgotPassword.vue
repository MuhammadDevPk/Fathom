<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Reset your password',
        description:
            'Enter your registered email and we will send you a password recovery link',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Forgot password" />

    <div
        v-if="status"
        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50/80 p-3 text-center text-xs font-medium text-emerald-800 dark:border-emerald-800/60 dark:bg-emerald-950/40 dark:text-emerald-300"
    >
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form
            v-bind="email.form()"
            v-slot="{ errors, processing }"
            class="space-y-5"
        >
            <div class="grid gap-1.5">
                <Label
                    for="email"
                    class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                >
                    Email address
                </Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="off"
                    v-focus
                    placeholder="you@company.com"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.email" />
            </div>

            <Button
                type="submit"
                class="mt-2 h-11 w-full cursor-pointer rounded-xl bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500 text-sm font-semibold text-white shadow-md shadow-sky-500/25 transition-all duration-200 hover:brightness-105 hover:shadow-lg hover:shadow-sky-500/35 active:scale-[0.99] disabled:opacity-60"
                :disabled="processing"
                data-test="email-password-reset-link-button"
            >
                <Spinner
                    v-if="processing"
                    class="mr-2 size-4 animate-spin text-white"
                />
                <span v-if="!processing">Send password reset link</span>
                <span v-else>Sending reset link...</span>
            </Button>
        </Form>

        <div
            class="flex items-center justify-center text-xs text-zinc-500 dark:text-zinc-400"
        >
            <TextLink
                :href="login()"
                class="group inline-flex items-center gap-1.5 font-semibold text-sky-600 no-underline transition-colors hover:text-sky-700 dark:text-sky-400"
            >
                <ArrowLeft
                    class="size-3.5 transition-transform duration-200 group-hover:-translate-x-0.5"
                />
                <span>Return to sign in</span>
            </TextLink>
        </div>
    </div>
</template>
