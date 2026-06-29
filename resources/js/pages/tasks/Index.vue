<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import tasks from '@/routes/tasks/index';
import TaskCard from '@/components/TaskCard.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tarefas',
                href: tasks.index(),
            },
        ],
    },
});

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

const props = defineProps<{
    pendingTasks: Array<Task>;
    completedTasks: Array<Task>;
    highPriorityTasks: Array<Task>;
    lowPriorityTasks: Array<Task>;
    mediumPriorityTasks: Array<Task>;
}>();
</script>

<template>
    <Head title="Tarefas" />

    <div class="flex flex-col gap-8 p-4 md:p-6">
        <div v-if="highPriorityTasks.length > 0" class="flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <span class="inline-block size-2 rounded-full bg-red-400" />
                <h1
                    class="text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                >
                    Prioridade Alta
                </h1>
                <span class="text-xs text-muted-foreground/60">{{
                    highPriorityTasks.length
                }}</span>
            </div>
            <div
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <TaskCard
                    v-for="task in highPriorityTasks"
                    :key="task.id"
                    :task="task"
                />
            </div>
        </div>

        <div v-if="mediumPriorityTasks.length > 0" class="flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <span class="inline-block size-2 rounded-full bg-amber-400" />
                <h1
                    class="text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                >
                    Prioridade Média
                </h1>
                <span class="text-xs text-muted-foreground/60">{{
                    mediumPriorityTasks.length
                }}</span>
            </div>
            <div
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <TaskCard
                    v-for="task in mediumPriorityTasks"
                    :key="task.id"
                    :task="task"
                />
            </div>
        </div>

        <div v-if="lowPriorityTasks.length > 0" class="flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <span class="inline-block size-2 rounded-full bg-emerald-400" />
                <h1
                    class="text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                >
                    Prioridade Baixa
                </h1>
                <span class="text-xs text-muted-foreground/60">{{
                    lowPriorityTasks.length
                }}</span>
            </div>
            <div
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <TaskCard
                    v-for="task in lowPriorityTasks"
                    :key="task.id"
                    :task="task"
                />
            </div>
        </div>

        <div v-if="completedTasks.length > 0" class="flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <span
                    class="inline-block size-2 rounded-full bg-muted-foreground/40"
                />
                <h1
                    class="text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                >
                    Concluídas
                </h1>
                <span class="text-xs text-muted-foreground/60">{{
                    completedTasks.length
                }}</span>
            </div>
            <div
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <TaskCard
                    v-for="task in completedTasks"
                    :key="task.id"
                    :task="task"
                />
            </div>
        </div>
    </div>
</template>
