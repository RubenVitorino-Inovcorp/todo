<script setup lang="ts">
import { computed } from 'vue';
import DotField from '@/components/DotField.vue';
import { SidebarInset } from '@/components/ui/sidebar';
import type { AppVariant } from '@/types';

type Props = {
    variant?: AppVariant;
    class?: string;
};

const props = withDefaults(defineProps<Props>(), {
    variant: 'sidebar',
});
const className = computed(() => props.class);
</script>

<template>
    <SidebarInset v-if="props.variant === 'sidebar'" :class="[className, 'isolate']">
        <div class="absolute inset-0 z-[-1] overflow-hidden">
            <DotField
                :dot-radius="1.5"
                :dot-spacing="14"
                :bulge-strength="67"
                :glow-radius="160"
                :sparkle="false"
                :wave-amplitude="0"
                :cursor-radius="0"
                :cursor-force="0"
                bulge-only
                gradient-from="#7a7a7a33"
                gradient-to="#7a7a7aBF"
                glow-color="#00000000"
            />
        </div>
        <slot />
    </SidebarInset>
    <main
        v-else
        class="isolate mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl relative"
        :class="className"
    >
        <div class="absolute inset-0 z-[-1] overflow-hidden rounded-xl">
            <DotField
                :dot-radius="1.5"
                :dot-spacing="14"
                :bulge-strength="67"
                :glow-radius="160"
                :sparkle="false"
                :wave-amplitude="0"
                :cursor-radius="500"
                :cursor-force="0.1"
                bulge-only
                gradient-from="#7cff67"
                gradient-to="#A0FFBC"
                glow-color="#120F17"
            />
        </div>
        <slot />
    </main>
</template>
