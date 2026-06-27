import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('cart')) || []
  }),

  getters: {
    totalItems: (state) => state.items.reduce((s, i) => s + i.qty, 0),
    subtotal:   (state) => state.items.reduce((s, i) => s + i.price * i.qty, 0),
    total:      (state) => state.items.reduce((s, i) => s + i.price * i.qty, 0) + 2.99
  },

  actions: {
    addItem(item) {
      const existing = this.items.find(i => i.id === item.id)
      if (existing) { existing.qty++ }
      else { this.items.push({ ...item, qty: 1 }) }
      localStorage.setItem('cart', JSON.stringify(this.items))
    },

    removeItem(id) {
      this.items = this.items.filter(i => i.id !== id)
      localStorage.setItem('cart', JSON.stringify(this.items))
    },

    updateQty(id, qty) {
      const item = this.items.find(i => i.id === id)
      if (item) { item.qty = qty }
      if (qty <= 0) this.removeItem(id)
      localStorage.setItem('cart', JSON.stringify(this.items))
    },

    clearCart() {
      this.items = []
      localStorage.removeItem('cart')
    }
  }
})