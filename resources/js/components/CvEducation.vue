<script setup lang="ts">
import {
    BuildingLibraryIcon,
    MapPinIcon,
    CalendarDateRangeIcon,
} from '@heroicons/vue/24/solid';
import type { Education } from '@/types/models';
defineProps<{ education: Education }>();
</script>

<template>
    <div>
        <h2>{{ education.title }}</h2>
        <div class="my-2 flex flex-row gap-x-3">
            <div v-if="education.institution">
                <BuildingLibraryIcon class="mr-1 inline h-4 w-4 align-middle" />
                <span class="align-middle">{{ education.institution }}</span>
            </div>
            <div v-if="education.location">
                <MapPinIcon class="mr-1 inline h-4 w-4 align-middle" />
                <span class="align-middle">{{ education.location }}</span>
            </div>
            <div>
                <CalendarDateRangeIcon
                    class="mr-1 inline h-4 w-4 align-middle"
                />
                <span class="align-middle">
                    from {{ education.started_at }}
                    <span v-if="education.ended_at"
                        >to {{ education.ended_at }}</span
                    >
                </span>
            </div>
        </div>
        <div
            v-if="education.description"
            class="my-2"
            v-html="education.description"
        />
    </div>
</template>
