<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, files } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Download, FolderPlus, Search, Share2, Upload } from 'lucide-vue-next';
import Tree from 'primevue/tree';
import { ref } from 'vue';

// Dummy data for the tree
const nodes = ref([
    {
        key: '0',
        label: 'Documents',
        data: 'Documents Folder',
        icon: 'pi pi-fw pi-inbox',
        children: [
            {
                key: '0-0',
                label: 'Work',
                data: 'Work Folder',
                icon: 'pi pi-fw pi-cog',
                children: [
                    {
                        key: '0-0-0',
                        label: 'Expenses.doc',
                        icon: 'pi pi-fw pi-file',
                        data: 'Expenses Document',
                    },
                    {
                        key: '0-0-1',
                        label: 'Resume.doc',
                        icon: 'pi pi-fw pi-file',
                        data: 'Resume Document',
                    },
                ],
            },
            {
                key: '0-1',
                label: 'Home',
                data: 'Home Folder',
                icon: 'pi pi-fw pi-home',
                children: [
                    {
                        key: '0-1-0',
                        label: 'Invoices.txt',
                        icon: 'pi pi-fw pi-file',
                        data: 'Invoices for this month',
                    },
                ],
            },
        ],
    },
    {
        key: '1',
        label: 'Pictures',
        data: 'Pictures Folder',
        icon: 'pi pi-fw pi-image',
        children: [
            {
                key: '1-0',
                label: 'barcelona.jpg',
                icon: 'pi pi-fw pi-image',
                data: 'Barcelona Photo',
            },
            {
                key: '1-1',
                label: 'logo.jpg',
                icon: 'pi pi-fw pi-image',
                data: 'PrimeFaces Logo',
            },
            {
                key: '1-2',
                label: 'primeui.png',
                icon: 'pi pi-fw pi-image',
                data: 'PrimeUI Logo',
            },
        ],
    },
]);

const selectedKey = ref(null);
const loading = ref(false);
const searchQuery = ref('');

const expandAll = () => {
    // Logic to expand all nodes
};

const collapseAll = () => {
    // Logic to collapse all nodes
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Files',
        href: files().url,
    },
];
</script>

<template>
    <Head title="File Manager" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="flex flex-col gap-6 lg:flex-row">
                <!-- Main Content: Tree View -->
                <div
                    class="flex-1 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800"
                >
                    <div
                        class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-200 p-4 dark:border-gray-700"
                    >
                        <h2
                            class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            Explorer
                        </h2>

                        <!-- Actions Toolbar -->
                        <div class="flex items-center gap-2">
                            <button
                                class="flex items-center gap-2 rounded-md bg-blue-50 px-3 py-1.5 text-sm text-blue-600 transition-colors hover:bg-blue-100"
                            >
                                <FolderPlus class="h-4 w-4" />
                                New Folder
                            </button>
                            <button
                                class="flex items-center gap-2 rounded-md bg-green-50 px-3 py-1.5 text-sm text-green-600 transition-colors hover:bg-green-100"
                            >
                                <Upload class="h-4 w-4" />
                                Upload
                            </button>

                            <div
                                class="mx-1 h-4 w-px bg-gray-300 dark:bg-gray-600"
                            ></div>

                            <button
                                class="rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50"
                                :disabled="!selectedKey"
                            >
                                <Download class="h-4 w-4" />
                            </button>
                            <button
                                class="rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50"
                                :disabled="!selectedKey"
                            >
                                <Share2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <div class="p-4">
                        <Tree
                            v-model:selectionKeys="selectedKey"
                            :value="nodes"
                            selectionMode="checkbox"
                            class="w-full"
                            :filter="true"
                            filterMode="lenient"
                        ></Tree>
                    </div>
                </div>

                <!-- Sidebar / Details (Optional, can be used for selected item details) -->
                <div
                    class="hidden w-full rounded-lg bg-white p-4 shadow lg:block lg:w-80 dark:bg-gray-800"
                >
                    <h3
                        class="mb-4 font-medium text-gray-900 dark:text-gray-100"
                    >
                        Details
                    </h3>
                    <div
                        v-if="selectedKey"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        <p>Selected Item Key: {{ selectedKey }}</p>
                        <p class="mt-2">Select an item to view details.</p>
                    </div>
                    <div v-else class="py-10 text-center text-gray-500">
                        <p>No item selected</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
