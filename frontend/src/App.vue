<template>
  <div class="min-h-screen bg-gray-100">
    <router-view />
  </div>
</template>

<script setup>
import { onBeforeUnmount } from 'vue'
import { useTaskStore } from '@/stores/task'

const taskStore = useTaskStore()

onBeforeUnmount(() => {
  taskStore.disconnectWebSockets()
})
</script>