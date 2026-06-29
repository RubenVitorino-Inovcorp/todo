<script setup lang="ts">
import type { AcceptableValue } from 'reka-ui';
import { computed, ref, watch } from 'vue';
import SpotlightCard from './ui/SpotlightCard.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Badge } from '@/components/ui/badge';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { CalendarDays, Pencil, Trash2, Ellipsis } from '@lucide/vue';
import { router } from '@inertiajs/vue3';
import {
    update,
    destroy,
    edit,
} from '@/actions/App/Http/Controllers/TaskController';

interface Task {
    id: number;
    title: string;
    description: string;
    priority: string;
    status: string;
    due_date: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{ task: Task }>();

const localChecked = ref(props.task.status === 'completed');

watch(() => props.task.status, (newStatus) => {
    localChecked.value = newStatus === 'completed';
});

const handleToggle = () => {
    const newValue = !localChecked.value;

    localChecked.value = newValue;

    const newStatus = newValue ? 'completed' : 'pending';

    router.put(
        update.url(props.task.id),
        { status: newStatus },
        {
            preserveScroll: true,
            onSuccess: () => {
                // toast
            },
            onError: (errors) => {
                localChecked.value = !newValue;
                console.error('Erro ao atualizar tarefa: ', errors);
            }
        }
    );
};

const isCompleted = computed(() => localChecked.value);

const changePriority = (newPriority: AcceptableValue) => {
    if (typeof newPriority !== 'string') return;
    if (newPriority === props.task.priority) return;

    router.put(
        update.url(props.task.id),
        {
            priority: newPriority,
        },
        {
            preserveScroll: true,
        },
    );
};

const deleteTask = () => {
    router.delete(destroy.url(props.task.id), {
        preserveScroll: true,
    });
};

const navigateToEdit = () => {
    router.visit(edit.url(props.task.id));
};

const priorityDot: Record<string, string> = {
    high: 'bg-red-400',
    medium: 'bg-amber-400',
    low: 'bg-emerald-400',
};

const formattedDueDate = computed(() => {
    if (!props.task.due_date) return null;

    const dueDate = new Date(props.task.due_date);
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const dueDateOnly = new Date(
        dueDate.getFullYear(),
        dueDate.getMonth(),
        dueDate.getDate(),
    );

    if (dueDateOnly.getTime() === today.getTime()) {
        return { text: 'Hoje', isOverdue: false, isToday: true };
    }
    if (dueDateOnly.getTime() === tomorrow.getTime()) {
        return { text: 'Amanhã', isOverdue: false, isToday: false };
    }

    const isOverdue = dueDateOnly < today;
    return {
        text: dueDate.toLocaleDateString('pt-PT', {
            day: 'numeric',
            month: 'short',
        }),
        isOverdue,
        isToday: false,
    };
});

const dueDateBadgeClass = computed(() => {
    if (!formattedDueDate.value) return '';
    if (formattedDueDate.value.isOverdue)
        return 'bg-red-500/15 text-red-400 border-red-500/20';
    if (formattedDueDate.value.isToday)
        return 'bg-amber-500/15 text-amber-400 border-amber-500/20';
    return 'bg-muted/50 text-muted-foreground border-border/50';
});
</script>

<template>
    <SpotlightCard
        class="group flex min-h-[320px] flex-col transition-all duration-300 ease-out"
    >
        <!-- Top: Checkbox + Título -->
        <div class="flex items-start gap-3">
            <Checkbox
                v-model="localChecked"
                class="mt-0.5 size-5 shrink-0 rounded-full border-2 transition-colors duration-200 data-[state=checked]:border-emerald-500 data-[state=checked]:bg-emerald-500 text-white"
                @click="handleToggle"
                />
            <h3
                :class="[
                    'text-base leading-snug font-semibold transition-all duration-200',
                    isCompleted
                        ? 'text-muted-foreground line-through decoration-muted-foreground/50'
                        : 'text-foreground',
                ]"
            >
                {{ task.title }}
            </h3>
        </div>

        <!-- Body: Descrição -->
        <div class="mt-3 flex-1 pl-8">
            <p
                v-if="task.description"
                :class="[
                    'text-sm leading-relaxed transition-colors duration-200',
                    isCompleted
                        ? 'text-muted-foreground/50'
                        : 'text-muted-foreground',
                ]"
            >
                {{ task.description }}
            </p>
        </div>

        <!-- Row: Data de vencimento -->
        <div class="mt-4 flex items-center">
            <Badge
                v-if="formattedDueDate"
                variant="outline"
                :class="[
                    'gap-1.5 rounded-lg px-3 py-1 text-xs font-medium',
                    dueDateBadgeClass,
                ]"
            >
                <CalendarDays class="size-3.5" />
                {{ formattedDueDate.text }}
            </Badge>
        </div>

        <!-- Footer: ... menu / Prioridade select / Botão de editar -->
        <div class="mt-3 flex items-center justify-between">
            <!-- Left: More menu -->
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button
                        variant="ghost"
                        size="icon"
                        class="size-8 text-muted-foreground hover:text-foreground"
                    >
                        <Ellipsis class="size-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="start">
                    <DropdownMenuItem @click="navigateToEdit">
                        <Pencil class="size-4" />
                        Editar
                    </DropdownMenuItem>
                    <DropdownMenuItem
                        class="text-destructive focus:text-destructive"
                        @click="deleteTask"
                    >
                        <Trash2 class="size-4" />
                        Eliminar
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            <!-- Centro: Select de prioridade -->
            <Select
                :model-value="task.priority"
                @update:model-value="changePriority"
            >
                <SelectTrigger
                    class="h-8 w-auto gap-1.5 border-none bg-transparent px-2 text-xs font-medium shadow-none focus-visible:ring-0"
                >
                    <span
                        :class="[
                            'inline-block size-2.5 shrink-0 rounded-full',
                            priorityDot[task.priority] ?? 'bg-muted-foreground',
                        ]"
                    />
                    <SelectValue />
                </SelectTrigger>
                <SelectContent align="center">
                    <SelectItem value="high">
                        <span
                            class="inline-block size-2 rounded-full bg-red-400"
                        />
                        Alta
                    </SelectItem>
                    <SelectItem value="medium">
                        <span
                            class="inline-block size-2 rounded-full bg-amber-400"
                        />
                        Média
                    </SelectItem>
                    <SelectItem value="low">
                        <span
                            class="inline-block size-2 rounded-full bg-emerald-400"
                        />
                        Baixa
                    </SelectItem>
                </SelectContent>
            </Select>

            <Button
                size="icon"
                class="size-8 rounded-full bg-primary text-white hover:bg-primary/80 hover:cursor-pointer"
                @click="navigateToEdit"
            >
                <Pencil class="size-3.5 text-background" />
            </Button>
        </div>
    </SpotlightCard>
</template>
