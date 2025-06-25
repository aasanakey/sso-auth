<script setup lang="ts">
import { SocialProviderModal } from '@/components/modals';
import { SocialProvidersTable } from '@/components/tables';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>

    <Head title="Social Providers" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end">
                <SocialProviderModal />
            </div>
            <SocialProvidersTable />
        </div>
    </AppLayout>
</template>