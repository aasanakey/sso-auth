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
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '../ui/tags-input';

const { client } = defineProps<{ client?: any }>()
const nameInput = ref<HTMLInputElement | null>(null);
//const urlInput = ref<HTMLInputElement | null>(null);

const open = ref<boolean>(false);

const toggleModal = (value: boolean) => open.value = value

const form = useForm({
    name: client?.name || '',
    redirect_uris: client?.redirect_uris || [],
    confidential: client?.confidential || false,
    //type: undefined,
});

const submit = (e: Event) => {
    e.preventDefault();
    if (client) {
        form.patch(route('update_client', { client: client.id }), {
            preserveScroll: true,
            onSuccess: () => closeModal()
        })
    } else {
        form.post(route('clients'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            //onFinish: () => form.reset(),
        });
    }
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
    toggleModal(false)
};
</script>

<template>
    <Button @click="() => toggleModal(true)" variant="default" size="sm">
        <span v-if="client" class="inline-flex items-center gap-1">
            <PenIcon />Edit
        </span>
        <span v-else class="inline-flex items-center gap-1">
            <PlusIcon />Add
        </span>
    </Button>
    <Dialog :open="open" v-on:update:open="(value) => toggleModal(value)">
        <!-- <DialogTrigger as-child>
            <Button variant="default" size="sm">
                <span v-if="client" class="inline-flex items-center gap-1">
                    <PenIcon />Edit
                </span>
                <span v-else class="inline-flex items-center gap-1">
                    <PlusIcon />Add
                </span>
            </Button>
        </DialogTrigger> -->
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ `${client ? 'Update' : 'Add'} Client` }}</DialogTitle>
                <DialogDescription>
                    {{ `${client ? 'Make changes to client here.' : 'Provide client detials to create new client.'}
                    Click save when you're done.` }}
                </DialogDescription>
            </DialogHeader>
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
                        <!-- <Input id="redirect_url" type="url" name="redirect_uris" ref="urlInput"
                            v-model="form.redirect_uris" placeholder="Redirect url" /> -->
                        <TagsInput v-model="form.redirect_uris">
                            <TagsInputItem v-for="item in form.redirect_uris" :key="item" :value="item">
                                <TagsInputItemText />
                                <TagsInputItemDelete />
                            </TagsInputItem>

                            <TagsInputInput placeholder="redirect uris..." />
                        </TagsInput>
                        <InputError :message="form.errors.redirect_uris" />
                    </div>
                    <!-- <div v-if="!client" class="grid gap-2">
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
                    </div> -->
                    <div v-if="!client" class="grid gap-2">
                        <div class="flex items-center space-x-2">
                            <Label for="confidential" class="flex items-center sp ace-x-3">
                                <Checkbox id="confidential" v-model="form.confidential" :tabindex="3" />
                                <span>Confidential</span>
                            </Label>
                        </div>
                        <InputError :message="form.errors.confidential" />
                        <!-- <span class="form-text text-muted-foreground">
                            Require the client to authenticate with a secret. Confidential clients can hold credentials
                            in a secure way without exposing them to unauthorized parties. Public applications, such as
                            native desktop or JavaScript SPA applications, are unable to hold secrets securely.
                        </span> -->
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
