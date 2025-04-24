<template>
  <div class="bg-white shadow rounded-lg p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label for="status-filter" class="form-label">Status</label>
        <select 
          id="status-filter" 
          v-model="selectedStatus" 
          class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 form-input"
        >
          <option value="">All</option>
          <option value="pending">Pending</option>
          <option value="completed">Completed</option>
        </select>
      </div>
      
      <div>
        <label for="priority-filter" class="form-label">Priority</label>
        <select 
          id="priority-filter" 
          v-model="selectedPriority" 
          class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 form-input"
        >
          <option value="">All</option>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
      </div>
      
      <div>
        <label for="search" class="form-label">Search</label>
        <input 
          id="search" 
          v-model="searchQuery" 
          type="text" 
          class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 form-input" 
          placeholder="Search tasks..."
        />
      </div>
    </div>
    
    <div class="mt-4 flex justify-end">
      <button 
        @click="resetFilters" 
        class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75"
      >
        Reset Filters
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useTaskStore } from '@/stores/task'

const taskStore = useTaskStore()

const selectedStatus = ref('')
const selectedPriority = ref('')
const searchQuery = ref('')

watch(selectedStatus, (newValue) => {
  taskStore.setFilter('status', newValue || null)
})

watch(selectedPriority, (newValue) => {
  taskStore.setFilter('priority', newValue || null)
})

watch(searchQuery, (newValue) => {
  // Debounce search
  const timeoutId = setTimeout(() => {
    taskStore.setFilter('search', newValue)
  }, 300)
  
  return () => clearTimeout(timeoutId)
})

function resetFilters() {
  selectedStatus.value = ''
  selectedPriority.value = ''
  searchQuery.value = ''
  taskStore.resetFilters()
}
</script>