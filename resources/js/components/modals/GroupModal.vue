<script setup lang="ts">
import { Group } from '@/types';
import { Button } from '../ui/button';
import { Dialog, DialogContent } from '../ui/dialog';
import { Label } from '../ui/label';
import { Input } from '../ui/input';
import InputError from '../InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { LoaderCircle, PenIcon, PlusIcon } from 'lucide-vue-next';

interface Props {
    group?: Group
}
const open = ref(false);
const { group } = defineProps<Props>();
const form = useForm({
    name: group?.name || '',
})

const submit = () => {
    if (group) {
        form.patch(route('groups.update'), {
            preserveScroll: true,
            onSuccess: () => open.value = false
        });
    } else {
        form.post(route('groups'), {
            preserveScroll: true,
            onSuccess: () => open.value = false
        });
    }
}
</script>

<template>
    <Button @click="open = true">
        <span v-if="group" class="inline-flex items-center gap-1">
            <PenIcon />Edit
        </span>
        <span v-else class="inline-flex items-center gap-1">
            <PlusIcon />Add
        </span>
    </Button>
    <Dialog :open="open" v-on:update:open="value => open = value">
        <DialogContent>
            <div class="pt-6">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                            v-model="form.name" placeholder="group name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Save
                    </Button>
                </form>
            </div>
        </DialogContent>
    </Dialog>
</template>
