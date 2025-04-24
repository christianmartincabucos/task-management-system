import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useAdminStore = defineStore('admin', () => {
  const users = ref([])
  const isLoading = ref(false)
  const error = ref(null)
  const pagination = ref({
    currentPage: 1,
    totalPages: 1,
    perPage: 10
  })
  
  async function fetchUsers(page = 1) {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await axios.get(`/api/admin/users?page=${page}&per_page=${pagination.value.perPage}`)
      users.value = response.data.data
      
      pagination.value = {
        currentPage: response.data.meta.current_page,
        totalPages: response.data.meta.last_page,
        perPage: pagination.value.perPage
      }
      
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch users'
      throw error.value
    } finally {
      isLoading.value = false
    }
  }
  
  return {
    users,
    isLoading,
    error,
    pagination,
    fetchUsers
  }
})