<script setup lang="ts">
import {
    BuildingOfficeIcon,
    MapPinIcon,
    CalendarDateRangeIcon,
} from '@heroicons/vue/24/solid';
import type { Experience } from '@/types/models';
defineProps<{ experience: Experience }>();
</script>

<template>
    <div>
        <h2>{{ experience.title }}</h2>
        <div class="my-2 flex flex-row gap-x-3">
            <div v-if="experience.company">
                <BuildingOfficeIcon class="mr-1 inline h-4 w-4 align-middle" />
                <span class="align-middle">{{ experience.company }}</span>
            </div>
            <div v-if="experience.location">
                <MapPinIcon class="mr-1 inline h-4 w-4 align-middle" />
                <span class="align-middle">{{ experience.location }}</span>
            </div>
            <div>
                <CalendarDateRangeIcon
                    class="mr-1 inline h-4 w-4 align-middle"
                />
                <span class="align-middle">
                    from {{ experience.started_at }}
                    <span v-if="experience.ended_at"
                        >to {{ experience.ended_at }}</span
                    >
                </span>
            </div>
        </div>
        <div
            v-if="experience.description"
            class="my-2"
            v-html="experience.description"
        />
    </div>
</template>
