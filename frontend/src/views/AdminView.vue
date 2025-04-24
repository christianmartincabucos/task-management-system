<template>
  <div class="admin-view">
    <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-300">
        <thead>
          <tr>
            <th class="py-2 px-4 border-b">User ID</th>
            <th class="py-2 px-4 border-b">Username</th>
            <th class="py-2 px-4 border-b">Total Tasks</th>
            <th class="py-2 px-4 border-b">Completed Tasks</th>
            <th class="py-2 px-4 border-b">Pending Tasks</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td class="py-2 px-4 border-b">{{ user.id }}</td>
            <td class="py-2 px-4 border-b">{{ user.username }}</td>
            <td class="py-2 px-4 border-b">{{ user.totalTasks }}</td>
            <td class="py-2 px-4 border-b">{{ user.completedTasks }}</td>
            <td class="py-2 px-4 border-b">{{ user.pendingTasks }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { useAuthStore } from '../stores/auth';

export default defineComponent({
  name: 'AdminView',
  setup() {
    const authStore = useAuthStore();

    const users = ref([]);

    const fetchUsers = async () => {
      const response = await fetch('/api/admin/users', {
        headers: {
          Authorization: `Bearer ${authStore.token}`,
        },
      });
      users.value = await response.json();
    };

    onMounted(() => {
      fetchUsers();
    });

    return {
      users,
    };
  },
});
</script>

<style scoped>
.admin-view {
  padding: 20px;
}
</style>