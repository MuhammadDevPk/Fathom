<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Choose a new password',
        description: 'Please enter and confirm your new password below',
    },
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <Head title="Reset password" />

    <Form
        v-bind="update.form()"
        :transform="(data) => ({ ...data, token, email })"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-4">
            <!-- Email -->
            <div class="grid gap-1.5">
                <Label
                    for="email"
                    class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                >
                    Email
                </Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    v-model="inputEmail"
                    readonly
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-100/70 px-3.5 text-sm text-zinc-500 transition-all duration-200 dark:border-zinc-800 dark:bg-zinc-800/50 dark:text-zinc-400"
                />
                <InputError :message="errors.email" />
            </div>

            <!-- New Password -->
            <div class="grid gap-1.5">
                <Label
                    for="password"
                    class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                >
                    New password
                </Label>
                <PasswordInput
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    autofocus
                    placeholder="Enter new password"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.password" />
            </div>

            <!-- Confirm New Password -->
            <div class="grid gap-1.5">
                <Label
                    for="password_confirmation"
                    class="text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-zinc-300"
                >
                    Confirm new password
                </Label>
                <PasswordInput
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Repeat new password"
                    :passwordrules="passwordRules"
                    class="h-11 rounded-xl border-zinc-200/90 bg-zinc-50/50 px-3.5 text-sm transition-all duration-200 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 dark:border-zinc-800 dark:bg-zinc-900/60 dark:focus:border-sky-500"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <!-- Submit Button -->
            <Button
                type="submit"
                class="mt-2 h-11 w-full cursor-pointer rounded-xl bg-gradient-to-r from-sky-500 via-sky-600 to-amber-500 text-sm font-semibold text-white shadow-md shadow-sky-500/25 transition-all duration-200 hover:brightness-105 hover:shadow-lg hover:shadow-sky-500/35 active:scale-[0.99] disabled:opacity-60"
                :disabled="processing"
                data-test="reset-password-button"
            >
                <Spinner
                    v-if="processing"
                    class="mr-2 size-4 animate-spin text-white"
                />
                <span v-if="!processing">Reset password</span>
                <span v-else>Resetting password...</span>
            </Button>
        </div>
    </Form>
</template>
