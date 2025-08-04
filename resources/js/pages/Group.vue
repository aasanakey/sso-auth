<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Group, User, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { GroupUsersTable } from '@/components/tables';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Groups',
        href: '/groups',
    },
];
const { group } = defineProps<{
    group: Group;
    users: User[];
}>();
</script>

<template>
    <Head :title="group.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-4">
                <p class="text-2xl font-bold capitalize">Group:&nbsp;{{ group.name }}</p>
            </div>
            <GroupUsersTable/>
        </div>
    </AppLayout>
</template>