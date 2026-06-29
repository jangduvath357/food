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
        <h1>Reservations</h1>
        <p>Manage table bookings</p>
      </div>

      <div class="filter-row">
        <button
          v-for="status in statuses" :key="status"
          class="filter-btn"
          :class="{ active: selectedStatus === status }"
          @click="selectedStatus = status"
        >{{ status }}</button>
      </div>

      <div class="loading" v-if="loading">Loading reservations...</div>

      <div class="section-card" v-else>
        <table class="data-table">
          <thead>
            <tr>
              <th>#ID</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Date</th>
              <th>Time</th>
              <th>Guests</th>
              <th>Note</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="res in filteredReservations" :key="res.id">
              <td>#{{ res.id }}</td>
              <td>{{ res.name }}</td>
              <td>{{ res.phone }}</td>
              <td>{{ res.date }}</td>
              <td>{{ res.time }}</td>
              <td>👥 {{ res.guests }}</td>
              <td>{{ res.note || '—' }}</td>
              <td><span class="status-badge" :class="res.status">{{ res.status }}</span></td>
              <td>
                <select class="status-select" :value="res.status" @change="updateStatus(res.id, $event.target.value)">
                  <option value="pending">Pending</option>
                  <option value="confirmed">Confirmed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="empty" v-if="filteredReservations.length === 0">No reservations found.</div>
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
const reservations = ref([])
const selectedStatus = ref('All')
const statuses = ['All', 'pending', 'confirmed', 'cancelled']

const filteredReservations = computed(() => {
  if (selectedStatus.value === 'All') return reservations.value
  return reservations.value.filter(r => r.status === selectedStatus.value)
})

async function fetchReservations() {
  loading.value = true
  try {
    const res = await api.get('/admin/reservations')
    reservations.value = res.data
  } catch {} finally {
    loading.value = false
  }
}

async function updateStatus(id, status) {
  try {
    await api.put(`/admin/reservations/${id}/status`, { status })
    const res = reservations.value.find(r => r.id === id)
    if (res) res.status = status
  } catch {}
}

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}

onMounted(fetchReservations)
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
.admin-main { padding: 2rem; overflow-x: auto; }
.admin-header { margin-bottom: 1.5rem; }
.admin-header h1 { font-size: 28px; font-weight: 700; color: #2E1A0E; }
.admin-header p { font-size: 14px; color: #8B6355; margin-top: 4px; }
.filter-row { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.filter-btn { padding: 7px 18px; border: 1px solid rgba(46,26,14,0.2); border-radius: 100px; background: #fff; font-size: 12px; cursor: pointer; color: #2E1A0E; transition: all 0.2s; }
.filter-btn.active { background: #2E1A0E; color: #fff; border-color: #2E1A0E; }
.section-card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(46,26,14,0.06); overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; min-width: 900px; }
.data-table th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #8B6355; padding: 10px 12px; text-align: left; border-bottom: 1px solid #F5E6E0; }
.data-table td { font-size: 13px; color: #2E1A0E; padding: 12px; border-bottom: 1px solid #F9F5F3; }
.data-table tr:last-child td { border-bottom: none; }
.status-badge { font-size: 11px; font-weight: 500; border-radius: 100px; padding: 4px 10px; text-transform: capitalize; }
.status-badge.pending   { background: #FEF3C7; color: #92400E; }
.status-badge.confirmed { background: #D1FAE5; color: #065F46; }
.status-badge.cancelled { background: #FEE2E2; color: #B91C1C; }
.status-select { padding: 6px 10px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 12px; cursor: pointer; outline: none; }
.loading { text-align: center; padding: 2rem; color: #8B6355; }
.empty { text-align: center; padding: 2rem; color: #9CA3AF; font-size: 14px; }
</style>