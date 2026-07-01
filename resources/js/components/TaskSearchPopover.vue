<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { X } from '@lucide/vue';
import { debounce } from 'lodash';
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import tasks from '@/routes/tasks/index';

const props = defineProps<{
    filters: {
        search?: string | null;
        sort?: string | null;
    };
}>();

const open = defineModel<boolean>('open', { default: false });

const panelRef = ref<HTMLElement | null>(null);
const inputRef = ref<HTMLInputElement | null>(null);

const search = ref(props.filters.search ?? '');

const performSearch = debounce((value: string) => {
    const params: Record<string, string> = {};

    if (value) {
        params.search = value;
    }

    if (props.filters.sort) {
        params.sort = props.filters.sort;
    }

    router.get(tasks.index.url(), params, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}, 300);

watch(search, (newValue) => {
    performSearch(newValue);
});

// Auto-focus the input when the panel opens
watch(open, async (isOpen) => {
    if (isOpen) {
        await nextTick();
        inputRef.value?.focus();
    }
});

const clearSearch = () => {
    search.value = '';
};

const closeOnClickOutside = (e: MouseEvent) => {
    const target = e.target as HTMLElement;

    if (!document.body.contains(target)) {
        return;
    }

    if (panelRef.value && panelRef.value.contains(target)) {
        return;
    }

    open.value = false;
};

const closeOnEscape = (e: KeyboardEvent) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeOnClickOutside);
    document.addEventListener('keydown', closeOnEscape);
});

onUnmounted(() => {
    document.removeEventListener('click', closeOnClickOutside);
    document.removeEventListener('keydown', closeOnEscape);
});
</script>

<template>
    <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 translate-y-2 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 translate-y-2 scale-95"
    >
        <div
            v-if="open"
            ref="panelRef"
            class="absolute bottom-full left-1/2 z-50 mb-3 w-72 -translate-x-1/2 rounded-md border bg-popover p-3 text-popover-foreground shadow-md"
        >
            <div class="relative">
                <Input
                    ref="inputRef"
                    v-model="search"
                    type="search"
                    placeholder="Procurar tarefas..."
                    class="pr-8"
                />
                <Button
                    v-if="search"
                    variant="ghost"
                    size="sm"
                    class="absolute top-1/2 right-1 h-6 w-6 -translate-y-1/2 p-0"
                    @click="clearSearch"
                >
                    <X class="size-3.5" />
                </Button>
            </div>
        </div>
    </transition>
</template>
