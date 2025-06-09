<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import ClientTable from '@/components/tables/ClientTable.vue';
import { Button } from "@/components/ui/button"

import ClientModal from "@/components/modals/ClientModal.vue";
import ClientForm from "@/components/forms/ClientForm.vue";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clients',
        href: '/client',
    },
];
</script>

<template>
    <Head title="Clients" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end">
                <ClientModal/>
            </div>
            <ClientTable/>
        </div>
    </AppLayout>
</template>