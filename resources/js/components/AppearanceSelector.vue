<script setup lang="ts">
import {
    SunIcon,
    MoonIcon,
    ComputerDesktopIcon,
} from '@heroicons/vue/24/outline';
import { useAppearance } from '@/lib/appearance';
const { appearance, updateAppearance } = useAppearance();

const buttons = [
    { value: 'light', Icon: SunIcon, label: 'Light' },
    { value: 'dark', Icon: MoonIcon, label: 'Dark' },
    { value: 'system', Icon: ComputerDesktopIcon, label: 'System' },
] as const;
</script>

<template>
    <div
        class="inline-flex gap-1 rounded-2xl bg-stone-200 p-1 dark:bg-taupe-900 print:hidden"
    >
        <button
            v-for="{ value, Icon, label } in buttons"
            :key="value"
            type="button"
            :aria-label="label"
            :aria-pressed="appearance === value"
            @click="updateAppearance(value)"
            :class="[
                'flex cursor-pointer items-center rounded-xl px-3 py-1.5 transition-colors',
                appearance === value
                    ? 'bg-white shadow-xs dark:bg-taupe-800 dark:text-taupe-100'
                    : 'text-stone-600 hover:bg-stone-100/60 hover:text-black dark:text-taupe-400 dark:hover:bg-taupe-800/60',
            ]"
        >
            <component :is="Icon" class="h-4 w-4" />
        </button>
    </div>
</template>
