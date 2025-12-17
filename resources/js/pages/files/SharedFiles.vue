<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, shared_files } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {
    createColumnHelper,
    FlexRender,
    getCoreRowModel,
    useVueTable
} from '@tanstack/vue-table';
import { ref } from 'vue';

type SharedFile = {
    id: string;
    name: string;
    size: string;
    sharedWith: string;
    sharedDate: string;
    status: 'Active' | 'Expired';
};

const defaultData: SharedFile[] = [
    {
        id: '1',
        name: 'Project_Specs.pdf',
        size: '2.4 MB',
        sharedWith: 'alice@example.com',
        sharedDate: '2025-10-24',
        status: 'Active'
    },
    {
        id: '2',
        name: 'Q4_Report.xlsx',
        size: '1.1 MB',
        sharedWith: 'bob@example.com',
        sharedDate: '2025-10-23',
        status: 'Active'
    },
    {
        id: '3',
        name: 'Design_Assets.zip',
        size: '150 MB',
        sharedWith: 'team@design.com',
        sharedDate: '2025-10-20',
        status: 'Expired'
    }
];

const columnHelper = createColumnHelper<SharedFile>();

const columns = [
    columnHelper.accessor('name', {
        header: 'File Name',
        cell: (info) => info.getValue()
    }),
    columnHelper.accessor('size', {
        header: 'Size',
        cell: (info) => info.getValue()
    }),
    columnHelper.accessor('sharedWith', {
        header: 'Shared With',
        cell: (info) => info.getValue()
    }),
    columnHelper.accessor('sharedDate', {
        header: 'Date',
        cell: (info) => info.getValue()
    }),
    columnHelper.accessor('status', {
        header: 'Status',
        cell: (info) => {
            const status = info.getValue();
            return status;
        }
    })
];

const data = ref(defaultData);

const table = useVueTable({
    get data() {
        return data.value;
    },
    columns,
    getCoreRowModel: getCoreRowModel()
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url
    },
    {
        title: 'Files',
        href: shared_files().url
    }
];
</script>

<template>
    <Head title="Shared Files" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Shared Files
                </h1>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
            >
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400"
                        >
                        <tr
                            v-for="headerGroup in table.getHeaderGroups()"
                            :key="headerGroup.id"
                        >
                            <th
                                v-for="header in headerGroup.headers"
                                :key="header.id"
                                scope="col"
                                class="px-6 py-3 font-medium"
                            >
                                <FlexRender
                                    v-if="!header.isPlaceholder"
                                    :render="header.column.columnDef.header"
                                    :props="header.getContext()"
                                />
                            </th>
                        </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-gray-700"
                        >
                        <tr
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                            class="bg-white transition-colors hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700"
                        >
                            <td
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                                class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                    <span
                                        v-if="cell.column.id === 'status'"
                                        class="rounded-full px-2 py-1 text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300':
                                                cell.getValue() === 'Active',
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300':
                                                cell.getValue() === 'Expired',
                                        }"
                                    >
                                        <FlexRender
                                            :render="cell.column.columnDef.cell"
                                            :props="cell.getContext()"
                                        />
                                    </span>
                                <span v-else>
                                        <FlexRender
                                            :render="cell.column.columnDef.cell"
                                            :props="cell.getContext()"
                                        />
                                    </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="table.getRowModel().rows.length === 0"
                    class="p-8 text-center text-gray-500 dark:text-gray-400"
                >
                    No shared files found.
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
