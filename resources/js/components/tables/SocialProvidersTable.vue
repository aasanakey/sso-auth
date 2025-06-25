<script setup lang="ts">
import { onMounted, ref, computed, h } from 'vue'
import type { ColumnDef } from '@tanstack/vue-table'
import DataTable from './components/DataTable.vue'
import { SocialProvider, User } from '@/types';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import DeleteAction from './components/DeleteAction.vue';
import ClientModal from '../modals/ClientModal.vue';
import { SocialProviderModal } from '../modals';


interface Props {
    columns?: [];
}
const form = useForm({});
const props = defineProps<Props>();
const deleteProvider = (id: any) => {
    form.delete(route('social_providers.destroy', { provider: id }), {
        preserveScroll: true,
        //onSuccess: () => ,
    })
}

const defaultColumns: ColumnDef<SocialProvider>[] = [
    {
        header: 'ID',
        accessorKey: 'id',
    },
    {
        header: 'Driver',
        accessorKey: 'driver',
    },
    // {
    //     header: 'Owner Type',
    //     accessorKey: 'owner_type',
    // },
    {
        header: 'Created at',
        accessorKey: 'created_at',
        cell: ({ row }) => row.original?.created_at ? Intl.DateTimeFormat('en-GH', { dateStyle: "long" }).format(new Date(row.original?.created_at)) : row.original?.created_at
    },
    {
        id: 'actions',
        header: 'Actions',
        enableHiding: false,
        cell: ({ row }) => {
            const provider = row.original
            return h('div', { class: 'flex items-center gap-2' }, [
                // h(Link, {
                //     href: route('show_client',{'client':provider.id}),
                //     class: "inline-flex items-center text-blue-500"
                // }, () => "view"), 
                h(SocialProviderModal, {provider:provider}),
                //@ts-ignore
                h(DeleteAction, {
                    label: 'Delete',
                    title: 'Delete Social Provider',
                    description: `This action will delete your ${provider?.driver} provider permanently. This cannot be undone.`,
                    onDelete: () => deleteProvider(provider.id)
                })
            ])
        },
    }
];
const columns = computed(() => props.columns ?? defaultColumns)
const page = usePage();
const providers = computed<any[]>(() => page.props.providers as any[] || [])
</script>

<template>
    <div class="container py-10 mx-auto">
        <DataTable :columns="columns" :data="providers" />
    </div>
</template>