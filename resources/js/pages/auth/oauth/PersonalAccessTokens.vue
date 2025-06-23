<style scoped>
.action-link {
    cursor: pointer;
}
</style>
<script setup lang="ts">
import { ref, onMounted, nextTick } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

const accessToken = ref(null)
const tokens = ref<any[]>([])
const scopes = ref<any>([])

const form = ref<{name:string,scopes:any[],errors:any[]}>({
    name: '',
    scopes: [],
    errors: []
})

const showModal = ref(false)

const getTokens = async () => {
    try {
        const response = await fetch('/oauth/personal-access-tokens')
        const data = await response.json()
        tokens.value = data
    } catch (err) {
        console.error(err)
    }
}

const getScopes = async () => {
    try {
        const response = await fetch('/oauth/scopes')
        const data = await response.json()
        scopes.value = data
    } catch (err) {
        console.error(err)
    }
}

const showCreateTokenForm = async () => {
    showModal.value = true
    await nextTick()
    const input = document.getElementById('create-token-name')
    if (input) input.focus()
}

const showAccessToken = (tokenValue:any) => {
    accessToken.value = tokenValue
}

const store = async () => {
    accessToken.value = null
    form.value.errors = []

    try {
        const response = await fetch('/oauth/personal-access-tokens', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: form.value.name,
                scopes: form.value.scopes
            })
        })

        if (!response.ok) {
            const errorData = await response.json()
            if (errorData.errors) {
                form.value.errors = Object.values(errorData.errors).flat()
            } else {
                form.value.errors = ['Something went wrong. Please try again.']
            }
            return
        }

        const result = await response.json()
        form.value.name = ''
        form.value.scopes = []
        tokens.value.push(result.token)
        showAccessToken(result.accessToken)
        showModal.value = false
    } catch (error) {
        console.error(error)
        form.value.errors = ['Something went wrong. Please try again.']
    }
}

const revoke = async (token:any) => {
    try {
        await fetch(`/oauth/personal-access-tokens/${token.id}`, { method: 'DELETE' })
        await getTokens()
    } catch (err) {
        console.error(err)
    }
}

const toggleScope = (scope:any) => {
    const index = form.value.scopes.indexOf(scope)
    if (index >= 0) {
        form.value.scopes.splice(index, 1)
    } else {
        form.value.scopes.push(scope)
    }
}

const scopeIsAssigned = (scope:any) => {
    return form.value.scopes.includes(scope)
}

onMounted(() => {
    getTokens()
    getScopes()
})
</script>
<template>
    <AppLayout>
        <div>
            <div>
                <div class="card card-default">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                Personal Access Tokens
                            </span>

                            <a class="action-link" tabindex="-1" @click="showCreateTokenForm">
                                Create New Token
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- No Tokens Notice -->
                        <p class="mb-0" v-if="tokens.length === 0">
                            You have not created any personal access tokens.
                        </p>

                        <!-- Personal Access Tokens -->
                        <table class="table table-borderless mb-0" v-if="tokens.length > 0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="token in tokens">
                                    <!-- Client Name -->
                                    <td style="vertical-align: middle;">
                                        {{ token.name }}
                                    </td>

                                    <!-- Delete Button -->
                                    <td style="vertical-align: middle;">
                                        <a class="action-link text-danger" @click="revoke(token)">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Create Token Modal -->
            <div class="modal fade" id="modal-create-token" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Create Token
                            </h4>

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>

                        <div class="modal-body">
                            <!-- Form Errors -->
                            <div class="alert alert-danger" v-if="form.errors.length > 0">
                                <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                                <br>
                                <ul>
                                    <li v-for="error in form.errors">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>

                            <!-- Create Token Form -->
                            <form role="form" @submit.prevent="store">
                                <!-- Name -->
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label">Name</label>

                                    <div class="col-md-6">
                                        <input id="create-token-name" type="text" class="form-control" name="name"
                                            v-model="form.name">
                                    </div>
                                </div>

                                <!-- Scopes -->
                                <div class="form-group row" v-if="scopes.length > 0">
                                    <label class="col-md-4 col-form-label">Scopes</label>

                                    <div class="col-md-6">
                                        <div v-for="scope in scopes">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" @click="toggleScope(scope.id)"
                                                        :checked="scopeIsAssigned(scope.id)">

                                                    {{ scope.id }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Modal Actions -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                            <button type="button" class="btn btn-primary" @click="store">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Access Token Modal -->
            <div class="modal fade" id="modal-access-token" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Personal Access Token
                            </h4>

                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>

                        <div class="modal-body">
                            <p>
                                Here is your new personal access token. This is the only time it will be shown so don't
                                lose it!
                                You may now use this token to make API requests.
                            </p>

                            <textarea class="form-control" rows="10">{{ accessToken }}</textarea>
                        </div>

                        <!-- Modal Actions -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<!-- 
<script>
import AppLayout from '@/layouts/AppLayout.vue';

    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                accessToken: null,

                tokens: [],
                scopes: [],

                form: {
                    name: '',
                    scopes: [],
                    errors: []
                }
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTokens();
                this.getScopes();

                $('#modal-create-token').on('shown.bs.modal', () => {
                    $('#create-token-name').focus();
                });
            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                        .then(response => {
                            this.scopes = response.data;
                        });
            },

            /**
             * Show the form for creating new tokens.
             */
            showCreateTokenForm() {
                $('#modal-create-token').modal('show');
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                        .then(response => {
                            this.form.name = '';
                            this.form.scopes = [];
                            this.form.errors = [];

                            this.tokens.push(response.data.token);

                            this.showAccessToken(response.data.accessToken);
                        })
                        .catch(error => {
                            if (typeof error.response.data === 'object') {
                                this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                            } else {
                                this.form.errors = ['Something went wrong. Please try again.'];
                            }
                        });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = _.reject(this.form.scopes, s => s == scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return _.indexOf(this.form.scopes, scope) >= 0;
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                $('#modal-create-token').modal('hide');

                this.accessToken = accessToken;

                $('#modal-access-token').modal('show');
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script> -->
