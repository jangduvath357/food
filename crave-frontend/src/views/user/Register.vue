<template>
  <div class="auth-page">
    <div class="auth-card">

      <div class="auth-logo">
        <div class="logo-circle">C</div>
        <span>Crave</span>
      </div>

      <h1>Sign up</h1>

      <div class="alert error"   v-if="error">{{ error }}</div>
      <div class="alert success" v-if="success">{{ success }}</div>

      <div class="form-group">
        <label>Name</label>
        <input v-model="name" type="text" placeholder="Enter your name" />
      </div>

      <div class="form-group">
        <label>Email</label>
        <input v-model="email" type="email" placeholder="Enter your email" />
      </div>

      <div class="form-group">
        <label>Password</label>
        <input v-model="password" type="password" placeholder="Enter your password" />
      </div>

      <div class="form-group">
        <label>Confirm Password</label>
        <input v-model="confirmPassword" type="password" placeholder="Confirm your password" />
      </div>

      <button class="btn-primary" @click="handleRegister" :disabled="loading">
        {{ loading ? 'Creating account...' : 'Sign up' }}
      </button>

      <p class="auth-footer">
        Already have an account?
        <RouterLink to="/login">Log in</RouterLink>
      </p>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router          = useRouter()
const auth            = useAuthStore()
const name            = ref('')
const email           = ref('')
const password        = ref('')
const confirmPassword = ref('')
const loading         = ref(false)
const error           = ref('')
const success         = ref('')

async function handleRegister() {
  error.value   = ''
  success.value = ''

  if (!name.value)                          return error.value = 'Please enter your name.'
  if (!email.value)                         return error.value = 'Please enter your email.'
  if (password.value.length < 6)            return error.value = 'Password must be at least 6 characters.'
  if (password.value !== confirmPassword.value) return error.value = 'Passwords do not match.'

  loading.value = true
  try {
    await auth.register(name.value, email.value, password.value)
    success.value = 'Account created! Please log in.'
    setTimeout(() => router.push('/login'), 1500)
  } catch (err) {
    const errors = err.response?.data?.errors
    if (errors) {
      error.value = Object.values(errors).flat().join(' ')
    } else {
      error.value = err.response?.data?.message || 'Registration failed.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
p{
  text-align: center;
  font-style: times new roman;
}
.auth-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #F3F4F6;
  padding: 2rem;
}

.auth-card {
  background: #fff;
  border-radius: 16px;
  padding: 2.5rem 2rem;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}

.auth-logo {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 1.5rem;
}

.logo-circle {
  width: 32px; height: 32px;
  background: #F97316;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: 700; font-size: 16px;
}

.auth-logo span {
  font-size: 20px;
  font-weight: 700;
  color: #111827;
}

h1 {
  font-size: 24px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 500;
  color: #111827;
  margin-bottom: 6px;
}

.form-group input {
  width: 100%;
  padding: 11px 14px;
  border: 1.5px solid #E5E7EB;
  border-radius: 8px;
  font-size: 13px;
  outline: none;
  transition: border-color 0.2s;
}

.form-group input:focus { border-color: #F97316; }

.btn-primary {
  width: 100%;
  padding: 13px;
  background: #111827;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  margin-top: 0.5rem;
  transition: background 0.2s;
}

.btn-primary:hover    { background: #1F2937; }
.btn-primary:disabled { background: #9CA3AF; cursor: not-allowed; }

.alert {
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 13px;
  margin-bottom: 1rem;
}

.alert.error   { background: #FEE2E2; color: #B91C1C; }
.alert.success { background: #D1FAE5; color: #065F46; }
</style>