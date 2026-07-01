<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { parseDate, getLocalTimeZone, today } from '@internationalized/date'
import { ChevronDownIcon } from '@lucide/vue'
import { Pencil } from '@lucide/vue';
import { ref } from 'vue';
import {
    update,
} from '@/actions/App/Http/Controllers/TaskController';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar'
import {
  Drawer,
  DrawerClose,
  DrawerContent,
  DrawerDescription,
  DrawerFooter,
  DrawerHeader,
  DrawerTitle,
  DrawerTrigger,
} from '@/components/ui/drawer'
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
import { Input } from './ui/input'
import { Textarea } from './ui/textarea'

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
    <Drawer>
    <DrawerTrigger as-child>
            <Button
                size="icon"
                class="size-8 rounded-full bg-primary text-white hover:bg-primary/80 hover:cursor-pointer"
            >
                <Pencil class="size-3.5 text-background" />
            </Button>
    </DrawerTrigger>
    <DrawerContent>
      <div class="mx-auto w-full max-w-sm">
        <DrawerHeader>
          <DrawerTitle class="text-xl">Editar Tarefa</DrawerTitle>
          <DrawerDescription>Edite os campos abaixo para editar a tarefa.</DrawerDescription>
        </DrawerHeader>
        <div class="p-4 pb-0">
          <form id="create-task-form" @submit.prevent="submit" class="space-y-4">
            <div class="flex flex-col gap-2">
            <Label for="title" class="text-lg">Título</Label>
            <Input v-model="form.title" id="title" placeholder="Título" required/>
            <span class="text-sm text-red-600" v-if="form.errors?.title">{{ form.errors.title }}</span>
            </div>
            <div class="flex flex-col gap-2">
            <Label for="description" class="text-lg">Descrição</Label>
            <Textarea v-model="form.description" id="description" placeholder="Descrição" />
            <span class="text-sm text-red-600" v-if="form.errors?.description">{{ form.errors.description }}</span>
            </div>
            <div class="flex flex-col gap-2">
                <Label class="text-lg">Data vencimento</Label>
        <div class="flex gap-4">
            <div class="flex flex-col gap-3">
            <Label for="date-picker" class="px-1">
                Data
            </Label>
            <Popover v-model:open="open">
                <PopoverTrigger as-child>
                <Button
                    id="date-picker"
                    variant="outline"
                    class="w-32 justify-between font-normal"
                >
                    {{ date ? date.toDate(getLocalTimeZone()).toLocaleDateString() : "Select date" }}
                    <ChevronDownIcon />
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
            <div class="flex flex-col gap-3">
            <Label for="time-picker" class="px-1">
                Hora
            </Label>
            <Input
                id="time-picker"
                type="time"
                step="1"
                v-model="time"
                class="bg-background appearance-none [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-calendar-picker-indicator]:appearance-none"
            />
            </div>
            <div class="flex flex-col gap-3">
                <Label for="priority" class="px-1">
                    Prioridade
                </Label>
                <Select v-model="form.priority" required>
                    <SelectTrigger>
                        <SelectValue placeholder="Prioridade" />
                    </SelectTrigger>
                    <SelectContent>
                            <SelectItem value="low">
                                Baixa
                            </SelectItem>
                            <SelectItem value="medium">
                                Média
                            </SelectItem>
                            <SelectItem value="high">
                                Alta
                            </SelectItem>
                        </SelectContent>
                </Select>
                <span class="text-sm text-red-600" v-if="form.errors?.priority">{{ form.errors.priority }}</span>
            </div>
        </div>  
        <span class="text-sm text-red-600" v-if="form.errors?.due_date">{{ form.errors.due_date }}</span>
        
        <div class="flex flex-col gap-2">
            <Label for="status" class="text-lg">
                Status
            </Label>
            <Select v-model="form.status" required>
                <SelectTrigger>
                    <SelectValue placeholder="Status" />
                </SelectTrigger>
                <SelectContent>
                        <SelectItem value="pending">
                            Pendente
                        </SelectItem>
                        <SelectItem value="completed">
                            Concluída
                        </SelectItem>
                    </SelectContent>
            </Select>
            <span class="text-sm text-red-600" v-if="form.errors?.status">{{ form.errors.status }}</span>
        </div>

      </div>          
          </form>
        </div>
        <DrawerFooter>
          <Button type="submit" form="create-task-form" :disabled="form.processing" class="hover:cursor-pointer">Editar Tarefa</Button>
          <DrawerClose as-child>
            <Button variant="outline" class="hover:cursor-pointer">
              Cancelar
            </Button>
          </DrawerClose>
        </DrawerFooter>
      </div>
    </DrawerContent>
  </Drawer>
</template>