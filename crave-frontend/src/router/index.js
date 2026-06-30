import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  // PUBLIC
  { path: '/',            component: () => import('../views/user/Home.vue') },
  { path: '/menu',        component: () => import('../views/user/Menu.vue') },
  { path: '/about',       component: () => import('../views/user/About.vue') },
  { path: '/contact',     component: () => import('../views/user/Contact.vue') },
  { path: '/blog',        component: () => import('../views/user/Blog.vue') },
  { path: '/login',       component: () => import('../views/user/Login.vue') },
  { path: '/register',    component: () => import('../views/user/Register.vue') },

  // CUSTOMER
  { path: '/order',       component: () => import('../views/user/Order.vue'),       meta: { requiresAuth: true } },
  { path: '/reservation', component: () => import('../views/user/Reservation.vue'), meta: { requiresAuth: true } },
  { path: '/profile',     component: () => import('../views/user/Profile.vue'),     meta: { requiresAuth: true } },

  // ADMIN
  { path: '/admin',              component: () => import('../views/admin/Dashboard.vue'),    meta: { requiresAdmin: true } },
  { path: '/dashboard',         redirect: '/admin' },
  { path: '/admin/orders',       component: () => import('../views/admin/Orders.vue'),       meta: { requiresAdmin: true } },
  { path: '/admin/menu',         component: () => import('../views/admin/MenuManage.vue'),   meta: { requiresAdmin: true } },
  { path: '/admin/reservations', component: () => import('../views/admin/Reservations.vue'), meta: { requiresAdmin: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from) => {
  const auth = useAuthStore()
  if (to.meta.requiresAdmin && !auth.isAdmin) return '/login'
  if (to.meta.requiresAuth && !auth.isLoggedIn) return '/login'
  return true
})

export default router