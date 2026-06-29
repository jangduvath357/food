<template>
  <div class="admin-page">
    <aside class="sidebar">
      <div class="sidebar-logo">
        <div class="logo-circle">C</div>
        <span>Crave Admin</span>
      </div>
      <nav class="sidebar-nav">
        <RouterLink to="/admin" class="nav-item">📊 Dashboard</RouterLink>
        <RouterLink to="/admin/orders" class="nav-item">📋 Orders</RouterLink>
        <RouterLink to="/admin/menu" class="nav-item">🍕 Menu</RouterLink>
        <RouterLink to="/admin/reservations" class="nav-item">📅 Reservations</RouterLink>
      </nav>
      <button class="btn-logout" @click="handleLogout">🚪 Logout</button>
    </aside>

    <main class="admin-main">
      <div class="admin-header">
        <h1>Dashboard</h1>
        <p>Welcome back, {{ authStore.user?.name }}!</p>
      </div>

      <!-- STATS -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📦</div>
          <div class="stat-info">
            <h3>{{ stats.totalOrders }}</h3>
            <p>Total Orders</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">💰</div>
          <div class="stat-info">
            <h3>${{ stats.totalRevenue }}</h3>
            <p>Total Revenue</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">👥</div>
          <div class="stat-info">
            <h3>{{ stats.totalUsers }}</h3>
            <p>Total Customers</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📅</div>
          <div class="stat-info">
            <h3>{{ stats.totalReservations }}</h3>
            <p>Reservations</p>
          </div>
        </div>
      </div>

      <!-- RECENT ORDERS -->
      <div class="section-card">
        <h2>Recent Orders</h2>
        <div class="loading" v-if="loading">Loading...</div>
        <table class="data-table" v-else>
          <thead>
            <tr>
              <th>#ID</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Type</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in recentOrders" :key="order.id">
              <td>#{{ order.id }}</td>
              <td>{{ order.user?.name || 'Guest' }}</td>
              <td>${{ order.total }}</td>
              <td><span class="type-tag">{{ order.order_type }}</span></td>
              <td><span class="status-badge" :class="order.status">{{ order.status }}</span></td>
              <td>{{ formatDate(order.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import api from '../../api'

const router    = useRouter()
const authStore = useAuthStore()
const loading   = ref(true)

const stats = ref({
  totalOrders: 0, totalRevenue: 0,
  totalUsers: 0,  totalReservations: 0
})

const recentOrders = ref([])

async function fetchData() {
  loading.value = true
  try {
    const [ordersRes, reservationsRes] = await Promise.all([
      api.get('/admin/orders'),
      api.get('/admin/reservations')
    ])
    const orders = ordersRes.data
    recentOrders.value         = orders.slice(0, 5)
    stats.value.totalOrders    = orders.length
    stats.value.totalRevenue   = orders.reduce((s, o) => s + parseFloat(o.total), 0).toFixed(2)
    stats.value.totalReservations = reservationsRes.data.length
  } catch {} finally {
    loading.value = false
  }
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}

onMounted(fetchData)
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }
.admin-page { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; font-family: 'Inter', sans-serif; background: #F5E6E0; }

/* SIDEBAR */
.sidebar { background: #2E1A0E; display: flex; flex-direction: column; padding: 1.5rem; position: sticky; top: 0; height: 100vh; }
.sidebar-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 2rem; }
.logo-circle { width: 36px; height: 36px; background: #C4622A; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
.sidebar-logo span { font-size: 16px; font-weight: 700; color: #fff; }
.sidebar-nav { display: flex; flex-direction: column; gap: 4px; flex: 1; }
.nav-item { display: block; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 500; color: rgba(247,240,230,0.6); transition: all 0.2s; }
.nav-item:hover { background: rgba(247,240,230,0.1); color: #fff; }
.nav-item.router-link-active { background: #C4622A; color: #fff; }
.btn-logout { padding: 10px 14px; border-radius: 8px; border: none; background: rgba(239,68,68,0.15); color: #FCA5A5; font-size: 13px; cursor: pointer; text-align: left; transition: background 0.2s; }
.btn-logout:hover { background: rgba(239,68,68,0.25); }

/* MAIN */
.admin-main { padding: 2rem; overflow-y: auto; }
.admin-header { margin-bottom: 2rem; }
.admin-header h1 { font-size: 28px; font-weight: 700; color: #2E1A0E; }
.admin-header p { font-size: 14px; color: #8B6355; margin-top: 4px; }

/* STATS */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.2rem; margin-bottom: 2rem; }
.stat-card { background: #fff; border-radius: 12px; padding: 1.5rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 8px rgba(46,26,14,0.06); }
.stat-icon { font-size: 2rem; }
.stat-info h3 { font-size: 24px; font-weight: 700; color: #2E1A0E; }
.stat-info p { font-size: 12px; color: #8B6355; margin-top: 2px; }

/* TABLE */
.section-card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(46,26,14,0.06); }
.section-card h2 { font-size: 18px; font-weight: 700; color: #2E1A0E; margin-bottom: 1.2rem; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #8B6355; padding: 10px 12px; text-align: left; border-bottom: 1px solid #F5E6E0; }
.data-table td { font-size: 13px; color: #2E1A0E; padding: 12px; border-bottom: 1px solid #F9F5F3; }
.data-table tr:last-child td { border-bottom: none; }
.type-tag { font-size: 11px; background: #F5E6E0; color: #C4622A; border-radius: 100px; padding: 3px 10px; text-transform: capitalize; }
.status-badge { font-size: 11px; font-weight: 500; border-radius: 100px; padding: 4px 10px; text-transform: capitalize; }
.status-badge.pending   { background: #FEF3C7; color: #92400E; }
.status-badge.preparing { background: #DBEAFE; color: #1E40AF; }
.status-badge.delivered { background: #D1FAE5; color: #065F46; }
.status-badge.cancelled { background: #FEE2E2; color: #B91C1C; }
.loading { text-align: center; padding: 2rem; color: #8B6355; }

@media (max-width: 900px) {
  .admin-page { grid-template-columns: 1fr; }
  .sidebar { position: static; height: auto; flex-direction: row; flex-wrap: wrap; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>