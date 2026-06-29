<template>
  <div class="profile-page">

    <!-- NAVBAR -->
    <nav class="navbar">
      <RouterLink to="/" class="logo">
        <div class="logo-circle">C</div>
        <span>Crave</span>
      </RouterLink>
      <div class="nav-links">
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/menu">Menu</RouterLink>
        <RouterLink to="/reservation">Reservation</RouterLink>
      </div>
      <div class="nav-actions">
        <RouterLink to="/order" class="cart-btn">
          🛒 <span class="cart-count">{{ cartStore.totalItems }}</span>
        </RouterLink>
        <button class="btn-logout" @click="handleLogout">Logout</button>
      </div>
    </nav>

    <div class="profile-layout">

      <!-- LEFT SIDEBAR -->
      <div class="profile-sidebar">
        <div class="avatar-wrap">
          <div class="avatar">{{ initials }}</div>
          <h3>{{ authStore.user?.name }}</h3>
          <p>{{ authStore.user?.email }}</p>
          <span class="role-badge">{{ authStore.user?.role }}</span>
        </div>

        <div class="sidebar-menu">
          <button
            v-for="tab in tabs" :key="tab.key"
            class="sidebar-btn"
            :class="{ active: activeTab === tab.key }"
            @click="activeTab = tab.key"
          >
            {{ tab.icon }} {{ tab.label }}
          </button>
        </div>
      </div>

      <!-- RIGHT CONTENT -->
      <div class="profile-content">

        <!-- PROFILE INFO -->
        <div v-if="activeTab === 'profile'">
          <h2>My Profile</h2>
          <div class="alert success" v-if="profileSuccess">{{ profileSuccess }}</div>
          <div class="alert error"   v-if="profileError">{{ profileError }}</div>

          <div class="form-card">
            <div class="form-group">
              <label>Name</label>
              <input v-model="profileForm.name" type="text" placeholder="Your name" />
            </div>
            <div class="form-group">
              <label>Phone</label>
              <input v-model="profileForm.phone" type="tel" placeholder="Your phone number" />
            </div>
            <div class="form-group">
              <label>Address</label>
              <input v-model="profileForm.address" type="text" placeholder="Your address" />
            </div>
            <button class="btn-save" @click="updateProfile" :disabled="profileLoading">
              {{ profileLoading ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </div>

        <!-- ORDER HISTORY -->
        <div v-if="activeTab === 'orders'">
          <h2>My Orders</h2>
          <div class="loading" v-if="ordersLoading">Loading orders...</div>
          <div class="empty-state" v-else-if="orders.length === 0">
            <div class="empty-icon">📦</div>
            <p>No orders yet</p>
            <RouterLink to="/menu" class="btn-browse">Browse Menu</RouterLink>
          </div>
          <div class="orders-list" v-else>
            <div class="order-card" v-for="order in orders" :key="order.id">
              <div class="order-header">
                <div>
                  <h4>Order #{{ order.id }}</h4>
                  <p class="order-date">{{ formatDate(order.created_at) }}</p>
                </div>
                <div class="order-right">
                  <span class="status-badge" :class="order.status">{{ order.status }}</span>
                  <span class="order-total">${{ order.total }}</span>
                </div>
              </div>
              <div class="order-items">
                <span v-for="item in order.items" :key="item.id" class="order-item-tag">
                  {{ item.menu_item?.name || 'Item' }} x{{ item.quantity }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- RESERVATIONS -->
        <div v-if="activeTab === 'reservations'">
          <h2>My Reservations</h2>
          <div class="loading" v-if="reservationsLoading">Loading reservations...</div>
          <div class="empty-state" v-else-if="reservations.length === 0">
            <div class="empty-icon">📅</div>
            <p>No reservations yet</p>
            <RouterLink to="/reservation" class="btn-browse">Book a Table</RouterLink>
          </div>
          <div class="reservations-list" v-else>
            <div class="reservation-card" v-for="res in reservations" :key="res.id">
              <div class="res-header">
                <div>
                  <h4>Reservation #{{ res.id }}</h4>
                  <p class="res-date">📅 {{ res.date }} at {{ res.time }}</p>
                  <p class="res-guests">👥 {{ res.guests }} guests</p>
                </div>
                <span class="status-badge" :class="res.status">{{ res.status }}</span>
              </div>
              <p class="res-note" v-if="res.note">📝 {{ res.note }}</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { useCartStore } from '../../stores/cart'
import api from '../../api'

const router    = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()

const activeTab = ref('profile')
const tabs = [
  { key: 'profile',      label: 'My Profile',      icon: '👤' },
  { key: 'orders',       label: 'My Orders',        icon: '📦' },
  { key: 'reservations', label: 'My Reservations',  icon: '📅' },
]

const initials = computed(() => {
  const name = authStore.user?.name || 'U'
  return name.split(' ').map(n => n[0]).join('').toUpperCase()
})

// PROFILE
const profileLoading = ref(false)
const profileSuccess = ref('')
const profileError   = ref('')
const profileForm    = ref({
  name:    authStore.user?.name    || '',
  phone:   authStore.user?.phone   || '',
  address: authStore.user?.address || ''
})

async function updateProfile() {
  profileSuccess.value = ''
  profileError.value   = ''
  profileLoading.value = true
  try {
    const res = await api.put('/profile', profileForm.value)
    authStore.user = res.data.user
    localStorage.setItem('user', JSON.stringify(res.data.user))
    profileSuccess.value = 'Profile updated successfully! ✅'
  } catch (err) {
    profileError.value = err.response?.data?.message || 'Failed to update profile.'
  } finally {
    profileLoading.value = false
  }
}

// ORDERS
const orders        = ref([])
const ordersLoading = ref(false)

async function fetchOrders() {
  ordersLoading.value = true
  try {
    const res  = await api.get('/orders/my')
    orders.value = res.data
  } catch {} finally {
    ordersLoading.value = false
  }
}

// RESERVATIONS
const reservations        = ref([])
const reservationsLoading = ref(false)

async function fetchReservations() {
  reservationsLoading.value = true
  try {
    const res = await api.get('/reservations/my')
    reservations.value = res.data
  } catch {} finally {
    reservationsLoading.value = false
  }
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric', month: 'short', day: 'numeric'
  })
}

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}

