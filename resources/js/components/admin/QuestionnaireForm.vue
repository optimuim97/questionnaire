<template>
  <div class="max-w-xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Nouveau questionnaire</h1>

    <form @submit.prevent="submit" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-5">
      <div>
        <label class="label">Titre <span class="text-red-500">*</span></label>
        <input v-model="form.title" type="text" class="input" placeholder="Ex : Satisfaction client" required />
      </div>
      <div>
        <label class="label">Description</label>
        <textarea v-model="form.description" class="input" rows="3" placeholder="Courte présentation du questionnaire…"></textarea>
      </div>
      <div class="flex items-center gap-2">
        <input v-model="form.is_active" type="checkbox" id="is_active" class="w-4 h-4 text-indigo-600" />
        <label for="is_active" class="text-sm text-gray-700">Activer immédiatement</label>
      </div>

      <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>

      <div class="flex gap-3 pt-2">
        <button type="submit" :disabled="loading" class="btn-primary">
          {{ loading ? 'Création…' : 'Créer et ajouter des questions' }}
        </button>
        <router-link to="/admin" class="btn-secondary">Annuler</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const loading = ref(false);
const error = ref('');
const form = ref({ title: '', description: '', is_active: true });

async function submit() {
  loading.value = true;
  error.value = '';
  try {
    const { data } = await axios.post('/api/admin/questionnaires', form.value);
    router.push(`/admin/questionnaires/${data.id}/edit`);
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Une erreur est survenue.';
  } finally {
    loading.value = false;
  }
}
</script>
