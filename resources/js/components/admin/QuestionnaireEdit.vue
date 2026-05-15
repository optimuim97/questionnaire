<template>
  <div>
    <div class="flex items-center gap-4 mb-6">
      <router-link to="/admin" class="text-gray-400 hover:text-gray-600">← Retour</router-link>
      <h1 class="text-2xl font-bold text-gray-800">{{ questionnaire.title }}</h1>
      <span
        :class="questionnaire.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
        class="text-xs px-2 py-0.5 rounded-full font-medium"
      >{{ questionnaire.is_active ? 'Actif' : 'Inactif' }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Infos générales -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-4">
          <h2 class="font-semibold text-gray-700">Informations</h2>
          <div>
            <label class="label">Titre</label>
            <input v-model="questionnaire.title" type="text" class="input" />
          </div>
          <div>
            <label class="label">Description</label>
            <textarea v-model="questionnaire.description" class="input" rows="3"></textarea>
          </div>
          <div class="flex items-center gap-2">
            <input v-model="questionnaire.is_active" type="checkbox" id="is_active" class="w-4 h-4 text-indigo-600" />
            <label for="is_active" class="text-sm text-gray-700">Actif</label>
          </div>
          <div class="flex gap-2 pt-1">
            <button @click="saveQuestionnaire" class="btn-primary text-sm">Enregistrer</button>
            <a :href="`/q/${questionnaire.id}`" target="_blank" class="btn-secondary text-sm">Aperçu</a>
          </div>
          <div v-if="saveMsg" class="text-green-600 text-sm">{{ saveMsg }}</div>
        </div>
      </div>

      <!-- Questions -->
      <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
          <h2 class="font-semibold text-gray-700">Questions ({{ questions.length }})</h2>
          <button @click="showAddForm = true" class="btn-primary text-sm">+ Ajouter</button>
        </div>

        <!-- Add form -->
        <div v-if="showAddForm" class="bg-white rounded-xl border border-indigo-200 p-5 shadow-sm space-y-4">
          <h3 class="font-medium text-gray-700">Nouvelle question</h3>
          <QuestionEditor v-model="newQuestion" />
          <div class="flex gap-2">
            <button @click="addQuestion" :disabled="addingQuestion" class="btn-primary text-sm">
              {{ addingQuestion ? 'Ajout…' : 'Ajouter' }}
            </button>
            <button @click="cancelAdd" class="btn-secondary text-sm">Annuler</button>
          </div>
        </div>

        <!-- Questions list -->
        <div v-if="questions.length === 0 && !showAddForm" class="bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-400">
          Aucune question. Cliquez sur "+ Ajouter" pour commencer.
        </div>

        <div v-for="(q, index) in questions" :key="q.id" class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
          <div v-if="editingId !== q.id">
            <div class="flex items-start justify-between gap-4">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-xs font-medium bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded">{{ labelType(q.input_type) }}</span>
                  <span v-if="q.required" class="text-xs text-red-400">Requis</span>
                </div>
                <p class="font-medium text-gray-800">{{ index + 1 }}. {{ q.title }}</p>
                <p v-if="q.explanation" class="text-sm text-gray-500 mt-1">{{ q.explanation }}</p>
                <div v-if="q.options?.length" class="mt-2 flex flex-wrap gap-1">
                  <span v-for="opt in q.options" :key="opt" class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-600">{{ opt }}</span>
                </div>
              </div>
              <div class="flex gap-2 flex-shrink-0">
                <button @click="startEdit(q)" class="text-sm text-indigo-600 hover:text-indigo-800">Modifier</button>
                <button @click="deleteQuestion(q)" class="text-sm text-red-400 hover:text-red-600">Supprimer</button>
              </div>
            </div>
          </div>

          <div v-else class="space-y-4">
            <QuestionEditor v-model="editForm" />
            <div class="flex gap-2">
              <button @click="saveEdit(q)" class="btn-primary text-sm">Enregistrer</button>
              <button @click="editingId = null" class="btn-secondary text-sm">Annuler</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import QuestionEditor from './QuestionEditor.vue';

const route = useRoute();
const questionnaire = ref({ title: '', description: '', is_active: true });
const questions = ref([]);
const showAddForm = ref(false);
const addingQuestion = ref(false);
const editingId = ref(null);
const editForm = ref({});
const saveMsg = ref('');
const blankQuestion = () => ({
  title: '', explanation: '', input_type: 'text', options: [], required: true,
});
const newQuestion = ref(blankQuestion());

onMounted(async () => {
  const { data } = await axios.get(`/api/admin/questionnaires/${route.params.id}`);
  questionnaire.value = data;
  questions.value = data.questions ?? [];
});

async function saveQuestionnaire() {
  await axios.put(`/api/admin/questionnaires/${questionnaire.value.id}`, {
    title: questionnaire.value.title,
    description: questionnaire.value.description,
    is_active: questionnaire.value.is_active,
  });
  saveMsg.value = 'Enregistré ✓';
  setTimeout(() => (saveMsg.value = ''), 2000);
}

async function addQuestion() {
  addingQuestion.value = true;
  try {
    const { data } = await axios.post(
      `/api/admin/questionnaires/${questionnaire.value.id}/questions`,
      newQuestion.value,
    );
    questions.value.push(data);
    newQuestion.value = blankQuestion();
    showAddForm.value = false;
  } finally {
    addingQuestion.value = false;
  }
}

function cancelAdd() {
  newQuestion.value = blankQuestion();
  showAddForm.value = false;
}

function startEdit(q) {
  editingId.value = q.id;
  editForm.value = { ...q, options: q.options ? [...q.options] : [] };
}

async function saveEdit(q) {
  const { data } = await axios.put(`/api/admin/questions/${q.id}`, editForm.value);
  const idx = questions.value.findIndex(item => item.id === q.id);
  questions.value[idx] = data;
  editingId.value = null;
}

async function deleteQuestion(q) {
  if (!confirm('Supprimer cette question ?')) return;
  await axios.delete(`/api/admin/questions/${q.id}`);
  questions.value = questions.value.filter(item => item.id !== q.id);
}

const typeLabels = {
  text: 'Texte', number: 'Nombre', email: 'Email', phone: 'Téléphone',
  date: 'Date', textarea: 'Texte long', select: 'Liste', radio: 'Choix unique', checkbox: 'Cases à cocher',
};
function labelType(type) { return typeLabels[type] ?? type; }
</script>
