<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { GroupModal } from '@/components/modals';
import { GroupTable } from '@/components/tables';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Groups',
        href: '/groups',
    },
];
</script>

<template>

    <Head title="Groups" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end gap-4">
                <GroupModal />
            </div>
            <GroupTable />
        </div>
    </AppLayout>
</template>