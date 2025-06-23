<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { User } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
interface Props {
    request: any,
    authToken: string,
    client: Record<string, any>
    user: User,
    scopes: string[],
}
const { request, authToken, client, user, scopes } = defineProps<Props>();

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
    <!-- <div class="flex min-h-screen items-center justify-center bg-white">
        <div class="flex flex-col gap-2 p-4 shadow-xl rounded-md bg-blue-50">
            <p class="font-bold text-2xl">Authorization Request</p>
            <p class="text-xl"><strong>{{ client?.name }}</strong> is requesting permission to access your account</p>
            <div class="flex items-center gap-4 justify-end flex-wrap">
                <Button type="button" variant="destructive" @click="cancel" :disabled="form.processing">Cancel</Button>
                <Button type="button" variant="outline" @click="authorize" :disabled="form.processing">Continue</Button> 
            </div>
        </div>
    </div> -->
    <div class="container min-h-screen flex items-center justify-center">
        <div class="flex flex-col justify-center">
            <div class="col- md-6">
                <div class="card card- default rounded-lg p-4 shadow">
                    <div class="card-header text-2xl font-medium">
                        Authorization Request
                    </div>
                    <div class="card-body">
                        <!-- Introduction -->
                        <p><strong>{{ client.name }}</strong> is requesting permission to access your account.</p>

                        <!-- Scope List -->
                        <div v-if="scopes.length > 0" class="scopes mt-5">
                            <p><strong>This application will be able to:</strong></p>

                            <ul v-for="scope in scopes as any[]">
                                <li>{{ scope.description }}</li>
                            </ul>
                        </div>

                        <div class="buttons flex items-center justify-center gap-1 mt-6">
                            <!-- Authorize Button -->
                            <Button type="submit" @click="authorize" :disabled="form.processing" class="btn btn-success btn-approve">Authorize</button>

                            <!-- Cancel Button -->
                            <Button type="button" variant="destructive" @click="cancel" :disabled="form.processing" class="btn btn-danger">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>