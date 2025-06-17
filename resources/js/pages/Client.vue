<script setup lang="ts">
import { Switch } from '@/components/ui/switch';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import AppLayout from '@/layouts/AppLayout.vue';
import { mask_text } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Copy, Info } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    client: Record<string, any>,
    secret?: string,
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clients',
        href: '/client/:id',
    },
];
const { client, secret } = defineProps<Props>();

const plainSecret = ref<string>(secret || "")
const tooltipText = ref('Copy to clipboard')
const tooltipOpen = ref(false)
const showSecret = ref(false)

const copySecret = async () => {
    try {
        await navigator.clipboard.writeText(plainSecret.value)
        tooltipText.value = 'Copied!'
        tooltipOpen.value = true
        setTimeout(() => {
            tooltipOpen.value = false
            tooltipText.value = 'Copy to clipboard'
        }, 1500)
    } catch (err) {
        tooltipText.value = 'Failed to copy'
        tooltipOpen.value = true
        setTimeout(() => (tooltipOpen.value = false), 1500)
        console.error(err)
    }
}

const onOpenChange = (val: boolean) => {
    tooltipOpen.value = val;
}
const toggleSecret = (val:boolean) => {
    showSecret.value = val;
}

</script>

<template>

    <Head :title="client.name ?? 'Client'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div v-if="secret"
                class="mx-auto shadow rounded-lg p-4 text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400">
                <h2>Client Created</h2>
                <p class="flex items-center gap-1"><strong>ID:</strong> {{ client.id }}</p>
                <p class="flex items-center gap-1"><strong>Name:</strong> {{ client.name }}</p>
                <p class="flex items-center gap-1">
                    <strong>Secret:</strong> <code>{{ secret || 'ac998c53-202a-49f7-b349-48bfa3aec216' }}</code>
                    <TooltipProvider>
                        <Tooltip :open="tooltipOpen" @update:open="onOpenChange">
                            <TooltipTrigger as-child>
                                <button type="button" @click="copySecret"
                                    class="ml-2 cursor-pointer bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                                    aria-label="Copy">
                                    <span class="sr-only">Copy</span>
                                    <Copy class="w-4 h-4" />
                                </button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>{{ tooltipText }}</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                </p>
                <p class="text-red-500">⚠️ Make sure to copy this secret now. The client secret will not be shown again,
                    so don't lose it!</p>
            </div>
            <div>
                <div class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="flex flex-col gap-y-1.5 p-6">
                        <h3 class="font-semibold leading-none tracking-tight">Client Settings</h3>
                        <p class="text-sm text-muted-foreground">Manage your client settings here.</p>
                    </div>
                    <div class="p-6 pt-0 grid gap-6">
                        <div class="flex items-center justify-between space-x-2">
                            <label for="id"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col space-y-1">
                                <span>Client ID</span>
                                <span class="font-normal leading-snug text-muted-foreground">
                                    {{ client.id }}
                                </span>
                            </label>
                            <!-- <button
                                class="peer inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=unchecked]:bg-input"
                                default-checked="" id="necessary" role="switch" type="button" aria-checked="false"
                                aria-required="false" data-state="unchecked" value="on"
                                aria-label="Strictly Necessary These cookies are essential in order to use the website and use its features.">
                                <span data-state="unchecked"
                                    class="pointer-events-none block h-4 w-4 rounded-full bg-background shadow-lg ring-0 transition-transform data-[state=checked]:translate-x-4 data-[state=unchecked]:translate-x-0">
                                </span>
                            </button> -->
                        </div>

                        <div class="flex items-center justify-between space-x-2">
                            <label for="id"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col space-y-1">
                                <span>Client Name</span>
                                <span class="font-normal leading-snug text-muted-foreground">
                                    {{ client.name }}
                                </span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between space-x-2">
                            <label for="id"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col space-y-1">
                                <span>Redirect Uris</span>
                                <span v-for="uri in client.redirect_uris"
                                    class="font-normal leading-snug text-muted-foreground">
                                    {{ uri }}
                                </span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between space-x-2">
                            <label for="id"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col space-y-1">
                                <span>Grant Types</span>
                                <span v-for="grant_type in client.grant_types"
                                    class="font-normal leading-snug text-muted-foreground">
                                    {{ grant_type }}
                                </span>
                            </label>
                        </div>

                        <div v-if="client.confidential" class="flex items-center justify-between space-x-2">
                            <label for="id"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 flex flex-col space-y-1">
                                <span>Secret</span>
                                <span v-if="plainSecret && showSecret" class="font-normal leading-snug text-muted-foreground">
                                    {{ plainSecret }}
                                </span>
                                <span v-else class="font-normal leading-snug text-muted-foreground">
                                    {{ mask_text(plainSecret || client.secret, 1) }}
                                </span>
                            </label>
                            <div>
                                <div v-if="plainSecret" class="flex items-center space-x-2">
                                    <Switch id="plainSecret" :model-value="showSecret" v-on:update:model-value="toggleSecret" />
                                    <label for="plainSecret"
                                        class="sr-only text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Show
                                    </label>
                                </div>
                                <TooltipProvider v-else>
                                    <Tooltip>
                                        <TooltipTrigger as-child>
                                            <Info class="w-4 h-5 text-muted-foreground" />
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <div>
                                                <p>Secret unavailable</p>
                                                <p>
                                                    The client secret was only visible at creation and <br />
                                                    cannot be retrieved. Please generate a new client if needed.
                                                </p>
                                            </div>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <dl>
                    <dt>Coffee</dt>
                    <dd>- black hot drink</dd>
                    <dt>Milk</dt>
                    <dd>- white cold drink</dd>
                </dl> -->
            </div>
        </div>
    </AppLayout>
</template>