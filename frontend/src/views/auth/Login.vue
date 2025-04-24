<template>
    <div>
      <form @submit.prevent="login" class="mt-8 space-y-6">
        <div v-if="error" class="bg-red-50 p-4 rounded-md">
          <p class="text-sm text-red-700">{{ error }}</p>
        </div>
        
        <div class="rounded-md shadow-sm -space-y-px">
          <p>{{error}}</p>
        </div>
        
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email" class="sr-only">Email address</label>
            <input 
              id="email" 
              v-model="form.email" 
              type="email" 
              required 
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm" 
              placeholder="Email address"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input 
              id="password" 
              v-model="form.password" 
              type="password" 
              required 
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm" 
              placeholder="Password"
            />
          </div>
        </div>
  
        <div>
          <button 
            type="submit" 
            class="group relative w-full flex justify-center py-3 px-6 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75"
            :disabled="isLoading"
          >
            <span v-if="isLoading">Loading...</span>
            <span v-else>Sign in</span>
          </button>
        </div>
        
        <div class="text-center">
          <router-link to="/auth/register" class="text-sm text-primary-600 hover:text-primary-500">
            Don't have an account? Register
          </router-link>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive } from 'vue'
  import { useRouter } from 'vue-router'
  import { useAuthStore } from '../../stores/auth'
  
  const router = useRouter()
  const authStore = useAuthStore()
  
  const form = reactive({
    email: '',
    password: ''
  })
  
  const isLoading = ref(false)
  const error = ref('')
  
  async function login() {
    isLoading.value = true
    error.value = ''
    
    try {
      await authStore.login(form)
      router.push({ name: 'tasks' })
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to login. Please check your credentials.'
    } finally {
      isLoading.value = false
    }
  }
  </script>