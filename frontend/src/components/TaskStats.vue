<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-lg font-medium text-gray-900">Task Status</h3>
      <div class="mt-2 grid grid-cols-2 gap-4">
        <div>
          <p class="text-sm text-gray-500">Pending</p>
          <p class="text-2xl font-semibold text-primary-600">{{ stats.pending }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500">Completed</p>
          <p class="text-2xl font-semibold text-green-600">{{ stats.completed }}</p>
        </div>
      </div>
      <div class="mt-4">
        <div class="w-full bg-gray-200 rounded-full h-2.5">
          <div 
            class="bg-primary-600 h-2.5 rounded-full" 
            :style="{ width: `${completionPercentage}%` }"
          ></div>
        </div>
        <p class="text-xs text-gray-500 mt-1">{{ completionPercentage }}% completed</p>
      </div>
    </div>
    
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-lg font-medium text-gray-900">Priority Breakdown</h3>
      <div class="mt-2 space-y-2">
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-500">High</span>
          <span class="text-sm font-medium text-red-600">{{ stats.highPriority }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div 
            class="bg-red-500 h-2 rounded-full" 
            :style="{ width: `${highPriorityPercentage}%` }"
          ></div>
        </div>
        
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-500">Medium</span>
          <span class="text-sm font-medium text-yellow-600">{{ stats.mediumPriority }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div 
            class="bg-yellow-500 h-2 rounded-full" 
            :style="{ width: `${mediumPriorityPercentage}%` }"
          ></div>
        </div>
        
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-500">Low</span>
          <span class="text-sm font-medium text-green-600">{{ stats.lowPriority }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div 
            class="bg-green-500 h-2 rounded-full" 
            :style="{ width: `${lowPriorityPercentage}%` }"
          ></div>
        </div>
      </div>
    </div>
    
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-lg font-medium text-gray-900">Total Tasks</h3>
      <p class="text-4xl font-bold text-gray-900 mt-2">{{ stats.total }}</p>
      <p class="text-sm text-gray-500 mt-1">Drag and drop to reorder tasks</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  stats: {
    type: Object,
    required: true
  }
})

const completionPercentage = computed(() => {
  if (props.stats.total === 0) return 0
  return Math.round((props.stats.completed / props.stats.total) * 100)
})

const highPriorityPercentage = computed(() => {
  if (props.stats.total === 0) return 0
  return Math.round((props.stats.highPriority / props.stats.total) * 100)
})

const mediumPriorityPercentage = computed(() => {
  if (props.stats.total === 0) return 0
  return Math.round((props.stats.mediumPriority / props.stats.total) * 100)
})

const lowPriorityPercentage = computed(() => {
  if (props.stats.total === 0) return 0
  return Math.round((props.stats.lowPriority / props.stats.total) * 100)
})
</script>