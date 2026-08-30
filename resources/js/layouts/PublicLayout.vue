<script setup lang="ts">
import AppearanceSelector from '@/components/AppearanceSelector.vue';
</script>

<template>
    <div class="mx-auto w-full p-4 lg:px-8 lg:py-6 xl:w-[80rem]">
        <div class="fixed top-8 right-8 z-100">
            <AppearanceSelector />
        </div>
        <slot />
    </div>
</template>
