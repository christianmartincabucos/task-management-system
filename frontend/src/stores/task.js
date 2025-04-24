import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

export const useTaskStore = defineStore('task', () => {
  const tasks = ref([])
  const isLoading = ref(false)
  const error = ref(null)
  const filters = ref({
    status: null,
    priority: null,
    search: ''
  })
  
  const filteredTasks = computed(() => {
    return tasks.value.sort((a, b) => a.order - b.order)
  })
  
  const taskStats = computed(() => {
    const total = tasks.value.length
    const completed = tasks.value.filter(task => task.status === 'completed').length
    const pending = total - completed
    
    return {
      total,
      completed,
      pending,
      lowPriority: tasks.value.filter(task => task.priority === 'low').length,
      mediumPriority: tasks.value.filter(task => task.priority === 'medium').length,
      highPriority: tasks.value.filter(task => task.priority === 'high').length
    }
  })
  
  async function fetchTasks() {
    isLoading.value = true
    error.value = null
    
    try {
      let url = '/api/tasks'
      const params = {}
      
      if (filters.value.status) params.status = filters.value.status
      if (filters.value.priority) params.priority = filters.value.priority
      if (filters.value.search) params.search = filters.value.search
      
      const response = await axios.get(url, { params })
      tasks.value = response.data.data
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch tasks'
      throw error.value
    } finally {
      isLoading.value = false
    }
  }
  
  async function createTask(taskData) {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await axios.post('/api/tasks', taskData)
      tasks.value.push(response.data.data)
      return response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to create task'
      throw error.value
    } finally {
      isLoading.value = false
    }
  }
  
  async function updateTask(id, taskData) {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await axios.put(`/api/tasks/${id}`, taskData)
      const index = tasks.value.findIndex(task => task.id === id)
      
      if (index !== -1) {
        tasks.value[index] = response.data.data
      }
      
      return response.data.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update task'
      throw error.value
    } finally {
      isLoading.value = false
    }
  }
  
  async function deleteTask(id) {
    isLoading.value = true
    error.value = null
    
    try {
      await axios.delete(`/api/tasks/${id}`)
      tasks.value = tasks.value.filter(task => task.id !== id)
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete task'
      throw error.value
    } finally {
      isLoading.value = false
    }
  }
  
  async function updateTaskOrder(orderedTasks) {
    try {
      await axios.post('/api/tasks/reorder', { tasks: orderedTasks })
      
      // Update local task order
      orderedTasks.forEach(({ id, order }) => {
        const task = tasks.value.find(t => t.id === id)
        if (task) {
          task.order = order
        }
      })
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update task order'
      throw error.value
    }
  }
  
  function setFilter(filterType, value) {
    filters.value[filterType] = value
    fetchTasks()
  }
  
  function resetFilters() {
    filters.value = {
      status: null,
      priority: null,
      search: ''
    }
    fetchTasks()
  }
  
  return {
    tasks,
    filteredTasks,
    isLoading,
    error,
    filters,
    taskStats,
    fetchTasks,
    createTask,
    updateTask,
    deleteTask,
    updateTaskOrder,
    setFilter,
    resetFilters
  }
})