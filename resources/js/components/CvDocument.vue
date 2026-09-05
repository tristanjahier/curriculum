<script setup lang="ts">
import type { CurriculumVitae } from '@/types/models';
import CvExperience from './CvExperience.vue';
defineProps<{ cv: CurriculumVitae }>();
</script>

<template>
    <div id="cv-document">
        <div id="cv-person" class="my-4">
            <div class="text-2xl">
                {{ cv.person.first_name }}
                <span class="uppercase">{{ cv.person.last_name }}</span>
            </div>

            <div v-if="cv.headline != null">{{ cv.headline }}</div>
            <div v-if="cv.person.age != null">Age: {{ cv.person.age }}</div>
        </div>

        <div id="cv-contact-details" class="my-4">
            <div v-if="cv.person.residence != null">
                Residence: {{ cv.person.residence }}
            </div>
            <div v-if="cv.person.phone != null">
                Phone: {{ cv.person.phone }}
            </div>
            <div v-if="cv.person.email != null">
                Email: {{ cv.person.email }}
            </div>
        </div>

        <div id="cv-summary" v-if="cv.summary != null" class="my-4">
            {{ cv.summary }}
        </div>

        <div
            id="cv-experience-container"
            class="my-4"
            v-if="cv.experiences.length > 0"
        >
            <h1>Experience</h1>
            <CvExperience
                v-for="experience in cv.experiences"
                :key="experience.id"
                :experience
                class="my-4"
            />
        </div>
    </div>
</template>
