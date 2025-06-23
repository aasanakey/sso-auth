<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Authorized Clients',
        href: '/client',
    },
];

const tokens = ref<any[]>([])

const getTokens = async () => {
    try {
        const response = await fetch('/oauth/tokens')
        const data = await response.json()
        tokens.value = data
    } catch (error) {
        console.error('Error fetching tokens:', error)
    }
}

const revoke = async (token:any) => {
    try {
        await fetch(`/oauth/tokens/${token.id}`, {
            method: 'DELETE'
        })
        await getTokens()
    } catch (error) {
        console.error('Error revoking token:', error)
    }
}

onMounted(() => {
    getTokens()
})

</script>

<style scoped>
.action-link {
    cursor: pointer;
}
</style>
<template>

    <Head title="Clients" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <div v-if="tokens.length > 0">
                    <div class="card card-default">
                        <div class="card-header">Authorized Applications</div>

                        <div class="card-body">
                            <!-- Authorized Tokens -->
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Scopes</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="token in tokens" :key="token.id">
                                        <!-- Client Name -->
                                        <td style="vertical-align: middle;">
                                            {{ token.client.name }}
                                        </td>

                                        <!-- Scopes -->
                                        <td style="vertical-align: middle;">
                                            <span v-if="token.scopes.length > 0">
                                                {{ token.scopes.join(', ') }}
                                            </span>
                                        </td>

                                        <!-- Revoke Button -->
                                        <td style="vertical-align: middle;">
                                            <a class="action-link text-danger" @click="revoke(token)">
                                                Revoke
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p>No authorized clients</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
