<script setup lang="ts" generic="TData, TValue">
import type {
  ColumnDef,
  ColumnFiltersState,
  ExpandedState,
  SortingState,
  VisibilityState,
} from '@tanstack/vue-table'
import {
  FlexRender,
  getCoreRowModel,
  getExpandedRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useVueTable,
} from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'

import { computed, h, ref, shallowRef } from 'vue'
import { valueUpdater } from '@/lib/valueUpdater'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import Pagination from './Pagination.vue'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/components/ui/select'
const props = defineProps<{
  columns: ColumnDef<TData, TValue>[]
  data: TData[]
}>()
const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})
const expanded = ref<ExpandedState>({})
const pageSize = ref(10);
const pagination = computed(() => ({ pageIndex: 0, pageSize: pageSize.value }))
const globalFilter = ref('')

const table = useVueTable({
  get data() { return props.data },
  get columns() { return props.columns },
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  getExpandedRowModel: getExpandedRowModel(),
  onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
  onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
  onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
  onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
  onExpandedChange: updaterOrValue => valueUpdater(updaterOrValue, expanded),
  onGlobalFilterChange: (updater) => {
    globalFilter.value = typeof updater === 'function' ? updater(globalFilter.value) : updater;
  },
  state: {
    get sorting() { return sorting.value },
    get columnFilters() { return columnFilters.value },
    get columnVisibility() { return columnVisibility.value },
    get rowSelection() { return rowSelection.value },
    get expanded() { return expanded.value },
    get pagination() { return pagination.value },
    get globalFilter() { return globalFilter.value },
  },
})

const currentPage = computed(() => table.getState().pagination.pageIndex + 1)
</script>

<template>
  <div class="w-full">
    <div class="flex gap-2 items-center  justify-between py-4">
      <Input class="max-w-52" placeholder="Search all columns..." v-model="globalFilter" />
      <div class="flex items-center gap-1">
        <Button>
          Export
        </Button>
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="outline" class="ml-auto">
              Columns
              <ChevronDown class="ml-2 h-4 w-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuCheckboxItem v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
              :key="column.id" class="capitalize" :model-value="column.getIsVisible()" @update:model-value="(value) => {
                column.toggleVisibility(!!value)
              }">
              {{ column.id }}
            </DropdownMenuCheckboxItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>
    <div class="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <TableHead v-for="header in headerGroup.headers" :key="header.id">
              <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                :props="header.getContext()" />
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <template v-if="table.getRowModel().rows?.length">
            <TableRow v-for="row in table.getRowModel().rows" :key="row.id"
              :data-state="row.getIsSelected() ? 'selected' : undefined">
              <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
              </TableCell>
            </TableRow>
          </template>
          <template v-else>
            <TableRow>
              <TableCell :colspan="columns.length" class="h-24 text-center">
                No results.
              </TableCell>
            </TableRow>
          </template>
        </TableBody>
      </Table>
    </div>

    <div v-if="table.getFilteredRowModel().rows.length > 0" class="flex items-center justify-between space-x-2 py-4">
      <div class="flex flex-col items-center">
        <div class="flex-1 text-sm text-muted-foreground">
          Showing&nbsp;
          <span class="font-semibold firstItem">{{ table.getState().pagination.pageSize * (currentPage - 1) +
            1 }}</span>
          &nbsp;to&nbsp;
          <span class="font-semibold lastItem">
            {{ (table.getRowCount() > table.getState().pagination.pageSize * currentPage)
              ?
              table.getState().pagination.pageSize * currentPage
              : table.getRowCount()
            }}
          </span>
          &nbsp;of&nbsp;
          <span class="font-semibold total">{{ table.getRowCount() }}</span>
          &nbsp;Results
        </div>
        <Pagination :pageSize="table.getState().pagination.pageSize" :currentPage="currentPage"
          :total="table.getPageCount()" />
      </div>
      <div class="space-x-2">

        <Select :model="pageSize" @update:model-value="table.setPageSize(Number($event))">
          <SelectTrigger class="w-fit">
            <SelectValue :placeholder="pageSize.toString()" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem :value="10">10</SelectItem>
            <SelectItem :value="25">25</SelectItem>
            <SelectItem :value="50">50</SelectItem>
            <SelectItem :value="100">100</SelectItem>
            <SelectItem :value="table.getRowCount()">All</SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>
  </div>
</template>