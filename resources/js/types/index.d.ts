import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    flash: {
        success?: string,
        error?: string,
        info?: string,
        warning?: string
    };
}

export interface User {
    id: string;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    groups?: Group[]
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface SocialProvider {
    id:number,
    driver:string,
    client_id:string,
    client_secret:string
    redirect_uri?: string
    created_at:string
}

export interface Group {
    id: number;
    name: string;
    created_at?: string;
    updated_at?: string;
    users?: User[]
}