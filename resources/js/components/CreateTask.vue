<script setup lang="ts">
import { ref } from 'vue';
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import type { DateValue } from '@internationalized/date'
import { getLocalTimeZone, today } from '@internationalized/date'
import { ChevronDownIcon } from '@lucide/vue'
import { Calendar } from '@/components/ui/calendar'
import { Label } from '@/components/ui/label'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import { Button } from '@/components/ui/button';
import { Plus } from '@lucide/vue';
import { useForm } from '@inertiajs/vue3';
import { Input } from './ui/input'
import { Textarea } from './ui/textarea'

const props = defineProps({
    title: String,
    description: String,
    priority: String,
    status: String,
    due_date: Date,
})

const form = useForm({
    title: '',
    description: '',
    priority: 'low',
    status: 'pending',
    due_date: null,
})

const submit = () => {
    if (date.value && time.value) {
        form.due_date = `${date.value.toString()} ${time.value}`
    } else if (date.value) {
        form.due_date = date.value.toString()
    }

    form.post('tasks', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        }
    })
}

const date = ref(today(getLocalTimeZone())) as Ref<DateValue>
const time = ref('10:30:00')
const open = ref(false)
</script>

<template>
    <Drawer>
    <DrawerTrigger as-child>
      <Button variant="outline" class="hover:cursor-pointer">
        <Plus />
        Nova Tarefa
      </Button>
    </DrawerTrigger>
    <DrawerContent>
      <div class="mx-auto w-full max-w-sm">
        <DrawerHeader>
          <DrawerTitle class="text-xl">Criar Tarefa</DrawerTitle>
          <DrawerDescription>Preencha os campos abaixo para criar uma nova tarefa.</DrawerDescription>
        </DrawerHeader>
        <div class="p-4 pb-0">
          <form id="create-task-form" @submit.prevent="submit" class="space-y-4">
            <div class="flex flex-col gap-2">
            <Label for="title" class="text-lg">Título</Label>
            <Input v-model="form.title" id="title" placeholder="Título" required/>
            </div>
            <div class="flex flex-col gap-2">
            <Label for="description" class="text-lg">Descrição</Label>
            <Textarea v-model="form.description" id="description" placeholder="Descrição" />
            </div>
            <div class="flex flex-col gap-2">
                <Label class="text-lg">Data de vencimento</Label>
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
            </div>
        </div>  
      </div>          
          </form>
        </div>
        <DrawerFooter>
          <Button type="submit" form="create-task-form" :disabled="form.processing" class="hover:cursor-pointer">Criar Tarefa</Button>
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