<script setup lang="ts">
import { SocialProvider } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '../ui/button';
import { LoaderCircle, PenIcon, PlusIcon } from 'lucide-vue-next';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '../ui/dialog';
import { Label } from '../ui/label';
import { Input } from '../ui/input';
import InputError from '../InputError.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '../ui/select';

interface Props {
    provider?: SocialProvider
}
const open = ref(false);

const toggleModal = (value: boolean) => open.value = value

const { provider } = defineProps<Props>();
const { drivers }  = usePage().props as any

const form = useForm({
    driver: provider?.driver || '',
    client_id: provider?.client_id || '',
    client_secret: provider?.client_secret || '',
    redirect_uri: provider?.redirect_uri || '',
})

const submit = () => {
    if (provider) {
        form.patch(route('social_providers.update',{provider:provider.id}), {
            preserveScroll: true,
            onSuccess: () => toggleModal(false)
        });
    } else {
        form.post(route('social_providers.store'), {
            preserveScroll: true,
            onSuccess: () => toggleModal(false)
        });
    }
}
</script>

<template>
    <Button @click="open = true">
        <span v-if="provider" class="inline-flex items-center gap-1">
            <PenIcon />Edit
        </span>
        <span v-else class="inline-flex items-center gap-1">
            <PlusIcon />Add
        </span>
    </Button>
    <Dialog :open="open" v-on:update:open="value => toggleModal(value)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ `${provider ? 'Update' : 'Add'} Provider` }}</DialogTitle>
                <DialogDescription>
                    {{ `${provider ? 'Make changes to provider here.' : 'Provide social provider detials to add new social login provider.'}
                    Click save when you're done.` }}
                </DialogDescription>
            </DialogHeader>
            <div class="pt-6">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-2">
                        <Label for="driver">Provider name</Label>
                        <Select v-model="form.driver" autofocus required>
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select a provider" />
                            </SelectTrigger>
                            <SelectContent >
                                    <SelectItem v-for="(driver, index) in drivers" :key="index" :value="driver">
                                        {{driver}}
                                    </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.driver" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="client_id">Client ID</Label>
                        <Input id="client_id" type="text" required autofocus :tabindex="1" autocomplete="client_id"
                            v-model="form.client_id" placeholder="Client ID" />
                        <InputError :message="form.errors.client_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="client_secret">Client Secret</Label>
                        <Input id="client_secret" type="text" required autofocus :tabindex="1"
                            autocomplete="client_secret" v-model="form.client_secret" placeholder="Client Secret" />
                        <InputError :message="form.errors.client_secret" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="redirect_uri">Redirect Uri</Label>
                        <Input id="redirect_uri" type="text" required autofocus :tabindex="1"
                            autocomplete="redirect_uri" v-model="form.redirect_uri" placeholder="Redirect uri" />
                        <InputError :message="form.errors.redirect_uri" />
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