<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-96">
      <RegisterForm @register="handleRegister" />
    </div>
  </div>
</template>

<script setup>
import RegisterForm from '../components/Auth/RegisterForm.vue';
</script>