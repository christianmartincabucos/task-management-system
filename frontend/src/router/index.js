// import { createRouter, createWebHistory } from 'vue-router';
// import HomeView from '../views/HomeView.vue';
// import LoginView from '../views/LoginView.vue';
// import RegisterView from '../views/RegisterView.vue';
// import TasksView from '../views/TasksView.vue';
// import AdminView from '../views/AdminView.vue';

// const routes = [
//   {
//     path: '/',
//     name: 'home',
//     component: HomeView,
//   },
//   {
//     path: '/login',
//     name: 'login',
//     component: LoginView,
//   },
//   {
//     path: '/register',
//     name: 'register',
//     component: RegisterView,
//   },
//   {
//     path: '/tasks',
//     name: 'tasks',
//     component: TasksView,
//   },
//   {
//     path: '/admin',
//     name: 'admin',
//     component: AdminView,
//     meta: { requiresAdmin: true },
//   },
// ];

// const router = createRouter({
//   history: createWebHistory(process.env.BASE_URL),
//   routes,
// });

// router.beforeEach((to, from, next) => {
//   const isAdmin = localStorage.getItem('isAdmin') === 'true';
//   if (to.meta.requiresAdmin && !isAdmin) {
//     next({ name: 'tasks' });
//   } else {
//     next();
//   }
// });

// export default router;
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Layouts
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

// Views
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'
import TaskList from '@/views/tasks/TaskList.vue'
import AdminDashboard from '@/views/admin/Dashboard.vue'

const routes = [
  {
    path: '/',
    component: DefaultLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'tasks',
        component: TaskList
      },
      {
        path: 'admin',
        name: 'admin',
        component: AdminDashboard,
        meta: { requiresAdmin: true }
      }
    ]
  },
  {
    path: '/auth',
    component: AuthLayout,
    meta: { guest: true },
    children: [
      {
        path: 'login',
        name: 'login',
        component: Login
      },
      {
        path: 'register',
        name: 'register',
        component: Register
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' })
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next({ name: 'tasks' })
  } else if (to.meta.requiresAdmin && !authStore.user?.is_admin) {
    next({ name: 'tasks' })
  } else {
    next()
  }
})

export default router