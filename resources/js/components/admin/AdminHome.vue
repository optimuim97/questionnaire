<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Mes questionnaires</h1>

    <div v-if="loading" class="text-center py-16 text-gray-400">Chargement…</div>

    <div v-else-if="questionnaires.length === 0" class="text-center py-16">
      <p class="text-gray-400 mb-4">Aucun questionnaire pour l'instant.</p>
      <router-link to="/admin/questionnaires/create" class="btn-primary">Créer le premier</router-link>
    </div>

    <div v-else class="grid gap-4">
      <div
        v-for="q in questionnaires"
        :key="q.id"
        class="bg-white rounded-xl border border-gray-200 p-5 flex items-center justify-between shadow-sm hover:shadow transition"
      >
        <div>
          <div class="flex items-center gap-2">
            <h2 class="font-semibold text-gray-800">{{ q.title }}</h2>
            <span
              :class="q.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
              class="text-xs px-2 py-0.5 rounded-full font-medium"
            >{{ q.is_active ? 'Actif' : 'Inactif' }}</span>
          </div>
          <p class="text-sm text-gray-400 mt-1">
            {{ q.questions_count }} question{{ q.questions_count !== 1 ? 's' : '' }} ·
            {{ q.responses_count }} réponse{{ q.responses_count !== 1 ? 's' : '' }}
          </p>
        </div>
        <div class="flex gap-2">
          <a
            :href="`/q/${q.id}`"
            target="_blank"
            class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-600"
          >Aperçu</a>
          <router-link
            :to="`/admin/questionnaires/${q.id}/responses`"
            class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-600"
          >Réponses</router-link>
          <router-link
            :to="`/admin/questionnaires/${q.id}/edit`"
            class="text-sm px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
          >Modifier</router-link>
          <button
            @click="deleteQuestionnaire(q)"
            class="text-sm px-3 py-1.5 border border-red-200 text-red-500 rounded-lg hover:bg-red-50 transition"
          >Supprimer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const questionnaires = ref([]);
const loading = ref(true);

onMounted(async () => {
  const { data } = await axios.get('/api/admin/questionnaires');
  questionnaires.value = data;
  loading.value = false;
});

async function deleteQuestionnaire(q) {
  if (!confirm(`Supprimer "${q.title}" ?`)) return;
  await axios.delete(`/api/admin/questionnaires/${q.id}`);
  questionnaires.value = questionnaires.value.filter(item => item.id !== q.id);
}
</script>
