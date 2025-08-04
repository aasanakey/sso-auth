<script setup lang="ts">
import { computed, h } from 'vue'
import type { ColumnDef } from '@tanstack/vue-table'
import DataTable from './components/DataTable.vue'
import { Group, User } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';



interface Props {
    columns?: [];
}

const props = defineProps<Props>();

const defaultColumns: ColumnDef<User>[] = [
    {
        header: 'ID',
        accessorKey: 'id',
    },
    {
        header: 'Name',
        accessorKey: 'name',
    },
    {
        header: 'Email',
        accessorKey: 'email',
    },
    {
        header: 'Created_at',
        accessorKey: 'created_at',
        cell: ({ row }) => row.original?.created_at ? Intl.DateTimeFormat('en-GH', { dateStyle: "long" }).format(new Date(row.original?.created_at)) : row.original?.created_at
    },
    {
        id: 'actions',
        header: 'Actions',
        enableHiding: false,
        cell: ({ row }) => {
            const user = row.original

            return h('div',{cass:'flex items-center gap-2'},[
                h(Link,{href:route('show_user',{user:user.id}), class:'text-blue-500'},() =>'view')
            ])
        },
    }
];
const columns = computed(() => props.columns ?? defaultColumns)
const page = usePage<{
    group: Group
}>();
const users = computed<User[]>(() => page.props.group?.users as User[])
</script>

<template>
    <div class="container py-10 mx-auto">
        <DataTable :columns="columns" :data="users" />
    </div>
</template>