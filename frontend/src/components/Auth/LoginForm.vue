<template>
  <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4">Login</h1>
    <form @submit.prevent="handleLogin" class="w-full max-w-sm">
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
      <button
        type="submit"
        class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700"
      >
        Login
      </button>
      <p class="mt-4 text-sm text-gray-600">
        Don't have an account? <router-link to="/register" class="text-blue-500">Register</router-link>
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
  const email = ref('');
  const password = ref('');
  const router = useRouter();
  const authStore = useAuthStore();

  const handleLogin = async () => {
    try {
      await authStore.login({ email: email.value, password: password.value });
      router.push('/tasks');
    } catch (error) {
      console.error('Login failed:', error);
    }
  };
</script>

<style scoped>
/* Add any additional styles here */
</style>