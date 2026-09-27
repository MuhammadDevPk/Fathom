<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel class="text-[10px] font-normal tracking-[0.14em] uppercase text-zinc-400 dark:text-zinc-500 mb-2 px-3">Platform</SidebarGroupLabel>
        <SidebarMenu class="space-y-1">
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="isCurrentUrl(item.href)"
                    :tooltip="item.title"
                    :class="[
                        'rounded-xl transition-all duration-200',
                        isCurrentUrl(item.href)
                            ? 'bg-sky-100/60 font-medium text-sky-800 hover:bg-sky-100/80 dark:bg-sky-950/50 dark:text-sky-300 dark:hover:bg-sky-900/50'
                            : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/70 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/50',
                    ]"
                >
                    <Link :href="item.href" class="flex items-center gap-2.5 px-3 py-2">
                        <component
                            :is="item.icon"
                            :class="[
                                'size-4 shrink-0 transition-colors duration-200',
                                isCurrentUrl(item.href) ? 'text-sky-600 dark:text-sky-400' : 'text-zinc-400 group-hover:text-zinc-600',
                            ]"
                        />
                        <span class="text-xs leading-none">{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
