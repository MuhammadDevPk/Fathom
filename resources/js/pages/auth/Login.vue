<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Welcome back',
        description: 'Sign in to access your meetings, transcripts & AI summaries',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50/80 p-3 text-center text-xs font-medium text-emerald-800 dark:border-emerald-800/60 dark:bg-emerald-950/40 dark:text-emerald-300"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-5">
            <!-- Email Field -->
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
                    required
                    v-focus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="you@company.com"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.email" />
            </div>

            <!-- Password Field -->
            <div class="grid gap-1.5">
                <div class="flex items-center justify-between">
                    <Label
                        for="password"
                        class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                    >
                        Password
                    </Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs font-semibold text-sky-600 no-underline transition-colors hover:text-sky-700 hover:underline dark:text-sky-400"
                        :tabindex="5"
                    >
                        Forgot password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-0.5">
                <Label
                    for="remember"
                    class="flex cursor-pointer select-none items-center gap-2.5 text-xs font-medium text-zinc-600 dark:text-zinc-400"
                >
                    <Checkbox
                        id="remember"
                        name="remember"
                        :tabindex="3"
                        class="size-4 rounded-md border-zinc-300 transition-all data-[state=checked]:border-sky-600 data-[state=checked]:bg-sky-600 dark:border-zinc-700"
                    />
                    <span>Remember this device</span>
                </Label>
            </div>

            <!-- Submit Button -->
            <Button
                type="submit"
                class="mt-2 h-11 w-full cursor-pointer rounded-xl bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500 text-sm font-semibold text-white shadow-md shadow-sky-500/25 transition-all duration-200 hover:brightness-105 hover:shadow-lg hover:shadow-sky-500/35 active:scale-[0.99] disabled:opacity-60"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner
                    v-if="processing"
                    class="mr-2 size-4 animate-spin text-white"
                />
                <span v-if="!processing">Sign in</span>
                <span v-else>Signing in...</span>
            </Button>
        </div>

        <!-- Quick Demo Credentials Callout -->
        <div
            class="rounded-xl border border-sky-100 bg-sky-50/60 p-3 text-xs dark:border-sky-900/40 dark:bg-sky-950/20"
        >
            <div
                class="flex items-center justify-between text-[11px] text-zinc-500 dark:text-zinc-400"
            >
                <span class="font-semibold text-sky-800 dark:text-sky-300">
                    Demo Account
                </span>
                <span
                    class="select-all font-mono text-zinc-600 dark:text-zinc-300"
                >
                    demo@example.com / password
                </span>
            </div>
        </div>

        <!-- Switch Link -->
        <div class="text-center text-xs text-zinc-500 dark:text-zinc-400">
            Don't have an account yet?
            <TextLink
                :href="register()"
                :tabindex="5"
                class="ml-1 font-semibold text-sky-600 underline underline-offset-4 hover:text-sky-700 dark:text-sky-400"
            >
                Create account
            </TextLink>
        </div>
    </Form>

    <!-- Ghost Demo Button Below Form -->
    <div class="mt-4 pt-4 border-t border-zinc-200/80 text-center dark:border-zinc-800">
        <Link
            method="post"
            href="/demo/login"
            as="button"
            type="button"
            class="inline-flex cursor-pointer items-center justify-center gap-1.5 text-xs font-semibold text-zinc-500 transition-colors duration-200 hover:text-sky-600 active:scale-95 dark:text-zinc-400 dark:hover:text-sky-400"
        >
            <span>Or try the demo without an account &rarr;</span>
        </Link>
    </div>
</template>
