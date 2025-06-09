<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { User } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
interface Props  {
    request:any,
    authToken:string,
    client:Record<string,any>
    user:User,
    scopes:string[],
}
const { request, authToken,client,user,scopes} = defineProps<Props>();
console.log(request.state,authToken,client)
console.log(user,scopes)
const form = useForm({
    client_id: client.id,
    auth_token: authToken,
    state: request?.state
});
const authorize = () => {
    form.post(route('passport.authorizations.approve'))
}

const cancel = () => {
    form.delete(route('passport.authorizations.approve'))
}

</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-400">
        <div class="flex flex-col gap-2 p-4 shadow-xl rounded-md bg-white">
            <p class="font-bold text-2xl">Authorization Request</p>
            <p class="text-xl"><strong>{{ client?.name }}</strong> is requesting permission to access your account</p>
            <div class="flex items-center gap-4 justify-end flex-wrap">
                <Button variant="destructive" @click="cancel" :disabled="form.processing">Cancel</Button>
                <Button variant="secondary" @click="authorize" :disabled="form.processing">Authorize</Button> 
            </div>
        </div>
    </div>
</template>