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
        <h1>All Orders</h1>
        <p>Manage and update customer orders</p>
      </div>

      <!-- FILTERS -->
      <div class="filter-row">
        <button
          v-for="status in statuses" :key="status"
          class="filter-btn"
          :class="{ active: selectedStatus === status }"
          @click="selectedStatus = status"
        >{{ status }}</button>
      </div>

      <div class="loading" v-if="loading">Loading orders...</div>

      <div class="section-card" v-else>
        <table class="data-table">
          <thead>
            <tr>
              <th>#ID</th>
              <th>Customer</th>
              <th>Items</th>
              <th>Total</th>
              <th>Type</th>
              <th>Status</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in filteredOrders" :key="order.id">
              <td>#{{ order.id }}</td>
              <td>{{ order.user?.name || 'Guest' }}</td>
              <td>{{ order.items?.length || 0 }} items</td>
              <td>${{ order.total }}</td>
              <td><span class="type-tag">{{ order.order_type }}</span></td>
              <td><span class="status-badge" :class="order.status">{{ order.status }}</span></td>
              <td>{{ formatDate(order.created_at) }}</td>
              <td>
                <select class="status-select" :value="order.status" @change="updateStatus(order.id, $event.target.value)">
                  <option value="pending">Pending</option>
                  <option value="preparing">Preparing</option>
                  <option value="delivered">Delivered</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="empty" v-if="filteredOrders.length === 0">No orders found.</div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import api from '../../api'

const router    = useRouter()
const authStore = useAuthStore()
const loading   = ref(true)
const orders    = ref([])
const selectedStatus = ref('All')
const statuses  = ['All', 'pending', 'preparing', 'delivered', 'cancelled']

const filteredOrders = computed(() => {
  if (selectedStatus.value === 'All') return orders.value
  return orders.value.filter(o => o.status === selectedStatus.value)
})

async function fetchOrders() {
  loading.value = true
  try {
    const res  = await api.get('/admin/orders')
    orders.value = res.data
  } catch {} finally {
    loading.value = false
  }
}

async function updateStatus(id, status) {
  try {
    await api.put(`/admin/orders/${id}/status`, { status })
    const order = orders.value.find(o => o.id === id)
    if (order) order.status = status
  } catch {}
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}

onMounted(fetchOrders)
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }
.admin-page { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; font-family: 'Inter', sans-serif; background: #F5E6E0; }
.sidebar { background: #2E1A0E; display: flex; flex-direction: column; padding: 1.5rem; position: sticky; top: 0; height: 100vh; }
.sidebar-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 2rem; }
.logo-circle { width: 36px; height: 36px; background: #C4622A; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
.sidebar-logo span { font-size: 16px; font-weight: 700; color: #fff; }
.sidebar-nav { display: flex; flex-direction: column; gap: 4px; flex: 1; }
.nav-item { display: block; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 500; color: rgba(247,240,230,0.6); transition: all 0.2s; }
.nav-item:hover { background: rgba(247,240,230,0.1); color: #fff; }
.nav-item.router-link-active { background: #C4622A; color: #fff; }
.btn-logout { padding: 10px 14px; border-radius: 8px; border: none; background: rgba(239,68,68,0.15); color: #FCA5A5; font-size: 13px; cursor: pointer; text-align: left; }
.btn-logout:hover { background: rgba(239,68,68,0.25); }
.admin-main { padding: 2rem; }
.admin-header { margin-bottom: 1.5rem; }
.admin-header h1 { font-size: 28px; font-weight: 700; color: #2E1A0E; }
.admin-header p { font-size: 14px; color: #8B6355; margin-top: 4px; }
.filter-row { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.filter-btn { padding: 7px 18px; border: 1px solid rgba(46,26,14,0.2); border-radius: 100px; background: #fff; font-size: 12px; cursor: pointer; color: #2E1A0E; transition: all 0.2s; }
.filter-btn.active { background: #2E1A0E; color: #fff; border-color: #2E1A0E; }
.section-card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(46,26,14,0.06); overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; min-width: 800px; }
.data-table th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #8B6355; padding: 10px 12px; text-align: left; border-bottom: 1px solid #F5E6E0; }
.data-table td { font-size: 13px; color: #2E1A0E; padding: 12px; border-bottom: 1px solid #F9F5F3; }
.data-table tr:last-child td { border-bottom: none; }
.type-tag { font-size: 11px; background: #F5E6E0; color: #C4622A; border-radius: 100px; padding: 3px 10px; text-transform: capitalize; }
.status-badge { font-size: 11px; font-weight: 500; border-radius: 100px; padding: 4px 10px; text-transform: capitalize; }
.status-badge.pending   { background: #FEF3C7; color: #92400E; }
.status-badge.preparing { background: #DBEAFE; color: #1E40AF; }
.status-badge.delivered { background: #D1FAE5; color: #065F46; }
.status-badge.cancelled { background: #FEE2E2; color: #B91C1C; }
.status-select { padding: 6px 10px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 12px; cursor: pointer; outline: none; background: #fff; }
.loading { text-align: center; padding: 2rem; color: #8B6355; }
.empty { text-align: center; padding: 2rem; color: #9CA3AF; font-size: 14px; }
</style>