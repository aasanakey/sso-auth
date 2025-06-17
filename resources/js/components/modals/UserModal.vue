<script setup lang="ts">
import { User } from '@/types';
import { Button } from '../ui/button';
import { Dialog, DialogContent } from '../ui/dialog';
import { Label } from '../ui/label';
import { Input } from '../ui/input';
import InputError from '../InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { LoaderCircle, PenIcon, PlusIcon } from 'lucide-vue-next';

interface Props {
    user?: User
}
const open = ref(false);
const { user } = defineProps<Props>();
const form = useForm({
    name: user?.name || '',
    email: user?.email || '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    if (user) {
        form.patch(route('update_user'), {
            preserveScroll: true,
            onSuccess: () => open.value = false
        });
    } else {
        form.post(route('user'), {
            preserveScroll: true,
            onSuccess: () => open.value = false
        });
    }
}
</script>

<template>
    <Button @click="open = true">
        <span v-if="user" class="inline-flex items-center gap-1">
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
                            v-model="form.name" placeholder="Full name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" required autofocus :tabindex="1" autocomplete="email"
                            v-model="form.email" placeholder="email@example.com" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div v-if="!user" class="grid gap-2">
                        <Label for="password">Password</Label>
                        <Input id="password" type="password" required :tabindex="3" autocomplete="new-password"
                            v-model="form.password" placeholder="Password" />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div v-if="!user" class="grid gap-2">
                        <Label for="password_confirmation">Confirm password</Label>
                        <Input id="password_confirmation" type="password" required :tabindex="4"
                            autocomplete="new-password" v-model="form.password_confirmation"
                            placeholder="Confirm password" />
                        <InputError :message="form.errors.password_confirmation" />
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
