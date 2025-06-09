<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Components
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { PlusIcon, PenIcon, LoaderCircle } from "lucide-vue-next"
import { Label } from '../ui/label';
import { Input } from '../ui/input';
import { Checkbox } from '../ui/checkbox';

const { client } = defineProps<{ client?: any }>()
const nameInput = ref<HTMLInputElement | null>(null);
const urlInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    name: client?.name || '',
    redirect_url: client?.redirect_url || '',
    confidential: client?.confidential || false,
    type: '',
});

const submit = (e: Event) => {
    e.preventDefault();

    form.post(route('clients'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        //onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>

    <Dialog>
        <DialogTrigger as-child>
            <Button variant="default" size="sm">
                <span v-if="client" class="inline-flex items-center gap-1">
                    <PenIcon />Edit
                </span>
                <span v-else class="inline-flex items-center gap-1">
                    <PlusIcon />Add
                </span>
            </Button>
        </DialogTrigger>
        <DialogContent>
            <div class="pt-6">
                <form class="space-y-6" @submit="submit">

                    <div class="grid gap-2">
                        <Label for="name" class="sr-only">Name</Label>
                        <Input id="name" type="text" name="name" ref="nameInput" v-model="form.name"
                            placeholder="Client name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="redirect_url" class="sr-only">Redirect url</Label>
                        <Input id="redirect_url" type="url" name="redirect_url" ref="urlInput"
                            v-model="form.redirect_url" placeholder="Redirect url" />
                        <InputError :message="form.errors.redirect_url" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="redirect_url" class="sr-only">Client Type</Label>
                        <select v-model="form.type"
                            class="placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive">
                            <option></option>
                            <option value="client">Client</option>
                            <option value="personal">Personal</option>
                            <option value="password">Password</option>
                            <option value="implicit">Implicit</option>
                            <option value="device">device</option>
                        </select>
                        <InputError :message="form.errors.type" />
                    </div>
                    <div class="grid gap-2">
                        <div class="flex items-center space-x-2">
                            <Label for="confidential" class="flex items-center sp ace-x-3">
                                <Checkbox id="confidential" v-on:update:model-value="form.confidential" :tabindex="3" />
                                <span>Confidential</span>
                            </Label>
                        </div>
                        <InputError :message="form.errors.confidential" />
                    </div>
                    <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Save
                    </Button>
                </form>
            </div>
        </DialogContent>
    </Dialog>

</template>
