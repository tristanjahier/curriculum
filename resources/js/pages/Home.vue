<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CvDocument from '@/components/CvDocument.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { CurriculumVitae } from '@/types/models';
defineProps<{ defaultCv: CurriculumVitae | null }>();
defineOptions({ layout: PublicLayout });
</script>

<template>
    <Head v-if="defaultCv">
        <title>
            {{ defaultCv.person.full_name }} - {{ defaultCv.headline }}
        </title>
    </Head>

    <CvDocument v-if="defaultCv" :cv="defaultCv" />

    <div v-else>No curriculum vitae is available.</div>
</template>
