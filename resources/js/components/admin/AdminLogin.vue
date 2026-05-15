<template>
  <div class="min-h-screen bg-slate-950 flex items-center justify-center px-4 relative overflow-hidden">
    <!-- Background blobs -->
    <div class="absolute w-96 h-96 bg-indigo-600 rounded-full blur-3xl opacity-10 -top-20 -left-20 pointer-events-none"></div>
    <div class="absolute w-80 h-80 bg-violet-600 rounded-full blur-3xl opacity-10 -bottom-10 -right-10 pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-sm">
      <!-- Logo / Brand -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl shadow-2xl shadow-indigo-900 mb-4">
          <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
          </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-white">Administration</h1>
        <p class="text-slate-400 text-sm mt-1">Connectez-vous pour accéder au tableau de bord</p>
      </div>

      <!-- Card -->
      <div class="bg-white rounded-3xl shadow-2xl p-8">
        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="label">Adresse email</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
              </span>
              <input
                v-model="form.email"
                type="email"
                class="input pl-10"
                placeholder="admin@exemple.com"
                autocomplete="email"
                required
              />
            </div>
          </div>

          <div>
            <label class="label">Mot de passe</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
              </span>
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                class="input pl-10 pr-10"
                placeholder="••••••••"
                autocomplete="current-password"
                required
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition"
              >
                <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
              </button>
            </div>
          </div>

          <!-- Error -->
          <div v-if="error" class="flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl px-4 py-3 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ error }}
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold rounded-2xl transition-all duration-200 shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
          >
            <div v-if="loading" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
            {{ loading ? 'Connexion…' : 'Se connecter' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../../composables/useAuth.js';

const router = useRouter();
const { login } = useAuth();

const form = ref({ email: '', password: '' });
const loading = ref(false);
const error = ref('');
const showPassword = ref(false);

async function submit() {
  loading.value = true;
  error.value = '';
  try {
    await login(form.value.email, form.value.password);
    router.push('/admin');
  } catch (e) {
    const msg = e.response?.data?.errors?.email?.[0]
      ?? e.response?.data?.message
      ?? 'Impossible de se connecter.';
    error.value = msg;
  } finally {
    loading.value = false;
  }
}
</script>
