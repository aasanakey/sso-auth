<script setup lang="ts">
import { Link2, Link2Off } from 'lucide-vue-next';
import HeadingSmall from './HeadingSmall.vue';
interface Props {
    linked: any[],
    unlinked: any[]
}
const { linked, unlinked } = defineProps<Props>()
console.log(linked, unlinked)
//const drivers = ['facebook', 'x', 'linkedin-openid', 'google', 'github'];

</script>

<template>
    <div class="space-y-6">
        <HeadingSmall title="OAuth Providers" description="OAuth providers linked to your account are listed here" />
        <div v-if="linked.length > 0"
            class="space-y-4 rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-200/10 dark:bg-gray-700/10">
            <div v-for="account in linked" class="flex items-center justify-between relative space-y-0.5 text-gray-600 dark:text-gray-100">
                <p class="font-medium inline-flex items-center gap-1.5 capitalize">
                    <svg v-if="account.driver == 'github'" viewBox="0 0 15 15" width="1.2em" height="1.2em"
                        class="mr-2 h-4 w-4">
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M7.5.25a7.25 7.25 0 0 0-2.292 14.13c.363.066.495-.158.495-.35c0-.172-.006-.628-.01-1.233c-2.016.438-2.442-.972-2.442-.972c-.33-.838-.805-1.06-.805-1.06c-.658-.45.05-.441.05-.441c.728.051 1.11.747 1.11.747c.647 1.108 1.697.788 2.11.602c.066-.468.254-.788.46-.969c-1.61-.183-3.302-.805-3.302-3.583a2.8 2.8 0 0 1 .747-1.945c-.075-.184-.324-.92.07-1.92c0 0 .61-.194 1.994.744A7 7 0 0 1 7.5 3.756A7 7 0 0 1 9.315 4c1.384-.938 1.992-.743 1.992-.743c.396.998.147 1.735.072 1.919c.465.507.745 1.153.745 1.945c0 2.785-1.695 3.398-3.31 3.577c.26.224.492.667.492 1.343c0 .97-.009 1.751-.009 1.989c0 .194.131.42.499.349A7.25 7.25 0 0 0 7.499.25"
                            clip-rule="evenodd"></path>
                    </svg>

                    <svg v-if="account.driver == 'google'" role="img" viewBox="0 0 24 24" class="mr-2 h-4 w-4">
                        <path fill="currentColor"
                            d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z">
                        </path>
                    </svg>

                    <svg v-if="account.driver == 'facebook'" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 896 1664">
                        <path fill="currentColor"
                            d="M895 12v264H738q-86 0-116 36t-30 108v189h293l-39 296H592v759H286V905H31V609h255V391q0-186 104-288.5T667 0q147 0 228 12" />
                    </svg>

                    <svg v-if="account.driver == 'x'" class="m-0 p-0" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 14 14">
                        <g fill="none">
                            <g clip-path="url(#primeTwitter0)">
                                <path fill="currentColor"
                                    d="M11.025.656h2.147L8.482 6.03L14 13.344H9.68L6.294 8.909l-3.87 4.435H.275l5.016-5.75L0 .657h4.43L7.486 4.71zm-.755 11.4h1.19L3.78 1.877H2.504z" />
                            </g>
                            <defs>
                                <clipPath id="primeTwitter0">
                                    <path fill="#fff" d="M0 0h14v14H0z" />
                                </clipPath>
                            </defs>
                        </g>
                    </svg>

                    <svg v-if="account.driver == 'linkedin-openid' || account.driver == 'linkedin'"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93zM6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37z" />
                    </svg>
                    {{ account?.driver }}
                </p>
                <a :href="route('social.auth.unlink', { provider: account.driver })" class="text-sm" title="unlink account">
                    <Link2Off />
                </a>
            </div>
        </div>
        <div v-if="unlinked.length > 0"
            class="space-y-4 rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-200/10 dark:bg-gray-700/10">
            <div v-for="account in unlinked" class="flex items-center justify-between relative space-y-2 text-gray-600 dark:text-gray-100">
                <p class="font-medium inline-flex items-center gap-1.5 capitalize">
                    <svg v-if="account.driver == 'github'" viewBox="0 0 15 15" width="1.2em" height="1.2em"
                        class="mr-2 h-4 w-4">
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M7.5.25a7.25 7.25 0 0 0-2.292 14.13c.363.066.495-.158.495-.35c0-.172-.006-.628-.01-1.233c-2.016.438-2.442-.972-2.442-.972c-.33-.838-.805-1.06-.805-1.06c-.658-.45.05-.441.05-.441c.728.051 1.11.747 1.11.747c.647 1.108 1.697.788 2.11.602c.066-.468.254-.788.46-.969c-1.61-.183-3.302-.805-3.302-3.583a2.8 2.8 0 0 1 .747-1.945c-.075-.184-.324-.92.07-1.92c0 0 .61-.194 1.994.744A7 7 0 0 1 7.5 3.756A7 7 0 0 1 9.315 4c1.384-.938 1.992-.743 1.992-.743c.396.998.147 1.735.072 1.919c.465.507.745 1.153.745 1.945c0 2.785-1.695 3.398-3.31 3.577c.26.224.492.667.492 1.343c0 .97-.009 1.751-.009 1.989c0 .194.131.42.499.349A7.25 7.25 0 0 0 7.499.25"
                            clip-rule="evenodd"></path>
                    </svg>

                    <svg v-if="account.driver == 'google'" role="img" viewBox="0 0 24 24" class="mr-2 h-4 w-4">
                        <path fill="currentColor"
                            d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z">
                        </path>
                    </svg>

                    <svg v-if="account.driver == 'facebook'" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 896 1664">
                        <path fill="currentColor"
                            d="M895 12v264H738q-86 0-116 36t-30 108v189h293l-39 296H592v759H286V905H31V609h255V391q0-186 104-288.5T667 0q147 0 228 12" />
                    </svg>

                    <svg v-if="account.driver == 'x'" class="m-0 p-0" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 14 14">
                        <g fill="none">
                            <g clip-path="url(#primeTwitter0)">
                                <path fill="currentColor"
                                    d="M11.025.656h2.147L8.482 6.03L14 13.344H9.68L6.294 8.909l-3.87 4.435H.275l5.016-5.75L0 .657h4.43L7.486 4.71zm-.755 11.4h1.19L3.78 1.877H2.504z" />
                            </g>
                            <defs>
                                <clipPath id="primeTwitter0">
                                    <path fill="#fff" d="M0 0h14v14H0z" />
                                </clipPath>
                            </defs>
                        </g>
                    </svg>

                    <svg v-if="account.driver == 'linkedin-openid' || account.driver == 'linkedin'"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93zM6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37z" />
                    </svg>
                    {{ account?.driver }}
                </p>
                <a :href="route('social.auth.redirect', { provider: account.driver })" class="text-sm" title="unlink account">
                    <Link2 />
                </a>
            </div>
        </div>
    </div>
</template>