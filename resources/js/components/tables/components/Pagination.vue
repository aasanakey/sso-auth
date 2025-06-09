<script setup lang="ts">
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'
const {total, pageSize, currentPage = 1} = defineProps<{
    total:number,
    currentPage:number,
    pageSize: number
}>()
</script>

<template>
  <Pagination v-slot="{ page }" :items-per-page="pageSize" :total="total" :default-page="currentPage">
    <PaginationContent v-slot="{ items }">
      <PaginationPrevious />

      <template v-for="(item, index) in items" :key="index">
        <PaginationItem
          v-if="item.type === 'page'"
          :value="item.value"
          :is-active="item.value === page"
        >
          {{ item.value }}
        </PaginationItem>
        <PaginationEllipsis v-if="item.type === 'ellipsis'" :index="index" />
      </template>

      <!-- <PaginationEllipsis :index="4" /> -->

      <PaginationNext />
    </PaginationContent>
  </Pagination>
</template>