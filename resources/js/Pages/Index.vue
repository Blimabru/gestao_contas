<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const store = useStore();

const backgroundColor = computed(() => store.getters.backgroundColor);

const isOpen = ref(false); // Controle do menu mobile

onMounted(() => {
    store.dispatch('fetchSettings');
});

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    }
});
</script>

<template>
    <div :style="{ backgroundColor }" class="flex flex-col min-h-screen">

        <Head title="Home" />

        <!-- Header Section -->
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex space-around items-center py-3">
                    <!-- Logo maior para telas grandes -->
                    <Link :href="route('dashboard')" class="flex left-0 items-center">
                    <img src="/images/logo/Logo-Horizontal.png" alt="Logo" class="hidden sm:block h-9 w-auto" />
                    </Link>
                    <!-- Logo menor para telas pequenas -->
                    <Link :href="route('dashboard')" class="flex left-0 items-center">
                    <img src="/images/logo/Logo.png" alt="Logo" class="block sm:hidden h-9 w-auto" />
                    </Link>

                    <!-- Links  -->
                    <div class="hidden sm:flex space-x-4 ml-auto"> <!-- Exibe links em telas médias e grandes -->
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')"
                                class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out py-2">
                            Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')"
                                class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out py-2">
                            Log in
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="sm:hidden ml-auto">
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

            <!-- Mobile Menu (Exibe quando isOpen for true) -->
            <div v-if="isOpen" class="sm:hidden bg-white shadow-md">
                <div class="px-4 py-2">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')" class="block text-gray-500 hover:text-gray-700 py-2">
                        Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="block text-gray-500 hover:text-gray-700 py-2">
                        Log in
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Main Section -->
        <main class="flex-1 py-12"> <!-- Garante que o conteúdo principal ocupe o restante do espaço -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <ApplicationLogo />
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Bem-vindo!</h1>
                    <Link :href="route('login')"
                        class="text-lg text-gray-600 hover:text-gray-800 hover:underline transition-all duration-2000">
                    Para iniciar, faça seu login
                    </Link>
                </div>
            </div>
        </main>

        <!-- Footer Section -->
        <Footer />
    </div>
</template>