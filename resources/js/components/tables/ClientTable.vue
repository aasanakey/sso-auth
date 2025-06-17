<script setup lang="ts">
import { onMounted, ref, computed, h } from 'vue'
import type { ColumnDef } from '@tanstack/vue-table'
import DataTable from './components/DataTable.vue'
import { User } from '@/types';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '../ui/dropdown-menu';
import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '../ui/button';
import DeleteAction from './components/DeleteAction.vue';
import ClientModal from '../modals/ClientModal.vue';


interface Props {
    columns?: [];
}
const form = useForm({});
const props = defineProps<Props>();
const deleteClient = (id: any) => {
    form.delete(route('clients.destroy', { client: id }), {
        preserveScroll: true,
        //onSuccess: () => ,
    })
}

const defaultColumns: ColumnDef<User>[] = [
    {
        header: 'ID',
        accessorKey: 'id',
    },
    {
        header: 'Name',
        accessorKey: 'name',
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
            const client = row.original
            return h('div', { class: 'flex items-center gap-2' }, [
                h(Link, {
                    href: route('show_client',{'client':client.id}),
                    class: "inline-flex items-center text-blue-500"
                }, () => "view"), 
                h(ClientModal, {client:client}),
                //@ts-ignore
                h(DeleteAction, {
                    label: 'Delete',
                    title: 'Delete Client',
                    description: 'This action will delete your client permanently. This cannot be undone.',
                    onDelete: () => deleteClient(client.id)
                })
            ])
        },
    }
];
const columns = computed(() => props.columns ?? defaultColumns)
const page = usePage();
const clients = computed<any[]>(() => page.props.clients as any[] || [])
</script>

<template>
    <div class="container py-10 mx-auto">
        <DataTable :columns="columns" :data="clients" />
    </div>
</template>