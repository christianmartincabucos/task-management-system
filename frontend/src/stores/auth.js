import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import router from '../router'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('token') || null)
  const user = ref(JSON.parse(localStorage.getItem('user') || 'null'))
  
  const isAuthenticated = computed(() => !!token.value)
  
  async function register(credentials) {
    try {
      const response = await axios.post('/api/register', credentials)
      setToken(response.data.token)
      setUser(response.data.user)
      return response
    } catch (error) {
      throw error
    }
  }
  
  async function login(credentials) {
    try {
      const response = await axios.post('/api/login', credentials)
      setToken(response.data.token)
      setUser(response.data.user)
      return response
    } catch (error) {
      throw error
    }
  }
  
  function logout() {
    if (token.value) {
      axios.post('/api/logout')
        .finally(() => {
          clearAuth()
          router.push({ name: 'login' })
        })
    }
  }
  
  function setToken(newToken) {
    token.value = newToken
    localStorage.setItem('token', newToken)
    axios.defaults.headers.common['Authorization'] = `Bearer ${newToken}`
  }
  
  function setUser(newUser) {
    user.value = newUser
    localStorage.setItem('user', JSON.stringify(newUser))
  }
  
  function clearAuth() {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    delete axios.defaults.headers.common['Authorization']
  }
  
  // Initialize axios header if token exists
  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }
  
  return {
    token,
    user,
    isAuthenticated,
    register,
    login,
    logout,
    setUser
  }
})