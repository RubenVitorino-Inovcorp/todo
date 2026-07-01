<script setup lang="ts">
import TaskCard from '@/components/TaskCard.vue';
import tasks from '@/routes/tasks/index';

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
    task: Task;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Informações da tarefa',
                href: tasks.index(),
            },
        ],
    },
});
</script>

<template>
    <div class="flex flex-col mt-12 gap-4 w-full max-w-4xl mx-auto px-4 lg:justify-center">
        <h1 class="text-sm font-semibold tracking-wide text-muted-foreground uppercase">Informações da tarefa</h1>
        <TaskCard
            :key="task.id"
            :task="task"
        />
    </div>
</template>