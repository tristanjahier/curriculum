<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CvDocument from '@/components/CvDocument.vue';
import type { CurriculumVitae } from '@/types/models';
defineProps<{ defaultCv: CurriculumVitae | null }>();
</script>

<template>
    <template v-if="defaultCv">
        <Head>
            <title>
                {{ defaultCv.person.full_name }} - {{ defaultCv.headline }}
            </title>
        </Head>
        <CvDocument :cv="defaultCv" />
    </template>

    <div v-else>No curriculum vitae is available.</div>
</template>
