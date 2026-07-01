<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { X, ArrowUpAZ, ArrowDownAZ, CalendarArrowUp, CalendarArrowDown } from '@lucide/vue';
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import tasks from '@/routes/tasks/index';

const props = defineProps<{
    filters: {
        search?: string | null;
        sort?: string | null;
    };
}>();

const open = defineModel<boolean>('open', { default: false });

const panelRef = ref<HTMLElement | null>(null);

const sort = ref(props.filters.sort ?? 'titulo_asc');

const sortOptions = [
    { value: 'titulo_asc', label: 'Título A-Z', icon: ArrowUpAZ },
    { value: 'titulo_desc', label: 'Título Z-A', icon: ArrowDownAZ },
    { value: 'vencimento_asc', label: 'Vencimento ↑', icon: CalendarArrowUp },
    { value: 'vencimento_desc', label: 'Vencimento ↓', icon: CalendarArrowDown },
];

const hasActiveFilters = computed(() => {
    return sort.value && sort.value !== 'titulo_asc';
});

watch(sort, (newValue) => {
    const params: Record<string, string> = {};

    if (props.filters.search) {
        params.search = props.filters.search;
    }

    if (newValue) {
        params.sort = newValue;
    }

    router.get(tasks.index.url(), params, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
});

const clearFilters = () => {
    sort.value = 'titulo_asc';
};

const closeOnClickOutside = (e: MouseEvent) => {
    const target = e.target as HTMLElement;
    
    // 1. If the element was removed from the DOM before this event bubbled up, ignore it
    // (This fixes the issue where clicking the SelectTrigger immediately closes the popover)
    if (!document.body.contains(target)) {
        return;
    }

    // 2. Ignore clicks inside the panel itself
    if (panelRef.value && panelRef.value.contains(target)) {
        return;
    }

    // 3. Ignore clicks inside the teleported Select dropdown
    if (target.closest('[data-slot="select-content"]')) {
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
            class="absolute bottom-full left-1/2 z-50 mb-3 w-72 -translate-x-1/2 rounded-md border bg-popover p-4 text-popover-foreground shadow-md"
        >
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-semibold tracking-wide uppercase text-muted-foreground">
                    Filtros
                </h4>
                <Button
                    v-if="hasActiveFilters"
                    variant="ghost"
                    size="sm"
                    class="h-auto px-2 py-1 text-xs"
                    @click="clearFilters"
                >
                    <X class="mr-1 size-3" />
                    Limpar
                </Button>
            </div>

            <Separator class="my-3" />

            <div class="flex flex-col gap-2">
                <Label class="text-xs font-medium text-muted-foreground uppercase">Ordenar por</Label>
                <Select v-model="sort">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Título A-Z" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="option in sortOptions"
                            :key="option.value"
                            :value="option.value"
                        >
                            <component :is="option.icon" class="size-4" />
                            {{ option.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
        </div>
    </transition>
</template>
