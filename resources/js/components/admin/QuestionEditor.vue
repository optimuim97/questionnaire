<template>
  <div class="space-y-4">
    <div>
      <label class="label">Titre de la question <span class="text-red-500">*</span></label>
      <input :value="modelValue.title" @input="update('title', $event.target.value)" type="text" class="input" placeholder="Ex : Quel est votre prénom ?" required />
    </div>
    <div>
      <label class="label">Explication (optionnel)</label>
      <input :value="modelValue.explanation" @input="update('explanation', $event.target.value)" type="text" class="input" placeholder="Aide visible sous la question" />
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="label">Type de réponse</label>
        <select :value="modelValue.input_type" @change="update('input_type', $event.target.value)" class="input">
          <option value="text">Texte</option>
          <option value="number">Nombre</option>
          <option value="email">Email</option>
          <option value="phone">Téléphone</option>
          <option value="date">Date</option>
          <option value="textarea">Texte long</option>
          <option value="select">Liste déroulante</option>
          <option value="radio">Choix unique (radio)</option>
          <option value="checkbox">Cases à cocher</option>
        </select>
      </div>
      <div class="flex items-end pb-1">
        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
          <input :checked="modelValue.required" @change="update('required', $event.target.checked)" type="checkbox" class="w-4 h-4 text-indigo-600" />
          Réponse obligatoire
        </label>
      </div>
    </div>

    <!-- Options for select/radio/checkbox -->
    <div v-if="['select', 'radio', 'checkbox'].includes(modelValue.input_type)">
      <label class="label">Options (une par ligne)</label>
      <textarea
        :value="optionsText"
        @input="updateOptions($event.target.value)"
        class="input font-mono text-sm"
        rows="4"
        placeholder="Option 1&#10;Option 2&#10;Option 3"
      ></textarea>
      <p class="text-xs text-gray-400 mt-1">Saisissez chaque option sur une nouvelle ligne.</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({ modelValue: Object });
const emit = defineEmits(['update:modelValue']);

function update(key, value) {
  emit('update:modelValue', { ...props.modelValue, [key]: value });
}

const optionsText = computed(() => (props.modelValue.options ?? []).join('\n'));

function updateOptions(text) {
  const opts = text.split('\n').map(s => s.trim()).filter(Boolean);
  update('options', opts);
}
</script>
