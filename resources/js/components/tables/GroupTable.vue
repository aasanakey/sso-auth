<script setup lang="ts">
import { computed, h } from 'vue'
import type { ColumnDef } from '@tanstack/vue-table'
import DataTable from './components/DataTable.vue'
import { Group } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';



interface Props {
    columns?: [];
}

const props = defineProps<Props>();

const defaultColumns: ColumnDef<Group>[] = [
    {
        header: 'ID',
        accessorKey: 'id',
    },
    {
        header: 'Name',
        accessorKey: 'name',
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
            const group = row.original

            return h('div',{cass:'flex items-center gap-2'},[
                h(Link,{href:route('groups.show',{group:group.id}), class:'text-blue-500'},() =>'view')
            ])
        },
    }
];
const columns = computed(() => props.columns ?? defaultColumns)
const page = usePage();
const groups = computed<Group[]>(() => page.props.groups as Group[])
</script>

<template>
    <div class="container py-10 mx-auto">
        <DataTable :columns="columns" :data="groups" />
    </div>
</template>