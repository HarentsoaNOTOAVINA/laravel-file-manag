<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, files } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Download, FolderPlus, Search, Share2, Upload, Expand, Shrink, X, CloudUpload, Folder, File as FileIcon } from 'lucide-vue-next';
import Tree from 'primevue/tree';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogClose,
} from '@/components/ui/dialog'

const props = defineProps<{
    files: any[]
}>();

// Dummy data for the tree
const nodes = ref(props.files);

// Types for PrimeVue checkbox selection
interface SelectionState {
    checked: boolean;
    partialChecked: boolean;
}

const selectedKey = ref<Record<string, SelectionState>>({});
const expandedKeys = ref<Record<string, boolean>>({});
const loading = ref(false);
const searchQuery = ref('');
const newFolderName = ref('New Folder');
const isCreateFolderOpen = ref(false);

const expandAll = () => {
    for (let node of nodes.value) {
        expandNode(node);
    }
    expandedKeys.value = { ...expandedKeys.value };
};

const collapseAll = () => {
    expandedKeys.value = {};
};

const expandNode = (node: any) => {
    if (node.children && node.children.length) {
        expandedKeys.value[node.key] = true;

        for (let child of node.children) {
            expandNode(child);
        }
    }
};

// Helper: Find a node by key
const findNodeByKey = (nodesList: any[], key: string): any => {
    for (let node of nodesList) {
        if (node.key === key) return node;
        if (node.children) {
            const found = findNodeByKey(node.children, key);
            if (found) return found;
        }
    }
    return null;
};

// Computed: Determine if the Action Buttons (New Folder/Upload) should be disabled
import { computed } from 'vue';
const isActionDisabled = computed(() => {
    // In checkbox mode, we only consider fully checked items as the active context
    const checkedKeys = Object.keys(selectedKey.value).filter(k => selectedKey.value[k].checked);

    // 1. If nothing is checked, it's root context -> ENABLED
    if (checkedKeys.length === 0) return false;

    // 2. Check if there is at least one folder in the checked items
    const hasFolderChecked = checkedKeys.some(key => {
        const node = findNodeByKey(nodes.value, key);
        return node && node.is_folder; // Use the is_folder flag from backend
    });

    // 3. User rule: "deactivate when file is solected, otherwise, it is actiuvated"
    // If we have a folder checked, we consider the context to be "Folder", even if files inside are also checked.
    // So we only disable if we have NO folders checked (meaning ONLY files are checked).
    return !hasFolderChecked;
});

const createNewFolder = () => {
    const newKey = `new-${Date.now()}`;
    const newNode = {
        key: newKey,
        label: newFolderName.value,
        data: `${newFolderName.value} Folder`,
        icon: 'pi pi-fw pi-cog', // Folder icon
        children: []
    };

    // Determine parent
    // If selection exists and is a FOLDER, append to it.
    // If selection exists and is a FILE, we shouldn't be here because button is disabled,
    // but if we were, we'd add to its parent? No, "deactivate when file is selected".

    const keys = Object.keys(selectedKey.value).filter(k => selectedKey.value[k].checked);

    if (keys.length > 0) {
        // Find the first selected FOLDER in the checked keys
        const parentKey = keys.find(key => {
            const node = findNodeByKey(nodes.value, key);
            return node && node.is_folder;
        });

        const parentNode = parentKey ? findNodeByKey(nodes.value, parentKey) : null;

        if (parentNode && parentNode.children) {
            parentNode.children.push(newNode);
            expandedKeys.value[parentNode.key] = true;
            expandedKeys.value = { ...expandedKeys.value };
        } else {
            nodes.value.push(newNode);
        }
    } else {
        nodes.value.push(newNode);
    }

    isCreateFolderOpen.value = false;
    newFolderName.value = 'New Folder'; // Reset
};


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url
    },
    {
        title: 'Files',
        href: files().url
    }
];
</script>

