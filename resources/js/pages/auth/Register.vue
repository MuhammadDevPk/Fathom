<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Create your account',
        description: 'Get started with Fathom AI meeting intelligence',
    },
});
</script>

<template>
    <Head title="Register" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-4">
            <!-- Full Name -->
            <div class="grid gap-1.5">
                <Label
                    for="name"
                    class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                >
                    Full name
                </Label>
                <Input
                    id="name"
                    type="text"
                    required
                    v-focus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    placeholder="Alex Chen"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.name" />
            </div>

            <!-- Email Address -->
            <div class="grid gap-1.5">
                <Label
                    for="email"
                    class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                >
                    Work email
                </Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="you@company.com"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.email" />
            </div>

            <!-- Password -->
            <div class="grid gap-1.5">
                <Label
                    for="password"
                    class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                >
                    Password
                </Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Create a strong password"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.password" />
            </div>

            <!-- Confirm Password -->
            <div class="grid gap-1.5">
                <Label
                    for="password_confirmation"
                    class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                >
                    Confirm password
                </Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="4"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Repeat your password"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <!-- Submit Button -->
            <Button
                type="submit"
                class="mt-2 h-11 w-full cursor-pointer rounded-xl bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500 text-sm font-semibold text-white shadow-md shadow-sky-500/25 transition-all duration-200 hover:brightness-105 hover:shadow-lg hover:shadow-sky-500/35 active:scale-[0.99] disabled:opacity-60"
                tabindex="5"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner
                    v-if="processing"
                    class="mr-2 size-4 animate-spin text-white"
                />
                <span v-if="!processing">Create account</span>
                <span v-else>Creating account...</span>
            </Button>
        </div>

        <!-- Privacy note -->
        <p class="text-center text-[11px] text-zinc-500 dark:text-zinc-400">
            By creating an account, you agree to our Terms of Service and
            Privacy Policy.
        </p>

        <!-- Switch Link -->
        <div class="text-center text-xs text-zinc-500 dark:text-zinc-400">
            Already have an account?
            <TextLink
                :href="login()"
                class="ml-1 font-semibold text-sky-600 underline underline-offset-4 hover:text-sky-700 dark:text-sky-400"
                :tabindex="6"
            >
                Log in
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
