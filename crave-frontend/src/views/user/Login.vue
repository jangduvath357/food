<template>
  <div class="auth-page">
    <div class="auth-card">

      <div class="auth-logo">
        <div class="logo-circle">C</div>
        <span>Crave</span>
      </div>

      <h1>Log in</h1>

      <div class="alert error" v-if="error">{{ error }}</div>
      <div class="alert success" v-if="success">{{ success }}</div>

      <div class="form-group">
        <label>Email</label>
        <input v-model="email" type="email" placeholder="Enter your email" />
      </div>

      <div class="form-group">
        <label>Password</label>
        <input v-model="password" type="password" placeholder="Enter your password" />
      </div>

      <button class="btn-primary" @click="handleLogin" :disabled="loading">
        {{ loading ? 'Logging in...' : 'Log in' }}
      </button>

      <p class="auth-footer">
        Don't have an account?
        <RouterLink to="/register">Sign up</RouterLink>
      </p>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router   = useRouter()
const auth     = useAuthStore()
const email    = ref('')
const password = ref('')
const loading  = ref(false)
const error    = ref('')
const success  = ref('')

async function handleLogin() {
  error.value   = ''
  success.value = ''
  if (!email.value)    return error.value = 'Please enter your email.'
  if (!password.value) return error.value = 'Please enter your password.'

  loading.value = true
  try {
    const res = await auth.login(email.value, password.value)
    success.value = 'Login successful! Redirecting...'
    setTimeout(() => {
      if (auth.isAdmin) router.push('/admin')
      else router.push('/')
    }, 1000)
  } catch (err) {
    error.value = err.response?.data?.message || 'Login failed.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
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

.form-group input:focus {
  border-color: #F97316;
}

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

.btn-primary:hover { background: #1F2937; }
.btn-primary:disabled { background: #9CA3AF; cursor: not-allowed; }

.alert {
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 13px;
  margin-bottom: 1rem;
}

.alert.error   { background: #FEE2E2; color: #B91C1C; }
.alert.success { background: #D1FAE5; color: #065F46; }

.auth-footer {
  text-align: center;
  font-size: 13px;
  color: #6B7280;
  margin-top: 1.2rem;
}

.auth-footer a {
  color: #F97316;
  font-weight: 500;
  text-decoration: none;
}
</style>