<template>
    <Head title="File Manager" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex flex-col gap-6 lg:flex-row">
                <!-- Main Content: Tree View -->
                <div class="flex-1 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-200 p-4 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Explorer
                        </h2>

                        <!-- Actions Toolbar -->
                        <div class="flex items-center gap-2">
                            <!-- New Folder Dialog -->
                            <Dialog v-model:open="isCreateFolderOpen">
                                <DialogTrigger as-child>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        class="bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700"
                                        :disabled="isActionDisabled"
                                    >
                                        <FolderPlus class="h-4 w-4 mr-2" />
                                        New Folder
                                    </Button>
                                </DialogTrigger>
                                <DialogContent class="sm:max-w-[425px]">
                                    <DialogHeader>
                                        <DialogTitle>New Folder</DialogTitle>
                                        <DialogDescription>
                                            Create a new folder to organize your files.
                                        </DialogDescription>
                                    </DialogHeader>
                                    <div class="grid gap-4 py-4">
                                        <div class="grid grid-cols-4 items-center gap-4">
                                            <Label for="name" class="text-right">
                                                Name
                                            </Label>
                                            <Input
                                                id="name"
                                                v-model="newFolderName"
                                                class="col-span-3"
                                                @keyup.enter="createNewFolder"
                                            />
                                        </div>
                                    </div>
                                    <DialogFooter>
                                        <Button type="button" @click="createNewFolder">Create Folder</Button>
                                    </DialogFooter>
                                </DialogContent>
                            </Dialog>

                            <!-- Upload Dialog -->
                            <Dialog>
                                <DialogTrigger as-child>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        class="bg-green-50 text-green-600 hover:bg-green-100 hover:text-green-700"
                                        :disabled="isActionDisabled"
                                    >
                                        <Upload class="h-4 w-4 mr-2" />
                                        Upload
                                    </Button>
                                </DialogTrigger>
                                <DialogContent class="sm:max-w-[500px]">
                                    <DialogHeader>
                                        <DialogTitle>Upload Files</DialogTitle>
                                        <DialogDescription>
                                            Drag and drop files here or click to browse.
                                        </DialogDescription>
                                    </DialogHeader>
                                    <div class="grid gap-4 py-4">
                                        <div
                                            class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-lg border-gray-300 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer dark:bg-gray-800 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-700"
                                        >
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <CloudUpload class="w-10 h-10 mb-3 text-gray-400" />
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    SVG, PNG, JPG or GIF (MAX. 800x400px)
                                                </p>
                                            </div>
                                            <input type="file" class="hidden" multiple />
                                        </div>
                                    </div>
                                    <DialogFooter>
                                        <DialogClose as-child>
                                            <Button variant="secondary">Cancel</Button>
                                        </DialogClose>
                                        <Button type="submit">Upload</Button>
                                    </DialogFooter>
                                </DialogContent>
                            </Dialog>

                            <div class="mx-1 h-4 w-px bg-gray-300 dark:bg-gray-600"></div>

                            <Button
                                variant="ghost"
                                size="icon"
                                :disabled="!selectedKey"
                            >
                                <Download class="h-4 w-4" />
                            </Button>
                            <Button
                                variant="ghost"
                                size="icon"
                                :disabled="!selectedKey"
                            >
                                <Share2 class="h-4 w-4" />
                            </Button>

                        </div>
                    </div>

                    <div class="p-4">
                        <div class="flex flex-wrap gap-2 mb-6">
                            <Button
                                variant="outline"
                                size="sm"
                                class="bg-gray-500 text-white border-gray-600 hover:bg-gray-600 hover:text-white"
                                @click="expandAll"
                            >
                                <Expand class="h-4 w-4 mr-2" />
                                Expand All
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="collapseAll"
                            >
                                <Shrink class="h-4 w-4 mr-2" />
                                Collapse All
                            </Button>
                        </div>
                        <Tree
                            v-model:selectionKeys="selectedKey"
                            v-model:expandedKeys="expandedKeys"
                            :value="nodes"
                            selectionMode="checkbox"
                            class="w-full"
                            :filter="true"
                            filterMode="lenient"
                        >
                            <template #nodeicon="{ node }">
                                <Folder v-if="node.is_folder" class="h-6 w-6 mr-2 text-blue-500" />
                                <FileIcon v-else class="h-6 w-6 mr-2 text-gray-400" />
                            </template>
                        </Tree>
                    </div>
                </div>

                <!-- Sidebar / Details (Optional, can be used for selected item details) -->
                <div class="hidden w-full rounded-lg bg-white p-4 shadow lg:block lg:w-80 dark:bg-gray-800">
                    <h3 class="mb-4 font-medium text-gray-900 dark:text-gray-100">
                        Details
                    </h3>
                    <div v-if="selectedKey" class="text-sm text-gray-600 dark:text-gray-400">
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

<style scoped>
</style>
