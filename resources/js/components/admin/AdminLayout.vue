<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white border-b border-gray-200 shadow-sm">
      <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <router-link to="/admin" class="text-xl font-bold text-indigo-600">
          Questionnaires
        </router-link>

        <div class="flex items-center gap-3">
          <router-link
            to="/admin/questionnaires/create"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition"
          >
            + Nouveau
          </router-link>

          <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
            <span class="text-sm text-gray-500 hidden sm:block">{{ user?.name }}</span>
            <button
              @click="handleLogout"
              :disabled="loggingOut"
              title="Se déconnecter"
              class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-rose-600 transition px-2 py-1.5 rounded-lg hover:bg-rose-50"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
              </svg>
              <span class="hidden sm:block">Déconnexion</span>
            </button>
          </div>
        </div>
      </div>
    </nav>
    <main class="max-w-6xl mx-auto px-4 py-8">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../../composables/useAuth.js';

const router = useRouter();
const { user, logout } = useAuth();
const loggingOut = ref(false);

async function handleLogout() {
  loggingOut.value = true;
  await logout();
  router.push('/admin/login');
}
</script>
