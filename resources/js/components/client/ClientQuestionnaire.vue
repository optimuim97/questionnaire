<template>
  <!-- ── Loading ─────────────────────────────────────────────── -->
  <div v-if="loading" class="min-h-screen bg-slate-950 flex items-center justify-center">
    <div class="flex flex-col items-center gap-4">
      <div class="w-12 h-12 rounded-full border-4 border-indigo-500 border-t-transparent animate-spin"></div>
      <p class="text-slate-400 text-sm">Chargement du questionnaire…</p>
    </div>
  </div>

  <!-- ── Not found ────────────────────────────────────────────── -->
  <div v-else-if="!questionnaire" class="min-h-screen bg-slate-950 flex items-center justify-center">
    <div class="text-center px-6">
      <img :src="humaaans.sitting[2]" alt="" class="w-48 mx-auto mb-6 opacity-80" />
      <h2 class="text-2xl font-bold text-white mb-2">Questionnaire introuvable</h2>
      <p class="text-slate-400">Ce questionnaire n'existe pas ou n'est plus disponible.</p>
    </div>
  </div>

  <!-- ── Thank you ────────────────────────────────────────────── -->
  <div v-else-if="submitted" class="min-h-screen bg-slate-950 flex items-center justify-center overflow-hidden relative px-4">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="relative z-10 text-center max-w-md">
      <!-- Humaaans illustration -->
      <div class="relative inline-block mb-6">
        <img :src="humaaans.standing[6]" alt="" class="w-52 mx-auto drop-shadow-2xl" />
        <!-- Checkmark badge overlay -->
        <div class="absolute -top-2 -right-2 w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center shadow-xl shadow-emerald-900">
          <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
        </div>
      </div>
      <h1 class="text-4xl font-extrabold text-white mb-3">Merci beaucoup !</h1>
      <p class="text-slate-300 text-base leading-relaxed">Vos réponses ont bien été enregistrées.<br>Nous reviendrons vers vous très prochainement.</p>
    </div>
  </div>

  <!-- ── Welcome / Identity ───────────────────────────────────── -->
  <div v-else-if="currentPage === -1" class="min-h-screen bg-slate-950 flex items-center justify-center px-4 py-12 relative overflow-hidden">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="relative z-10 w-full max-w-5xl">
      <!-- Brand badge -->
      <div class="flex justify-center mb-8">
        <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur text-white text-sm font-medium px-4 py-2 rounded-full border border-white/20">
          <span class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></span>
          Questionnaire
        </span>
      </div>

      <!-- Two-column layout -->
      <div class="flex flex-col lg:flex-row items-center gap-8">

        <!-- Left — Humaaans illustration panel -->
        <div class="hidden lg:flex flex-col items-center justify-center flex-1 gap-6">
          <!-- Main figure -->
          <img :src="humaaans.standing[0]" alt="" class="w-64 xl:w-72 drop-shadow-2xl" />
          <!-- Floating mini figures -->
          <div class="flex items-end gap-4">
            <img :src="humaaans.sitting[0]" alt="" class="w-24 opacity-70" />
            <img :src="humaaans.standing[4]" alt="" class="w-20 opacity-60" />
            <img :src="humaaans.sitting[1]" alt="" class="w-24 opacity-70" />
          </div>
          <p class="text-white/30 text-xs text-center">Illustrations by <a href="https://humaaans.com" target="_blank" class="underline hover:text-white/60 transition">Humaaans</a></p>
        </div>

        <!-- Right — Form card -->
        <div class="w-full lg:max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden">
          <!-- Header gradient -->
          <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-8 relative overflow-hidden">
            <!-- Small decorative figure in header -->
            <img
              :src="humaaans.standing[2]"
              alt=""
              class="absolute -right-6 bottom-0 h-28 opacity-20 pointer-events-none"
            />
            <h1 class="text-2xl md:text-3xl font-extrabold text-white leading-tight relative">{{ questionnaire.title }}</h1>
            <p v-if="questionnaire.description" class="text-indigo-100 mt-2 text-sm leading-relaxed relative">{{ questionnaire.description }}</p>
            <div class="mt-4 flex items-center gap-4 text-indigo-200 text-xs relative">
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                {{ questions.length }} questions
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                ~{{ Math.ceil(questions.length * 0.4) }} minutes
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                {{ pages.length }} pages
              </span>
            </div>
          </div>

          <!-- Form body -->
          <div class="px-8 py-8">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Votre identité (optionnel)</p>
            <div class="space-y-4 mb-8">
              <div>
                <label class="label">Nom complet</label>
                <input v-model="respondentName" type="text" class="input" placeholder="Jean Dupont" />
              </div>
              <div>
                <label class="label">Adresse email</label>
                <input v-model="respondentEmail" type="email" class="input" placeholder="jean@exemple.fr" />
              </div>
            </div>

            <button @click="startQuestionnaire" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-base rounded-2xl transition-all duration-200 shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
              Commencer le questionnaire
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ── Questions stepper (4 per page) ───────────────────────── -->
  <div v-else class="min-h-screen bg-slate-950 flex flex-col relative overflow-hidden">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <!-- Top bar -->
    <div class="relative z-10 flex-shrink-0">
      <div class="max-w-3xl mx-auto px-4 py-4 flex items-center gap-4">
        <!-- Step segments -->
        <div class="flex items-center gap-1.5 flex-1">
          <div
            v-for="(_, i) in pages"
            :key="i"
            class="h-1.5 rounded-full transition-all duration-500"
            :class="[
              i < currentPage ? 'bg-indigo-400' : i === currentPage ? 'bg-white' : 'bg-white/20',
              i === currentPage ? 'flex-[2]' : 'flex-1'
            ]"
          ></div>
        </div>
        <span class="text-white/60 text-sm flex-shrink-0 font-medium">
          Page {{ currentPage + 1 }} / {{ pages.length }}
        </span>
      </div>
    </div>

    <!-- Questions card -->
    <div class="relative z-10 flex-1 flex items-start justify-center px-4 pb-8 pt-2">
      <div class="w-full max-w-3xl">
        <transition :name="slideDir" mode="out-in">
          <div :key="currentPage" class="bg-white rounded-3xl shadow-2xl overflow-hidden">

            <!-- Card header -->
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 md:px-8 py-5 flex items-center justify-between relative overflow-hidden">
              <!-- Tiny Humaaans figure in corner -->
              <img
                :src="humaaans.standing[currentPage % humaaans.standing.length]"
                alt=""
                class="absolute right-0 bottom-0 h-20 opacity-20 pointer-events-none"
              />
              <div class="relative">
                <p class="text-indigo-200 text-xs font-semibold uppercase tracking-widest">Section {{ currentPage + 1 }}</p>
                <h2 class="text-white font-bold text-lg mt-0.5">{{ sectionLabel }}</h2>
              </div>
              <div class="text-right relative">
                <span class="text-indigo-200 text-xs font-medium">Page {{ currentPage + 1 }} sur {{ pages.length }}</span>
                <div class="mt-1 w-20 h-1 bg-indigo-400/40 rounded-full overflow-hidden">
                  <div class="h-full bg-white rounded-full transition-all duration-500" :style="{ width: progressPercent + '%' }"></div>
                </div>
              </div>
            </div>

            <!-- Questions list -->
            <div class="divide-y divide-gray-100">
              <div
                v-for="(q, idx) in currentPageQuestions"
                :key="q.id"
                class="px-6 md:px-8 py-7"
              >
                <!-- Question header -->
                <div class="flex items-start gap-4 mb-5">
                  <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-sm font-bold shadow-md shadow-indigo-200">
                    {{ globalStart + idx + 1 }}
                  </div>
                  <div class="flex-1 pt-0.5">
                    <p class="font-semibold text-gray-800 leading-snug">
                      {{ q.title }}
                      <span v-if="q.required" class="text-rose-400 ml-1 font-normal">*</span>
                    </p>
                    <p v-if="q.explanation" class="text-sm text-gray-400 mt-1">{{ q.explanation }}</p>
                  </div>
                </div>

                <!-- Input -->
                <div class="pl-12">
                  <QuestionInput :question="q" v-model="answers[q.id]" />
                  <p v-if="errors[q.id]" class="text-rose-500 text-xs mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ errors[q.id] }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Navigation -->
            <div class="px-6 md:px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
              <button
                v-if="currentPage > 0"
                @click="prevPage"
                class="flex items-center gap-2 text-gray-500 hover:text-gray-700 font-semibold text-sm transition"
              >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18"/></svg>
                Précédent
              </button>
              <div v-else></div>

              <button
                v-if="currentPage < pages.length - 1"
                @click="nextPage"
                class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold rounded-2xl transition-all duration-200 shadow-md shadow-indigo-200 text-sm"
              >
                Continuer
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
              </button>

              <button
                v-else
                @click="submitResponses"
                :disabled="submitting"
                class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold rounded-2xl transition-all duration-200 shadow-md shadow-emerald-200 text-sm disabled:opacity-60 disabled:cursor-not-allowed"
              >
                <svg v-if="!submitting" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <div v-else class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                {{ submitting ? 'Envoi…' : 'Soumettre mes réponses' }}
              </button>
            </div>

          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import QuestionInput from './QuestionInput.vue';

