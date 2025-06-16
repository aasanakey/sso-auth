<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog, DialogHeader, DialogContent, DialogDescription, DialogFooter, DialogTitle
} from '@/components/ui/dialog';
import { Trash } from 'lucide-vue-next';
import { ref } from 'vue';

const open = ref(false)
defineEmits(['delete']);
const { label, title, description } = defineProps<{ label?: string, title?: string, description: string }>()
const toggle = () => {
    open.value = !open.value;
}
</script>

<template>
    <Button @click="open = true" size="sm" variant="destructive" class="inline-flex gap-2 items-center p-1.5">
        <Trash /> {{ label }}
    </Button>
    <Dialog :open="open" v-on:update:open="toggle">
        <DialogContent>

            <DialogHeader class="space-y-3">
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ description }}</DialogDescription>
            </DialogHeader>
            <DialogFooter class="gap-2">
                <Button variant="secondary" @click="toggle"> Cancel </Button>

                <Button type="button" variant="destructive" @click="$emit('delete')">
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>