<template>
  <div class="container mx-auto mt-10">
    <h1 class="text-3xl font-bold text-center">Welcome to the Task Management System</h1>
    <p class="mt-4 text-center">Manage your tasks efficiently and effectively.</p>
    <div class="mt-8 flex justify-center">
      <router-link to="/login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Login</router-link>
      <router-link to="/register" class="ml-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Register</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeView',
};
</script>

<style scoped>
.container {
  max-width: 600px;
}
</style>