const QUESTIONS_PER_PAGE = 4;
const HUMAAANS_BASE = 'https://raw.githubusercontent.com/Calinou/humaaans/master/Flat%20Assets/Humaaans';

const humaaans = {
  standing: Array.from({ length: 24 }, (_, i) => `${HUMAAANS_BASE}/standing-${i + 1}.svg`),
  sitting:  Array.from({ length: 8 },  (_, i) => `${HUMAAANS_BASE}/sitting-${i + 1}.svg`),
};

const route = useRoute();
const questionnaire = ref(null);
const questions = ref([]);
const loading = ref(true);
const submitted = ref(false);
const submitting = ref(false);
const currentPage = ref(-1);
const slideDir = ref('slide-forward');
const answers = ref({});
const errors = ref({});
const respondentName = ref('');
const respondentEmail = ref('');

const pages = computed(() => {
  const chunks = [];
  for (let i = 0; i < questions.value.length; i += QUESTIONS_PER_PAGE) {
    chunks.push(questions.value.slice(i, i + QUESTIONS_PER_PAGE));
  }
  return chunks;
});

const currentPageQuestions = computed(() => pages.value[currentPage.value] ?? []);
const globalStart = computed(() => currentPage.value * QUESTIONS_PER_PAGE);
const progressPercent = computed(() => ((currentPage.value + 1) / pages.value.length) * 100);

