<template>
  <div class="admin-dashboard">
    <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
    <div class="user-stats mb-6">
      <h2 class="text-xl font-semibold">User Statistics</h2>
      <ul>
        <li v-for="user in users" :key="user.id" class="mb-2">
          <span>{{ user.name }}: </span>
          <span>Total Tasks: {{ user.totalTasks }}</span>
          <span> | Completed: {{ user.completedTasks }}</span>
          <span> | Pending: {{ user.pendingTasks }}</span>
        </li>
      </ul>
    </div>
    <div class="task-list">
      <h2 class="text-xl font-semibold">All Tasks</h2>
      <TaskList :tasks="tasks" />
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import TaskList from '../Tasks/TaskList.vue';
import { useStore } from 'pinia';
import { useAdminStore } from '../../stores/admin';

export default {
  components: {
    TaskList,
  },
  setup() {
    const store = useStore();
    const adminStore = useAdminStore();
    const users = ref([]);
    const tasks = ref([]);

    onMounted(async () => {
      await adminStore.fetchUsers();
      await adminStore.fetchTasks();
      users.value = adminStore.users;
      tasks.value = adminStore.tasks;
    });

    return {
      users,
      tasks,
    };
  },
};
</script>

<style scoped>
.admin-dashboard {
  padding: 20px;
}
</style>