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

    <div class=""> <!-- This ensures the content takes up the remaining space -->
      <!-- Navigation -->
      <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <Link :href="route('dashboard')" class="flex items-center">
            <ApplicationMark class="block h-9 w-auto" />
            </Link>

            <!-- Navigation Links -->
            <div class="hidden sm:flex space-x-8">
              <button v-if="user.role !== 'user'" @click="changePage('searchCep')"
                :class="['flex-1 min-w-[150px] text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out py-2', currentPage === 'searchCep' ? 'border-b-2 border-blue-500 text-blue-500' : 'border-b-2 border-transparent']">
                Busca CEP
              </button>

              <button @click="changePage('finances')"
                :class="['flex-1 min-w-[150px] text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out py-2', currentPage === 'finances' ? 'border-b-2 border-blue-500 text-blue-500' : 'border-b-2 border-transparent']">
                Gestão de Contas
              </button>
            </div>

            <!-- User Dropdowns -->
            <div class="flex items-center space-x-4">
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
      <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard
          </ResponsiveNavLink>
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
          <div class="flex items-center px-4">
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
              <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url"
                :alt="$page.props.auth.user.name" />
            </div>
            <div>
              <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
              <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
            </div>
          </div>
          <div class="mt-3 space-y-1">
            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">Meu perfil
            </ResponsiveNavLink>
            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')"
              :active="route().current('api-tokens.index')">API Tokens</ResponsiveNavLink>
            <form method="POST" @submit.prevent="logout">
              <ResponsiveNavLink as="button">Sair</ResponsiveNavLink>
            </form>
            <template v-if="$page.props.jetstream.hasTeamFeatures">
              <div class="border-t border-gray-200" />
              <div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>
              <ResponsiveNavLink :href="route('teams.show', $page.props.auth.user.current_team)"
                :active="route().current('teams.show')">Team Settings</ResponsiveNavLink>
              <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')"
                :active="route().current('teams.create')">Create New Team</ResponsiveNavLink>
              <div v-if="$page.props.auth.user.all_teams.length > 1" class="border-t border-gray-200">
                <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>
                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                  <form @submit.prevent="switchToTeam(team)">
                    <ResponsiveNavLink as="button">
                      <div class="flex items-center">
                        <svg v-if="team.id == $page.props.auth.user.current_team_id" class="me-2 h-5 w-5 text-green-400"
                          xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>{{ team.name }}</div>
                      </div>
                    </ResponsiveNavLink>
                  </form>
                </template>
              </div>
            </template>
          </div>
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