const sectionLabels = [
  'Informations générales', 'Identité visuelle', 'Catalogue produits',
  'Moyens de paiement', 'Livraison', 'Dashboard & Gestion',
  'Marketing', 'Technique & Projet', 'Contact',
];
const sectionLabel = computed(() => sectionLabels[currentPage.value] ?? `Partie ${currentPage.value + 1}`);

onMounted(async () => {
  try {
    const { data } = await axios.get(`/api/questionnaires/${route.params.id}`);
    questionnaire.value = data;
    questions.value = data.questions ?? [];
    questions.value.forEach(q => {
      answers.value[q.id] = q.input_type === 'checkbox' ? [] : '';
    });
  } catch {
    questionnaire.value = null;
  } finally {
    loading.value = false;
  }
});

function startQuestionnaire() { currentPage.value = 0; }

function validatePage() {
  let valid = true;
  errors.value = {};
  for (const q of currentPageQuestions.value) {
    if (!q.required) continue;
    const val = answers.value[q.id];
    if (q.input_type === 'checkbox') {
      if (!val || val.length === 0) { errors.value[q.id] = 'Veuillez sélectionner au moins une option.'; valid = false; }
    } else if (!val || String(val).trim() === '') {
      errors.value[q.id] = 'Cette question est obligatoire.'; valid = false;
    }
  }
  return valid;
}

function nextPage() {
  if (!validatePage()) return;
  slideDir.value = 'slide-forward';
  currentPage.value++;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevPage() {
  slideDir.value = 'slide-back';
  errors.value = {};
  currentPage.value--;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function submitResponses() {
  if (!validatePage()) return;
  submitting.value = true;
  try {
    await axios.post(`/api/questionnaires/${questionnaire.value.id}/responses`, {
      respondent_name: respondentName.value || null,
      respondent_email: respondentEmail.value || null,
      answers: questions.value.map(q => ({ question_id: q.id, value: answers.value[q.id] })),
    });
    submitted.value = true;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  } finally {
    submitting.value = false;
  }
}
</script>

<style scoped>
.blob {
  position: absolute;
  border-radius: 9999px;
  filter: blur(80px);
  opacity: 0.15;
  pointer-events: none;
}
.blob-1 { width: 500px; height: 500px; background: #6366f1; top: -150px; left: -100px; }
.blob-2 { width: 400px; height: 400px; background: #8b5cf6; bottom: -100px; right: -80px; }

.slide-forward-enter-active, .slide-forward-leave-active { transition: all 0.35s cubic-bezier(0.4,0,0.2,1); }
.slide-forward-enter-from { opacity: 0; transform: translateX(50px); }
.slide-forward-leave-to  { opacity: 0; transform: translateX(-50px); }

.slide-back-enter-active, .slide-back-leave-active { transition: all 0.35s cubic-bezier(0.4,0,0.2,1); }
.slide-back-enter-from { opacity: 0; transform: translateX(-50px); }
.slide-back-leave-to  { opacity: 0; transform: translateX(50px); }
</style>
