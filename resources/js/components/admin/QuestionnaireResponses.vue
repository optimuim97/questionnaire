<template>
  <div>
    <div class="flex items-center gap-4 mb-6">
      <router-link to="/admin" class="text-gray-400 hover:text-gray-600">← Retour</router-link>
      <h1 class="text-2xl font-bold text-gray-800">Réponses — {{ title }}</h1>
    </div>

    <div v-if="loading" class="text-center py-16 text-gray-400">Chargement…</div>
    <div v-else-if="responses.length === 0" class="text-center py-16 text-gray-400">Aucune réponse pour l'instant.</div>

    <div v-else class="space-y-4">
      <div v-for="r in responses" :key="r.id" class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <div>
            <span class="font-medium text-gray-800">{{ r.respondent_name || 'Anonyme' }}</span>
            <span v-if="r.respondent_email" class="text-sm text-gray-400 ml-2">{{ r.respondent_email }}</span>
          </div>
          <span class="text-xs text-gray-400">{{ formatDate(r.created_at) }}</span>
        </div>
        <dl class="space-y-2">
          <div v-for="answer in r.answers" :key="answer.id" class="grid grid-cols-3 gap-2 text-sm">
            <dt class="text-gray-500 col-span-1">{{ answer.question?.title }}</dt>
            <dd class="col-span-2 text-gray-800 font-medium">
              {{ Array.isArray(answer.value) && answer.value.length > 1 ? answer.value.join(', ') : answer.value[0] }}
            </dd>
          </div>
        </dl>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const responses = ref([]);
const title = ref('');
const loading = ref(true);

onMounted(async () => {
  const [qRes, rRes] = await Promise.all([
    axios.get(`/api/admin/questionnaires/${route.params.id}`),
    axios.get(`/api/admin/questionnaires/${route.params.id}/responses`),
  ]);
  title.value = qRes.data.title;
  responses.value = rRes.data;
  loading.value = false;
});

function formatDate(dt) {
  return new Date(dt).toLocaleString('fr-FR', { dateStyle: 'short', timeStyle: 'short' });
}
</script>