onMounted(() => {
  fetchOrders()
  fetchReservations()
})
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.profile-page { min-height: 100vh; background: #F5E6E0; font-family: 'Inter', sans-serif; }

/* NAVBAR */
.navbar { background: #fff; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; box-shadow: 0 1px 8px rgba(0,0,0,0.06); }
.logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
.logo-circle { width: 30px; height: 30px; background: #F97316; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; }
.logo span { font-size: 18px; font-weight: 700; color: #111827; }
.nav-links { display: flex; gap: 1.5rem; }
.nav-links a { text-decoration: none; color: #6B7280; font-size: 13px; font-weight: 500; }
.nav-links a.router-link-active { color: #F97316; }
.nav-actions { display: flex; align-items: center; gap: 1rem; }
.cart-btn { position: relative; font-size: 20px; text-decoration: none; }
.cart-count { position: absolute; top: -6px; right: -8px; background: #F97316; color: #fff; font-size: 10px; font-weight: 700; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; }
.btn-logout { font-size: 12px; font-weight: 500; color: #EF4444; background: #FEE2E2; border: 1px solid #FECACA; border-radius: 100px; padding: 6px 16px; cursor: pointer; transition: background 0.2s; }
.btn-logout:hover { background: #FCA5A5; }

/* LAYOUT */
.profile-layout { display: grid; grid-template-columns: 280px 1fr; gap: 2rem; padding: 2rem 3rem; max-width: 1200px; margin: 0 auto; }

/* SIDEBAR */
.profile-sidebar { display: flex; flex-direction: column; gap: 1rem; }

.avatar-wrap { background: #fff; border-radius: 16px; padding: 2rem; text-align: center; box-shadow: 0 2px 12px rgba(46,26,14,0.08); }
.avatar { width: 72px; height: 72px; background: #C4622A; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; color: #fff; margin: 0 auto 1rem; }
.avatar-wrap h3 { font-size: 16px; font-weight: 700; color: #2E1A0E; margin-bottom: 4px; }
.avatar-wrap p { font-size: 12px; color: #8B6355; margin-bottom: 8px; }
.role-badge { font-size: 11px; font-weight: 500; background: #F5E6E0; color: #C4622A; border-radius: 100px; padding: 3px 12px; text-transform: capitalize; }

.sidebar-menu { background: #fff; border-radius: 16px; padding: 1rem; box-shadow: 0 2px 12px rgba(46,26,14,0.08); display: flex; flex-direction: column; gap: 4px; }
.sidebar-btn { width: 100%; padding: 10px 14px; border: none; border-radius: 8px; background: transparent; font-size: 13px; font-weight: 500; color: #6B7280; cursor: pointer; text-align: left; transition: all 0.2s; }
.sidebar-btn:hover { background: #F5E6E0; color: #2E1A0E; }
.sidebar-btn.active { background: #2E1A0E; color: #fff; }

/* CONTENT */
.profile-content h2 { font-size: 22px; font-weight: 700; color: #2E1A0E; margin-bottom: 1.5rem; }

.form-card { background: #fff; border-radius: 16px; padding: 2rem; box-shadow: 0 2px 12px rgba(46,26,14,0.08); }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; font-size: 12px; font-weight: 600; color: #2E1A0E; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
.form-group input { width: 100%; padding: 11px 14px; border: 1.5px solid #E5E7EB; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s; }
.form-group input:focus { border-color: #C4622A; }

.btn-save { padding: 12px 28px; background: #2E1A0E; color: #fff; border: none; border-radius: 100px; font-size: 13px; font-weight: 600; cursor: pointer; margin-top: 0.5rem; transition: background 0.2s; }
.btn-save:hover { background: #C4622A; }
.btn-save:disabled { background: #9CA3AF; cursor: not-allowed; }

/* ORDERS */
.orders-list { display: flex; flex-direction: column; gap: 1rem; }
.order-card { background: #fff; border-radius: 12px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(46,26,14,0.06); }
.order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.8rem; }
.order-header h4 { font-size: 14px; font-weight: 600; color: #2E1A0E; }
.order-date { font-size: 12px; color: #8B6355; margin-top: 2px; }
.order-right { text-align: right; display: flex; flex-direction: column; gap: 4px; align-items: flex-end; }
.order-total { font-size: 15px; font-weight: 700; color: #C4622A; }
.order-items { display: flex; flex-wrap: wrap; gap: 6px; }
.order-item-tag { font-size: 11px; background: #F5E6E0; color: #2E1A0E; border-radius: 100px; padding: 3px 10px; }

/* RESERVATIONS */
.reservations-list { display: flex; flex-direction: column; gap: 1rem; }
.reservation-card { background: #fff; border-radius: 12px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(46,26,14,0.06); }
.res-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
.res-header h4 { font-size: 14px; font-weight: 600; color: #2E1A0E; margin-bottom: 4px; }
.res-date, .res-guests { font-size: 12px; color: #8B6355; margin-top: 3px; }
.res-note { font-size: 12px; color: #8B6355; margin-top: 8px; }

/* STATUS BADGES */
.status-badge { font-size: 11px; font-weight: 500; border-radius: 100px; padding: 4px 12px; text-transform: capitalize; }
.status-badge.pending   { background: #FEF3C7; color: #92400E; }
.status-badge.confirmed { background: #D1FAE5; color: #065F46; }
.status-badge.preparing { background: #DBEAFE; color: #1E40AF; }
.status-badge.delivered { background: #D1FAE5; color: #065F46; }
.status-badge.cancelled { background: #FEE2E2; color: #B91C1C; }

/* EMPTY & LOADING */
.empty-state { text-align: center; padding: 3rem; background: #fff; border-radius: 12px; }
.empty-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }
.empty-state p { font-size: 14px; color: #9CA3AF; margin-bottom: 1rem; }
.btn-browse { display: inline-block; padding: 8px 20px; background: #2E1A0E; color: #fff; border-radius: 100px; text-decoration: none; font-size: 13px; }
.loading { text-align: center; padding: 2rem; color: #8B6355; }

.alert { padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 1rem; }
.alert.error   { background: #FEE2E2; color: #B91C1C; }
.alert.success { background: #D1FAE5; color: #065F46; }

@media (max-width: 860px) {
  .profile-layout { grid-template-columns: 1fr; padding: 1.5rem; }
}
</style>