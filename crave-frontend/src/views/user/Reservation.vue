<template>
  <div class="reservation-page">

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
        <RouterLink to="/profile" class="btn-login">{{ authStore.user?.name }}</RouterLink>
      </div>
    </nav>

    <div class="reservation-layout">

      <!-- LEFT INFO -->
      <div class="reservation-info">
        <h1>Reserve a Table</h1>
        <p class="subtitle">Book your table in seconds. We'll confirm your reservation shortly.</p>

        <div class="info-cards">
          <div class="info-card">
            <div class="info-icon">🕐</div>
            <div>
              <h4>Opening Hours</h4>
              <p>Monday – Sunday</p>
              <p>11:00am – 10:00pm</p>
            </div>
          </div>
          <div class="info-card">
            <div class="info-icon">📍</div>
            <div>
              <h4>Location</h4>
              <p>12 Parker Rd.</p>
              <p>Allentown, New Mexico</p>
            </div>
          </div>
          <div class="info-card">
            <div class="info-icon">📞</div>
            <div>
              <h4>Phone</h4>
              <p>(704) 555-0127</p>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT FORM -->
      <div class="reservation-form-wrap">
        <div class="form-card">
          <h2>Book a Table</h2>

          <div class="alert error"   v-if="error">{{ error }}</div>
          <div class="alert success" v-if="success">{{ success }}</div>

          <div class="form-group">
            <label>Your Name</label>
            <input v-model="form.name" type="text" placeholder="Enter your name" />
          </div>

          <div class="form-group">
            <label>Phone Number</label>
            <input v-model="form.phone" type="tel" placeholder="Enter your phone" />
          </div>

          <div class="form-group">
            <label>Email (optional)</label>
            <input v-model="form.email" type="email" placeholder="Enter your email" />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Date</label>
              <input v-model="form.date" type="date" :min="today" />
            </div>
            <div class="form-group">
              <label>Time</label>
              <select v-model="form.time">
                <option value="">Select time</option>
                <option v-for="t in timeSlots" :key="t" :value="t">{{ t }}</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Number of Guests</label>
            <div class="guests-selector">
              <button class="guest-btn" @click="decreaseGuests">−</button>
              <span class="guest-count">{{ form.guests }}</span>
              <button class="guest-btn" @click="increaseGuests">+</button>
            </div>
          </div>

          <div class="form-group">
            <label>Special Request (optional)</label>
            <textarea v-model="form.note" placeholder="Any special requests or dietary requirements..." rows="3"></textarea>
          </div>

          <button class="btn-reserve" @click="makeReservation" :disabled="loading">
            {{ loading ? 'Booking...' : 'Book Table' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useCartStore } from '../../stores/cart'
import { useAuthStore } from '../../stores/auth'
import api from '../../api'

const cartStore = useAuthStore()
const authStore = useAuthStore()
const cartStore2 = useCartStore()

const loading = ref(false)
const error   = ref('')
const success = ref('')

const today = computed(() => new Date().toISOString().split('T')[0])

const timeSlots = [
  '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM',
  '01:00 PM', '01:30 PM', '02:00 PM', '06:00 PM',
  '06:30 PM', '07:00 PM', '07:30 PM', '08:00 PM',
  '08:30 PM', '09:00 PM', '09:30 PM'
]

const form = ref({
  name:   authStore.user?.name || '',
  phone:  '',
  email:  authStore.user?.email || '',
  date:   '',
  time:   '',
  guests: 2,
  note:   ''
})

function increaseGuests() { if (form.value.guests < 20) form.value.guests++ }
function decreaseGuests() { if (form.value.guests > 1)  form.value.guests-- }

async function makeReservation() {
  error.value   = ''
  success.value = ''

  if (!form.value.name)   return error.value = 'Please enter your name.'
  if (!form.value.phone)  return error.value = 'Please enter your phone number.'
  if (!form.value.date)   return error.value = 'Please select a date.'
  if (!form.value.time)   return error.value = 'Please select a time.'

  loading.value = true
  try {
    const res = await api.post('/reservations', form.value)
    success.value = `Table booked! Reservation ID: #${res.data.reservation.id} 🎉`
    form.value = {
      name:   authStore.user?.name || '',
      phone:  '',
      email:  authStore.user?.email || '',
      date:   '',
      time:   '',
      guests: 2,
      note:   ''
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to make reservation.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.reservation-page { min-height: 100vh; background: #F5E6E0; font-family: 'Inter', sans-serif; }

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
.btn-login { font-size: 12px; font-weight: 500; color: #111827; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 100px; padding: 6px 16px; text-decoration: none; }

/* LAYOUT */
.reservation-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; padding: 4rem 3rem; max-width: 1200px; margin: 0 auto; }

/* LEFT INFO */
.reservation-info h1 { font-family: 'Georgia', serif; font-size: 2.5rem; font-weight: 700; color: #2E1A0E; margin-bottom: 1rem; line-height: 1.2; }
.subtitle { font-size: 15px; color: #8B6355; line-height: 1.7; margin-bottom: 2.5rem; }

.info-cards { display: flex; flex-direction: column; gap: 1.2rem; }
.info-card { display: flex; align-items: flex-start; gap: 1rem; background: #fff; border-radius: 12px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(46,26,14,0.06); }
.info-icon { font-size: 24px; flex-shrink: 0; }
.info-card h4 { font-size: 14px; font-weight: 600; color: #2E1A0E; margin-bottom: 4px; }
.info-card p { font-size: 13px; color: #8B6355; line-height: 1.6; }

/* FORM */
.form-card { background: #fff; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(46,26,14,0.08); }
.form-card h2 { font-size: 20px; font-weight: 700; color: #2E1A0E; margin-bottom: 1.5rem; }

.form-group { margin-bottom: 1.1rem; }
.form-group label { display: block; font-size: 12px; font-weight: 600; color: #2E1A0E; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
.form-group input,
.form-group select,
.form-group textarea { width: 100%; padding: 11px 14px; border: 1.5px solid #E5E7EB; border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; outline: none; transition: border-color 0.2s; background: #fff; color: #2E1A0E; }
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { border-color: #C4622A; }
.form-group textarea { resize: vertical; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

.guests-selector { display: flex; align-items: center; gap: 1rem; }
.guest-btn { width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #E5E7EB; background: transparent; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; color: #2E1A0E; }
.guest-btn:hover { background: #F5E6E0; border-color: #C4622A; }
.guest-count { font-size: 18px; font-weight: 700; color: #2E1A0E; min-width: 30px; text-align: center; }

.btn-reserve { width: 100%; padding: 14px; background: #2E1A0E; color: #fff; border: none; border-radius: 100px; font-size: 14px; font-weight: 700; cursor: pointer; margin-top: 0.5rem; transition: background 0.2s, transform 0.15s; }
.btn-reserve:hover { background: #C4622A; transform: translateY(-1px); }
.btn-reserve:disabled { background: #9CA3AF; cursor: not-allowed; transform: none; }

.alert { padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 1rem; }
.alert.error   { background: #FEE2E2; color: #B91C1C; }
.alert.success { background: #D1FAE5; color: #065F46; }

@media (max-width: 900px) {
  .reservation-layout { grid-template-columns: 1fr; padding: 2rem 1.5rem; }
  .reservation-info h1 { font-size: 1.8rem; }
}
</style>