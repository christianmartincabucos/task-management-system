<template>
  <div 
    :class="[
      'card p-4 mb-3 border-l-4 transition-all duration-300 cursor-move',
      statusClasses,
      priorityClasses
    ]"
    :data-id="task.id"
  >
    <div class="flex items-start justify-between">
      <div class="flex items-start space-x-3">
        <input 
          type="checkbox" 
          :checked="task.status === 'completed'" 
          @change="toggleStatus"
          class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
        />
        <div>
          <h3 
            :class="[
              'text-lg font-medium', 
              task.status === 'completed' ? 'text-gray-500 line-through' : 'text-gray-900'
            ]"
          >
            {{ task.title }}
          </h3>
          <p 
            v-if="task.description" 
            :class="[
              'text-sm mt-1', 
              task.status === 'completed' ? 'text-gray-400' : 'text-gray-600'
            ]"
          >
            {{ task.description }}
          </p>
          <div class="flex items-center mt-2 space-x-2">
            <span 
              :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                priorityBadgeClasses
              ]"
            >
              {{ task.priority }}
            </span>
          </div>
        </div>
      </div>
      <div class="flex space-x-2">
        <button 
          @click="$emit('edit', task)" 
          class="text-gray-400 hover:text-gray-500"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
          </svg>
        </button>
        <button 
          v-if="isAdmin" 
          @click="$emit('delete', task.id)" 
          class="text-red-400 hover:text-red-500"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth'

const props = defineProps({
  task: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['update', 'edit', 'delete'])

const authStore = useAuthStore()
const isAdmin = computed(() => authStore.user?.is_admin)

const statusClasses = computed(() => {
  return props.task.status === 'completed' 
    ? 'bg-gray-50' 
    : 'bg-white'
})

const priorityClasses = computed(() => {
  switch (props.task.priority) {
    case 'high':
      return 'border-red-500'
    case 'medium':
      return 'border-yellow-500'
    case 'low':
      return 'border-green-500'
    default:
      return 'border-gray-300'
  }
})

const priorityBadgeClasses = computed(() => {
  switch (props.task.priority) {
    case 'high':
      return 'bg-red-100 text-red-800'
    case 'medium':
      return 'bg-yellow-100 text-yellow-800'
    case 'low':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
})

function toggleStatus() {
  const newStatus = props.task.status === 'completed' ? 'pending' : 'completed'
  emit('update', { ...props.task, status: newStatus })
}
</script>