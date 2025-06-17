<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AlertInfo from './AlertInfo.vue';
import AlertWarning from './AlertWarning.vue';
import AlertDanger from './AlertDanger.vue';
import AlertSuccess from './AlertSuccess.vue';

const { flash } = usePage().props as Record<string,any>

</script>

<template>
  <div class="flex flex-col p-4">
    <AlertSuccess v-if="flash.success" :message="flash.success" />
    <AlertDanger v-if="flash.error" :message="flash.error" />
    <AlertWarning v-if="flash.warning" :message="flash.warning" />
    <AlertInfo v-if="flash.info" :message="flash.info" />
  </div>
</template>