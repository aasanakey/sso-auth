<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, UserRound, UsersRound, UserCog, Shield, LayoutPanelLeft, FolderClock, Cog, Database } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Users',
        href: route('users'),
        icon: UserRound
    },
    {
        title: 'Groups',
        href: '/groups',
        icon: UsersRound
    },
    {
        title: 'Roles',
        href: '/roles',
        icon: Shield
    },
    {
        title: 'Clients',
        href: route('clients'),
        icon: LayoutPanelLeft,
    },
    {
        title: 'Authorized Clients',
        href: route('authorized_clients'),
        icon: FolderClock
    },
    {
        title: 'Access Tokens',
        href: route('personal_access_tokens'),
        icon: UserCog
    },
    {
        title: 'Social Providers',
        href: route('social_providers'),
        icon: Database,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
    {
        title: 'Openid Discovery',
        href: route('openid.discovery'),
        icon: Cog
    }
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
