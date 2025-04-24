<template>
  <div class="max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Register</h2>
    <form @submit.prevent="handleRegister">
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input
          type="text"
          id="name"
          v-model="form.name"
          required
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50"
        />
      </div>
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input
          type="email"
          id="email"
          v-model="email"
          required
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
        />
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          required
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
        />
      </div>
      <div class="mb-4">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input
          type="password"
          id="password_confirmation"
          v-model="form.password_confirmation"
          required
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
        />
      </div>
      <button
        type="submit"
        class="w-full bg-blue-600 text-white font-bold py-2 rounded-md hover:bg-blue-700"
      >
        Register
      </button>
    </form>
    <p class="mt-4 text-sm text-gray-600">
      Already have an account? <router-link to="/login" class="text-blue-600">Login here</router-link>.
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
  const router = useRouter();
  const authStore = useAuthStore();
  const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  const handleRegister = async () => {
    try {
      await authStore.register(form.value);
      router.push('/tasks');
    } catch (error) {
      console.error('Registration failed:', error);
    }
  };
</script>

<style scoped>
/* Add any additional styles here */
</style>