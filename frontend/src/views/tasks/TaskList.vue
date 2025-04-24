<template>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 mb-6">My Tasks</h1>
      
      <TaskStats :stats="taskStore.taskStats" />
      
      <TaskFilters />
      
      <div class="mb-6">
        <button 
          v-if="!showForm" 
          @click="showForm = true" 
          class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75"
        >
          Add New Task
        </button>
        
        <TaskForm 
          v-if="showForm" 
          :task="editingTask" 
          :is-submitting="isSubmitting" 
          @submit="handleSubmit" 
          @cancel="cancelForm"
        />
      </div>
      
      <div v-if="taskStore.isLoading" class="text-center py-10">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
        <p class="mt-2 text-gray-500">Loading tasks...</p>
      </div>
      
      <div v-else-if="taskStore.tasks.length === 0" class="text-center py-10 bg-white rounded-lg shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No tasks found</h3>
        <p class="mt-1 text-gray-500">Get started by creating a new task.</p>
        <div class="mt-6">
          <button 
            @click="showForm = true" 
            class="btn btn-primary"
          >
            Add New Task
          </button>
        </div>
      </div>
      
      <transition-group 
        name="task-list" 
        tag="div" 
        class="task-list"
        @before-enter="beforeEnter"
        @enter="enter"
        @leave="leave"
      >
        <TaskItem 
          v-for="task in taskStore.filteredTasks" 
          :key="task.id" 
          :task="task"
          @update="updateTask"
          @edit="editTask"
          @delete="confirmDeleteTask"
        />
      </transition-group>
      
      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Delete</h3>
          <p class="text-gray-500 mb-6">Are you sure you want to delete this task? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
            <button 
              @click="showDeleteModal = false" 
              class="btn btn-secondary"
            >
              Cancel
            </button>
            <button 
              @click="deleteTask" 
              class="btn btn-danger"
              :disabled="isDeleting"
            >
              {{ isDeleting ? 'Deleting...' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, nextTick } from 'vue'
  import { useTaskStore } from '../../stores/task'
  import TaskItem from '../../components/TaskItem.vue'
  import TaskForm from '../../components/TaskForm.vue'
  import TaskFilters from '../../components/TaskFilters.vue'
  import TaskStats from '../../components/TaskStats.vue'
  import Sortable from 'sortablejs'
  import echo from '../../plugins/echo'
  import { useAuthStore } from '../../stores/auth'
  
  const taskStore = useTaskStore()
  const authStore = useAuthStore()
  
  const showForm = ref(false)
  const editingTask = ref(null)
  const isSubmitting = ref(false)
  const showDeleteModal = ref(false)
  const taskToDelete = ref(null)
  const isDeleting = ref(false)
  let sortable = null
  
  onMounted(async () => {
    await taskStore.fetchTasks()
    
    nextTick(() => {
      initSortable()
      initRealTimeUpdates()
    })
  })
  
  function initSortable() {
    const el = document.querySelector('.task-list')
    if (el) {
      sortable = new Sortable(el, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: handleTaskReorder
      })
    }
  }
  
  function initRealTimeUpdates() {
    if (authStore.user) {
      echo.private(`user.${authStore.user.id}`)
        .listen('TaskUpdated', (e) => {
          const index = taskStore.tasks.findIndex(task => task.id === e.task.id)
          if (index !== -1) {
            taskStore.tasks[index] = e.task
          }
        })
    }
  }
  
  function handleTaskReorder(evt) {
    const tasks = [...taskStore.tasks]
    const movedTask = tasks.splice(evt.oldIndex, 1)[0]
    tasks.splice(evt.newIndex, 0, movedTask)
    
    // Update order property for each task
    const orderedTasks = tasks.map((task, index) => ({
      id: task.id,
      order: index + 1
    }))
    
    taskStore.updateTaskOrder(orderedTasks)
  }
  
  async function handleSubmit(formData) {
    isSubmitting.value = true
    
    try {
      if (editingTask.value) {
        await taskStore.updateTask(editingTask.value.id, formData)
      } else {
        await taskStore.createTask(formData)
      }
      
      showForm.value = false
      editingTask.value = null
    } catch (error) {
      console.error('Error submitting task:', error)
    } finally {
      isSubmitting.value = false
    }
  }
  
  function cancelForm() {
    showForm.value = false
    editingTask.value = null
  }
  
  function editTask(task) {
    editingTask.value = task
    showForm.value = true
  }
  
  async function updateTask(task) {
    try {
      await taskStore.updateTask(task.id, {
        status: task.status
      })
    } catch (error) {
      console.error('Error updating task:', error)
    }
  }
  
  function confirmDeleteTask(id) {
    taskToDelete.value = id
    showDeleteModal.value = true
  }
  
  async function deleteTask() {
    if (!taskToDelete.value) return
    
    isDeleting.value = true
    
    try {
      await taskStore.deleteTask(taskToDelete.value)
      showDeleteModal.value = false
      taskToDelete.value = null
    } catch (error) {
      console.error('Error deleting task:', error)
    } finally {
      isDeleting.value = false
    }
  }
  
  // Animation methods
  function beforeEnter(el) {
    el.style.opacity = 0
    el.style.transform = 'translateY(30px)'
  }
  
  function enter(el, done) {
    const delay = el.dataset.index * 150
    setTimeout(() => {
      el.style.transition = 'all 0.3s ease'
      el.style.opacity = 1
      el.style.transform = 'translateY(0)'
      done()
    }, delay)
  }
  
  function leave(el, done) {
    el.style.transition = 'all 0.3s ease'
    el.style.opacity = 0
    el.style.transform = 'translateY(30px)'
    setTimeout(done, 300)
  }
  </script>
  
  <style scoped>
  .task-list-move {
    transition: transform 0.5s;
  }
  
  .sortable-ghost {
    opacity: 0.5;
    background: #f3f4f6;
  }
  
  .task-list-enter-active,
  .task-list-leave-active {
    transition: all 0.3s;
  }
  
  .task-list-enter-from,
  .task-list-leave-to {
    opacity: 0;
    transform: translateY(30px);
  }
  </style>