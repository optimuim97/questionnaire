<template>
  <!-- Text / Email / Phone -->
  <input
    v-if="['text', 'email', 'phone'].includes(question.input_type)"
    :type="question.input_type === 'phone' ? 'tel' : question.input_type"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="client-input"
    :placeholder="placeholder"
  />

  <!-- Number -->
  <input
    v-else-if="question.input_type === 'number'"
    type="number"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="client-input"
    placeholder="Saisissez un nombre…"
  />

  <!-- Date -->
  <input
    v-else-if="question.input_type === 'date'"
    type="date"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="client-input"
  />

  <!-- Textarea -->
  <textarea
    v-else-if="question.input_type === 'textarea'"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="client-input resize-none"
    rows="3"
    placeholder="Votre réponse…"
  ></textarea>

  <!-- Select -->
  <div v-else-if="question.input_type === 'select'" class="relative">
    <select
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      class="client-input appearance-none pr-10"
    >
      <option value="" disabled>Choisissez une option…</option>
      <option v-for="opt in question.options" :key="opt" :value="opt">{{ opt }}</option>
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
      </svg>
    </div>
  </div>

  <!-- Radio -->
  <div v-else-if="question.input_type === 'radio'" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
    <label
      v-for="opt in question.options"
      :key="opt"
      class="group flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all duration-150 select-none"
      :class="modelValue === opt
        ? 'border-indigo-500 bg-indigo-50 shadow-sm shadow-indigo-100'
        : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50'"
    >
      <span
        class="flex-shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-150"
        :class="modelValue === opt ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300 group-hover:border-indigo-400'"
      >
        <span v-if="modelValue === opt" class="w-2 h-2 bg-white rounded-full"></span>
      </span>
      <input type="radio" :value="opt" :checked="modelValue === opt" @change="$emit('update:modelValue', opt)" class="sr-only" />
      <span class="text-sm font-medium" :class="modelValue === opt ? 'text-indigo-700' : 'text-gray-600'">{{ opt }}</span>
    </label>
  </div>

  <!-- Checkbox -->
  <div v-else-if="question.input_type === 'checkbox'" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
    <label
      v-for="opt in question.options"
      :key="opt"
      class="group flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all duration-150 select-none"
      :class="modelValue?.includes(opt)
        ? 'border-indigo-500 bg-indigo-50 shadow-sm shadow-indigo-100'
        : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50'"
    >
      <span
        class="flex-shrink-0 w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all duration-150"
        :class="modelValue?.includes(opt) ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300 group-hover:border-indigo-400'"
      >
        <svg v-if="modelValue?.includes(opt)" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
      </span>
      <input type="checkbox" :value="opt" :checked="modelValue?.includes(opt)" @change="toggleCheckbox(opt)" class="sr-only" />
      <span class="text-sm font-medium" :class="modelValue?.includes(opt) ? 'text-indigo-700' : 'text-gray-600'">{{ opt }}</span>
    </label>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({ question: Object, modelValue: [String, Array] });
const emit = defineEmits(['update:modelValue']);

const placeholder = computed(() => ({
  text: 'Votre réponse…', email: 'votre@email.com', phone: '06 00 00 00 00',
}[props.question.input_type] ?? 'Votre réponse…'));

function toggleCheckbox(opt) {
  const current = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
  const idx = current.indexOf(opt);
  idx === -1 ? current.push(opt) : current.splice(idx, 1);
  emit('update:modelValue', current);
}
</script>
