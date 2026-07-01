<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DotField from '@/components/DotField.vue';
import { login } from '@/routes';
import { register } from '@/routes';
import tasks from '@/routes/tasks/index';
/* @chisel-registration */
/* @end-chisel-registration */
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="relative flex min-h-screen flex-col items-center bg-[#f0f4fb] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#070a12]"
    >
        <!-- Background: DotField -->
        <div class="pointer-events-none absolute inset-0 z-0 overflow-hidden">
            <div class="pointer-events-auto absolute inset-0 opacity-100">
                <DotField
                    :dot-radius="2.5"
                    :dot-spacing="24"
                    :cursor-radius="600"
                    :cursor-force="0.1"
                    :bulge-only="true"
                    :bulge-strength="80"
                    :glow-radius="0"
                    :sparkle="true"
                    :wave-amplitude="3"
                    gradient-from="rgba(66, 116, 217, 0.4)"
                    gradient-to="rgba(106, 156, 247, 0.2)"
                    glow-color="rgba(66, 116, 217, 0.6)"
                />
            </div>
        </div>

        <header
            class="relative z-10 mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
        >
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="tasks.index()"
                    class="inline-block rounded-sm border border-white/20 bg-white/10 px-5 py-1.5 text-sm leading-normal text-[#1b1b18] backdrop-blur-sm hover:bg-white/20 dark:border-white/10 dark:text-[#EDEDEC] dark:hover:bg-white/15"
                >
                    Tarefas
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-white/20 hover:bg-white/10 dark:text-[#EDEDEC] dark:hover:border-white/10"
                    >
                        Log in
                    </Link>
                    <!-- @chisel-registration -->
                    <Link
                        :href="register()"
                        class="inline-block rounded-sm border border-white/20 bg-white/10 px-5 py-1.5 text-sm leading-normal text-[#1b1b18] backdrop-blur-sm hover:bg-white/20 dark:border-white/10 dark:text-[#EDEDEC] dark:hover:bg-white/15"
                    >
                        Register
                    </Link>
                    <!-- @end-chisel-registration -->
                </template>
            </nav>
        </header>
        <div
            class="relative z-10 flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main
                class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg shadow-xl lg:max-w-4xl lg:flex-row"
            >
                <div
                    class="flex-1 rounded-br-lg rounded-bl-lg border border-white/20 bg-white/80 p-6 pb-12 text-[13px] leading-[20px] backdrop-blur-xl lg:rounded-tl-lg lg:rounded-br-none lg:border-r-0 lg:p-20 dark:border-white/10 dark:bg-[#161615]/80 dark:text-[#EDEDEC]"
                >
                    <h1 class="mb-1 text-lg font-semibold text-[#1b1b18] dark:text-white">Bem-vindo ao To-Do App</h1>
                    <p class="mb-4 text-[#706f6c] dark:text-[#A1A09A]">
                        Organize as suas tarefas de forma simples e eficiente.
                    </p>
                    <ul class="mb-4 flex flex-col gap-3 lg:mb-6">
                        <li class="flex items-center gap-3">
                            <span
                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#4274D9]/10 text-[#4274D9] dark:bg-[#4274D9]/20"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Crie e gerencie tarefas com facilidade</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span
                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#4274D9]/10 text-[#4274D9] dark:bg-[#4274D9]/20"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Acompanhe o progresso das suas atividades</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span
                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#4274D9]/10 text-[#4274D9] dark:bg-[#4274D9]/20"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Interface intuitiva e moderna</span>
                        </li>
                    </ul>
                    <ul class="flex gap-3 text-sm leading-normal">
                        <li>
                            <Link
                                v-if="$page.props.auth.user"
                                :href="tasks.index()"
                                class="inline-block rounded-sm bg-[#4274D9] px-5 py-1.5 text-sm leading-normal text-white transition-colors hover:bg-[#3562c0]"
                            >
                                Ir para Tarefas
                            </Link>
                            <Link
                                v-else
                                :href="login()"
                                class="inline-block rounded-sm bg-[#4274D9] px-5 py-1.5 text-sm leading-normal text-white transition-colors hover:bg-[#3562c0]"
                            >
                                Começar agora
                            </Link>
                        </li>
                    </ul>
                </div>
                <div
                    class="relative -mb-px aspect-[335/364] w-full shrink-0 overflow-hidden rounded-t-lg border border-white/20 bg-[#4274D9]/5 lg:mb-0 lg:-ml-px lg:aspect-auto lg:w-[438px] lg:rounded-t-none lg:rounded-r-lg lg:border-l-0 dark:border-white/10 dark:bg-[#4274D9]/10"
                >
                    <img
                        src="/storage/banner-welcome.webp"
                        alt="To-Do App Banner"
                        class="h-full w-full object-cover"
                    />
                    <div
                        class="absolute inset-0 rounded-t-lg shadow-[inset_0px_0px_0px_1px_rgba(255,255,255,0.1)] lg:rounded-t-none lg:rounded-r-lg"
                    ></div>
                </div>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
