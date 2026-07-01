<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { parseDate, getLocalTimeZone, today } from '@internationalized/date'
import { ChevronDownIcon } from '@lucide/vue'
import { ref } from 'vue';
import {
    update,
} from '@/actions/App/Http/Controllers/TaskController';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import SpotlightCard from '@/components/ui/SpotlightCard.vue';
import { Textarea } from '@/components/ui/textarea'
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

const form = useForm({
    title: props.task.title ?? '',
    description: props.task.description ?? '',
    priority: props.task.priority ?? 'low',
    status: props.task.status ?? 'pending',
    due_date: props.task.due_date ?? null,
})

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Editar tarefa',
                href: tasks.index(),
            },
        ],
    },
});

const submit = () => {
    if (date.value && time.value) {
        form.due_date = `${date.value.toString()} ${time.value}`
    } else if (date.value) {
        form.due_date = date.value.toString()
    }

    router.put(
    update.url(props.task.id),
    {
        status: form.status,
        title: form.title,
        description: form.description,
        priority: form.priority,
        due_date: form.due_date,
    },
    {
        preserveScroll: true,
        onSuccess: () => {
            // toast
        },
        onError: (errors) => {
            console.error('Erro ao atualizar tarefa: ', errors);
        }
    }
);
}

const raw = props.task.due_date; // Data de vencimento em formato string

// Transforma a data vencimento em um objeto DateValue
const date = ref(raw ? parseDate(raw.substring(0, 10)) : today(getLocalTimeZone()));
// Transforma a hora vencimento em um objeto TimeValue
const time = ref(raw ? raw.substring(11, 19) : '10:30:00');
const open = ref(false);
</script>

<template>
    <div class="flex flex-col mt-12 gap-4 w-full max-w-4xl mx-auto px-4 lg:justify-center">
        <h1 class="text-sm font-semibold tracking-wide text-muted-foreground uppercase">Editar tarefa</h1>

        <SpotlightCard class="flex flex-col transition-all duration-300 ease-out">
            <div class="mb-6 flex flex-col gap-1">
                <h2 class="text-xl font-semibold">{{ task.title }}</h2>
                <p class="text-sm text-muted-foreground">Edite os campos abaixo para atualizar a tarefa.</p>
            </div>

            <form id="edit-task-form" @submit.prevent="submit" class="space-y-5">
                <!-- Título -->
                <div class="flex flex-col gap-2">
                    <Label for="title" class="text-sm font-medium">Título</Label>
                    <Input v-model="form.title" id="title" placeholder="Título da tarefa" required />
                    <span class="text-sm text-red-500" v-if="form.errors?.title">{{ form.errors.title }}</span>
                </div>

                <!-- Descrição -->
                <div class="flex flex-col gap-2">
                    <Label for="description" class="text-sm font-medium">Descrição</Label>
                    <Textarea v-model="form.description" id="description" placeholder="Descreva a tarefa..." class="min-h-[100px]" />
                    <span class="text-sm text-red-500" v-if="form.errors?.description">{{ form.errors.description }}</span>
                </div>

                <!-- Data vencimento -->
                <div class="flex flex-col gap-2">
                    <Label class="text-sm font-medium">Data de vencimento</Label>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col gap-1.5">
                            <Label for="date-picker" class="text-xs text-muted-foreground px-1">Data</Label>
                            <Popover v-model:open="open">
                                <PopoverTrigger as-child>
                                    <Button
                                        id="date-picker"
                                        variant="outline"
                                        class="w-36 justify-between font-normal"
                                    >
                                        {{ date ? date.toDate(getLocalTimeZone()).toLocaleDateString() : "Selecionar data" }}
                                        <ChevronDownIcon class="size-4 text-muted-foreground" />
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-auto overflow-hidden p-0" align="start">
                                    <Calendar
                                        :model-value="date"
                                        @update:model-value="(value) => {
                                            if (value) {
                                                date = value
                                                open = false
                                            }
                                        }"
                                    />
                                </PopoverContent>
                            </Popover>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="time-picker" class="text-xs text-muted-foreground px-1">Hora</Label>
                            <Input
                                id="time-picker"
                                type="time"
                                step="1"
                                v-model="time"
                                class="w-28 bg-background appearance-none [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-calendar-picker-indicator]:appearance-none"
                            />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="priority" class="text-xs text-muted-foreground px-1">Prioridade</Label>
                            <Select v-model="form.priority" required>
                                <SelectTrigger class="w-28">
                                    <SelectValue placeholder="Prioridade" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="low">
                                        <span class="inline-block size-2 rounded-full bg-emerald-400" />
                                        Baixa
                                    </SelectItem>
                                    <SelectItem value="medium">
                                        <span class="inline-block size-2 rounded-full bg-amber-400" />
                                        Média
                                    </SelectItem>
                                    <SelectItem value="high">
                                        <span class="inline-block size-2 rounded-full bg-red-400" />
                                        Alta
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <span class="text-sm text-red-500" v-if="form.errors?.priority">{{ form.errors.priority }}</span>
                        </div>
                    </div>
                    <span class="text-sm text-red-500" v-if="form.errors?.due_date">{{ form.errors.due_date }}</span>
                </div>

                <!-- Status -->
                <div class="flex flex-col gap-2">
                    <Label for="status" class="text-sm font-medium">Status</Label>
                    <Select v-model="form.status" required>
                        <SelectTrigger class="w-36">
                            <SelectValue placeholder="Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="pending">Pendente</SelectItem>
                            <SelectItem value="completed">Concluída</SelectItem>
                        </SelectContent>
                    </Select>
                    <span class="text-sm text-red-500" v-if="form.errors?.status">{{ form.errors.status }}</span>
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <Button type="submit" form="edit-task-form" :disabled="form.processing" class="hover:cursor-pointer">
                        {{ form.processing ? 'A guardar...' : 'Guardar alterações' }}
                    </Button>
                </div>
            </form>
        </SpotlightCard>
    </div>
</template>