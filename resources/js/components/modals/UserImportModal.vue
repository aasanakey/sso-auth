<script setup lang="ts">
import { Button } from '../ui/button';
import { Dialog, DialogContent } from '../ui/dialog';
import { Label } from '../ui/label';
import { Input } from '../ui/input';
import InputError from '../InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { LoaderCircle, PlusIcon } from 'lucide-vue-next';


const open = ref(false);
const form = useForm<{file:File|null}>({
    file: null,
})

const submit = () => {

    form.post(route('user.import'), {
        preserveScroll: true,
        onSuccess: () => open.value = false
    });

}
</script>

<template>
    <Button @click="open = true">
            <PlusIcon /> Import Users
    </Button>
    <Dialog :open="open" v-on:update:open="value => open = value">
        <DialogContent>
            <div class="pt-6">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-2">
                        <Label for="file">Upload CSV</Label>
                        <Input id="file" type="file" required autofocus :tabindex="1" autocomplete="name"
                            @change="(e: Event) => form.file = (e.target as HTMLInputElement).files![0]" placeholder="Upload CSV file" />
                        <InputError :message="form.errors.file" />
                    </div>
                    <div>
                        <a href="/user_import_template.csv" target="_blank" class="underline text-sm hover:text-blue-500">Download template</a>
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
