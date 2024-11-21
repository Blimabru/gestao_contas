<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import SearchCep from '@/Components/SearchCep.vue';
import Finances from '@/Components/Finances.vue';
import Settings from '@/Pages/Settings/Settings.vue';
import Footer from '@/Components/Footer.vue';

const store = useStore();

const { props } = usePage();
const user = props.user;

const backgroundColor = computed(() => store.getters.backgroundColor);

onMounted(() => {
  store.dispatch('fetchSettings');
});

defineProps({
  title: String,
});

const showingNavigationDropdown = ref(false);
const showingMenu = ref(false); // Variável para controlar o estado do menu hamburger
const currentPage = ref('searchCep');

const changePage = (page) => {
  currentPage.value = page;
};

const logout = () => {
  router.post(route('logout'));
};

const switchToTeam = (team) => {
  router.put(route('current-team.update'), { team_id: team.id }, { preserveState: false });
};
</script>

<template>
  <div :style="{ backgroundColor }" class="flex flex-col min-h-screen">

    <Head :title="title" />

    <div class="">
      <!-- Navigation -->
      <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="relative flex items-center h-16">

            <!-- Logo maior para telas grandes -->
            <Link :href="route('dashboard')" class="absolute left-0 flex items-center">
            <img src="/images/logo/Logo-Horizontal.png" alt="Logo" class="hidden sm:block h-9 w-auto" />
            </Link>

            <!-- Logo menor para telas pequenas -->
            <Link :href="route('dashboard')" class="absolute left-0 flex items-center">
            <img src="/images/logo/Logo.png" alt="Logo" class="block sm:hidden h-9 w-auto" />
            </Link>

            <!-- Menu Hamburger -->
            <div class="sm:hidden ml-auto">
              <button @click="showingMenu = !showingMenu" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>

            <!-- Links -->
            <div class="w-full flex justify-center space-x-8 hidden sm:flex">
              <button v-if="user.role !== 'user'" @click="changePage('searchCep')"
                :class="['min-w-[150px] text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out py-2', currentPage === 'searchCep' ? 'border-b-2 border-blue-500 text-blue-500' : 'border-b-2 border-transparent']">
                Busca CEP
              </button>

              <button @click="changePage('finances')"
                :class="['min-w-[150px] text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out py-2', currentPage === 'finances' ? 'border-b-2 border-blue-500 text-blue-500' : 'border-b-2 border-transparent']">
                Gestor de Contas

              </button>
            </div>

            <!-- User Dropdowns -->
            <div class="flex items-center space-x-4 hidden sm:flex">

              <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                <template #trigger>
                  <button class="inline-flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                    {{ $page.props.auth.user.current_team.name }}
                    <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                  </button>
                </template>
                <template #content>
                  <div class="w-60">
                    <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                      Team Settings
                    </DropdownLink>
                    <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
                      Create New Team
                    </DropdownLink>

                    <div v-if="$page.props.auth.user.all_teams.length > 1" class="border-t border-gray-200">
                      <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>
                      <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                        <form @submit.prevent="switchToTeam(team)">
                          <DropdownLink as="button">
                            <div class="flex items-center">
                              <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                class="me-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                              </svg>
                              <div>{{ team.name }}</div>
                            </div>
                          </DropdownLink>
                        </form>
                      </template>
                    </div>
                  </div>
                </template>
              </Dropdown>

              <Dropdown align="right" width="48">
                <template #trigger>
                  <button
                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300">
                    <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url"
                      :alt="$page.props.auth.user.name" />
                  </button>
                </template>
                <template #content>

                  <DropdownLink :href="route('settings.index')" v-if="user.role === 'master'" class="">Configurações
                  </DropdownLink>

                  <!-- <DropdownLink :href="route('profile.show')">Meu perfil</DropdownLink> -->
                  <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">API Tokens
                  </DropdownLink>
                  <div class="border-t border-gray-200" />
                  <form @submit.prevent="logout">
                    <DropdownLink as="button">Sair</DropdownLink>
                  </form>
                </template>
              </Dropdown>
            </div>
          </div>
        </div>
      </nav>

      <!-- Responsive Navigation -->
      <div :class="{ 'block': showingMenu, 'hidden': !showingMenu }" class="sm:hidden">
        <div class="hamburger pt-2 pb-3 space-y-1">

          <Link
            class="block w-full ps-3 pe-4 py-2 text-start text-sm font-medium text-gray-700 bg-gray-50 focus:outline-none focus:text-blue-800 focus:bg-indigo-100 focus:border-blue-500 hover:text-gray-400 transition duration-150 ease-in-out"
            :href="route('dashboard')" :active="route().current('dashboard')">Dashboard
          </Link>

          <button v-if="user.role !== 'user'" @click="changePage('searchCep')"
            :class="['block w-full ps-3 pe-4 py-2 text-start text-sm font-medium text-gray-700 bg-gray-50 focus:outline-none focus:text-blue-800 focus:bg-indigo-100 focus:border-blue-500 hover:text-gray-400 transition duration-150 ease-in-out', currentPage === 'searchCep' ? 'border-b-2 border-blue-500 text-blue-500' : 'border-b-2 border-transparent']">
            Busca CEP
          </button>

          <button @click="changePage('finances')"
            :class="['block w-full ps-3 pe-4 py-2 text-start text-sm font-medium text-gray-700 bg-gray-50 focus:outline-none focus:text-blue-800 focus:bg-indigo-100 focus:border-blue-500 hover:text-gray-400 transition duration-150 ease-in-out', currentPage === 'finances' ? 'border-b-2 border-blue-500 text-blue-500' : 'border-b-2 border-transparent']">
            Gestor de Contas
          </button>

          <Link
            class="block w-full ps-3 pe-4 py-2 text-start text-sm font-medium text-gray-700 bg-gray-50 focus:outline-none focus:text-blue-800 focus:bg-indigo-100 focus:border-blue-500 hover:text-gray-400 transition duration-150 ease-in-out"
            :href="route('settings.index')" v-if="user.role === 'master'">Configurações
          </Link>

          <form @submit.prevent="logout">
            <DropdownLink
              class="block w-full pl-0 text-start text-sm font-medium text-gray-700 bg-gray-50 focus:outline-none focus:text-blue-800 focus:bg-indigo-100 focus:border-blue-500 hover:text-gray-400 transition duration-150 ease-in-out"
              as="button">Sair</DropdownLink>
          </form>
        </div>
      </div>

      <!-- Page Content -->
      <main class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center min-h-[calc(100vh-170px)]">
        <SearchCep v-if="currentPage === 'searchCep' && user.role !== 'user'" />
        <Finances :user-id="user.id" v-if="currentPage === 'finances'" />
        <Settings v-if="currentPage === 'settings'" />
      </main>
    </div>

    <!-- Footer -->
    <Footer />
  </div>
</template>