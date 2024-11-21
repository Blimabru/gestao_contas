<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import { ref } from 'vue';

const isOpen = ref(false); // Controle de abertura do menu mobile
</script>

<template>
    <header class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex space-around items-center py-3">
                <!-- Logo maior para telas grandes -->
                <Link :href="route('dashboard')" class="flex left-0 items-center">
                <img src="/images/logo/Logo-Horizontal.png" alt="Logo" class="hidden sm:block h-9 w-auto" />
                </Link>
                <!-- max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -->
                <!-- Logo menor para telas pequenas -->
                <Link :href="route('dashboard')" class="flex left-0 items-center">
                <img src="/images/logo/Logo.png" alt="Logo" class="block sm:hidden h-9 w-auto" />
                </Link>

                <!-- Links -->
                <div class="hidden md:flex space-x-4 ml-auto">
                    <Link :href="route('dashboard')"
                        class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out py-2">
                    Dashboard
                    </Link>
                </div>

                <!-- Menu Mobile -->
                <div class="md:hidden ml-auto">
                    <button @click="isOpen = !isOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </nav>
        </div>

        <!-- Menu Mobile (visível em telas pequenas) -->
        <div v-if="isOpen" class="md:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <Link
                    class="block w-full ps-3 pe-4 py-2 text-start text-sm font-medium text-gray-700 bg-gray-50 focus:outline-none focus:text-blue-800 focus:bg-indigo-100 focus:border-blue-500 hover:text-gray-400 transition duration-150 ease-in-out border-b-2 border-blue-500">
                Dashboard
                </Link>
            </div>
        </div>
    </header>
</template>
