<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CvDocument from '@/components/CvDocument.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { CurriculumVitae } from '@/types/models';
defineProps<{ cv: CurriculumVitae }>();
defineOptions({ layout: PublicLayout });
</script>

<template>
    <Head>
        <title>{{ cv.person.full_name }} - {{ cv.headline }}</title>
    </Head>
    <CvDocument :cv />
</template>
