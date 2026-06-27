import { defineStore } from 'pinia'
import api from '../api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user:  JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null
  }),

  getters: {
    isLoggedIn: (state) => !!state.token,
    isAdmin:    (state) => state.user?.role === 'admin',
    isCustomer: (state) => state.user?.role === 'customer'
  },

  actions: {
    async login(email, password) {
      const res = await api.post('/login', { email, password })
      this.token = res.data.token
      this.user  = res.data.user
      localStorage.setItem('token', this.token)
      localStorage.setItem('user',  JSON.stringify(this.user))
      return res.data
    },

    async register(name, email, password) {
      const res = await api.post('/register', { name, email, password })
      return res.data
    },

    async logout() {
      try { await api.post('/logout') } catch {}
      this.token = null
      this.user  = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  }
})