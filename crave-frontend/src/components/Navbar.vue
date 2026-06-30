<template>
  <nav class="navbar">
    <RouterLink to="/" class="logo">
      <img :src="logo" alt="Crave" />
    </RouterLink>

    <ul class="nav-links">
      <li><RouterLink to="/">Home</RouterLink></li>
      <li><RouterLink to="/menu">Menu</RouterLink></li>
      <li><RouterLink to="/about">About us</RouterLink></li>
      <li><RouterLink to="/reservation">Reservation</RouterLink></li>
      <li><RouterLink to="/blog">Blog</RouterLink></li>
    </ul>

    <div class="nav-actions">
      <button class="icon-btn" type="button" title="Search" @click="goSearch">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>

      <RouterLink v-if="!authStore.isLoggedIn" to="/login" class="btn-login">Log in</RouterLink>
      <RouterLink v-else to="/profile" class="btn-login">{{ authStore.user?.name || 'Profile' }}</RouterLink>

      <RouterLink to="/order" class="cart-btn" title="Cart">
        🛒<span class="cart-count">{{ cartStore.totalItems || 0 }}</span>
      </RouterLink>
    </div>
  </nav>
</template>

<script setup>
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'
import logo from '../../asset/homepageImge/Logo1.png'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()

function goSearch() {
  alert('Search is not available yet.')
}
</script>

<style scoped>
.navbar {
  width: 100vw;
  position: sticky;
  top: 0;
  left: 0;
  transform: none;
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 1rem;
  padding: 1rem 2rem;
  background: #fff;
  border-bottom: 1px solid rgba(15, 23, 42, 0.08);
  box-shadow: 0 1px 12px rgba(15, 23, 42, 0.08);
  z-index: 100;
}
.logo img {
  height: 40px;
}
.nav-links {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 2rem;
  list-style: none;
  padding: 0;
  margin: 0;
}
.nav-links li {
  display: inline-flex;
}
.nav-links a {
  color: #374151;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.95rem;
}
.nav-links a.router-link-active {
  color: #f97316;
}
.nav-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}
.icon-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 1px solid rgba(15, 23, 42, 0.12);
  display: grid;
  place-items: center;
  background: transparent;
  color: #374151;
  cursor: pointer;
}
.btn-login {
  background: #f3f4f6;
  color: #111827;
  padding: 0.6rem 1rem;
  border-radius: 9999px;
  text-decoration: none;
  font-weight: 600;
}
.cart-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  text-decoration: none;
  color: #111827;
  font-weight: 700;
}
.cart-count {
  background: #f97316;
  color: #fff;
  min-width: 20px;
  height: 20px;
  border-radius: 9999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  padding: 0 6px;
}
@media (max-width: 900px) {
  .navbar {
    flex-wrap: wrap;
    justify-content: space-between;
  }
  .nav-links {
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
  }
}
</style>
