<template>
  <div class="menu-page">

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
        <RouterLink to="/login" v-if="!authStore.isLoggedIn" class="btn-login">Log in</RouterLink>
        <RouterLink to="/profile" v-else class="btn-login">{{ authStore.user?.name }}</RouterLink>
      </div>
    </nav>

    <!-- HEADER -->
    <div class="menu-header">
      <h1>Our Menu</h1>
      <p>Fresh, seasonal & crafted with care</p>
    </div>

    <!-- FILTERS -->
    <div class="filters">
      <button
        v-for="cat in categories"
        :key="cat"
        class="filter-btn"
        :class="{ active: selectedCategory === cat }"
        @click="selectedCategory = cat"
      >
        {{ cat }}
      </button>
    </div>

    <!-- SEARCH -->
    <div class="search-wrap">
      <input v-model="search" type="text" placeholder="Search dishes..." class="search-input" />
    </div>

    <!-- LOADING -->
    <div class="loading" v-if="loading">Loading menu...</div>

    <!-- ERROR -->
    <div class="error-msg" v-if="error">{{ error }}</div>

    <!-- GRID -->
    <div class="menu-grid" v-if="!loading">
      <div
        class="menu-card"
        v-for="item in filteredItems"
        :key="item.id"
      >
        <div class="card-img-wrap">
          <span class="badge" v-if="item.has_discount">{{ item.discount_pct }}% Off</span>
          <img :src="item.image_url || 'https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?w=400&auto=format&fit=crop'" :alt="item.name" />
        </div>
        <div class="card-body">
          <div class="card-top">
            <h3>{{ item.name }}</h3>
            <span class="rating">5.0 ⭐</span>
          </div>
          <p class="card-desc">{{ item.description }}</p>
          <div class="card-footer">
            <span class="price">${{ item.price }}</span>
            <button class="btn-add" @click="addToCart(item)">+</button>
          </div>
        </div>
      </div>

      <div class="empty" v-if="filteredItems.length === 0">
        No dishes found.
      </div>
    </div>

    <!-- TOAST -->
    <div class="toast" :class="{ show: showToast }">{{ toastMsg }}</div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useCartStore } from '../../stores/cart'
import { useAuthStore } from '../../stores/auth'
import api from '../../api'

const cartStore = useCartStore()
const authStore = useAuthStore()

const items            = ref([])
const loading          = ref(true)
const error            = ref('')
const search           = ref('')
const selectedCategory = ref('All')
const showToast        = ref(false)
const toastMsg         = ref('')

const categories = ['All', 'dinner', 'lunch', 'dessert', 'drink']

const filteredItems = computed(() => {
  return items.value.filter(item => {
    const matchCat    = selectedCategory.value === 'All' || item.category === selectedCategory.value
    const matchSearch = item.name.toLowerCase().includes(search.value.toLowerCase())
    return matchCat && matchSearch
  })
})

async function fetchMenu() {
  loading.value = true
  try {
    const res  = await api.get('/menu')
    items.value = res.data
  } catch (err) {
    error.value = 'Failed to load menu. Make sure Laravel is running.'
  } finally {
    loading.value = false
  }
}

function addToCart(item) {
  cartStore.addItem(item)
  toastMsg.value  = `${item.name} added to cart!`
  showToast.value = true
  setTimeout(() => showToast.value = false, 2000)
}

onMounted(fetchMenu)
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.menu-page { min-height: 100vh; background: #F5E6E0; font-family: 'Inter', sans-serif; }

/* NAVBAR */
.navbar {
  background: #fff;
  padding: 1rem 2rem;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 100;
  box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}

.logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
.logo-circle { width: 30px; height: 30px; background: #F97316; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; }
.logo span { font-size: 18px; font-weight: 700; color: #111827; }

.nav-links { display: flex; gap: 1.5rem; }
.nav-links a { text-decoration: none; color: #6B7280; font-size: 13px; font-weight: 500; transition: color 0.2s; }
.nav-links a:hover { color: #111827; }
.nav-links a.router-link-active { color: #F97316; }

.nav-actions { display: flex; align-items: center; gap: 1rem; }
.cart-btn { position: relative; font-size: 20px; text-decoration: none; }
.cart-count { position: absolute; top: -6px; right: -8px; background: #F97316; color: #fff; font-size: 10px; font-weight: 700; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; }

.btn-login { font-size: 12px; font-weight: 500; color: #111827; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 100px; padding: 6px 16px; text-decoration: none; transition: background 0.2s; }
.btn-login:hover { background: #E5E7EB; }

/* HEADER */
.menu-header { text-align: center; padding: 3rem 2rem 1rem; }
.menu-header h1 { font-size: 2.5rem; font-weight: 700; color: #2E1A0E; font-family: 'Georgia', serif; }
.menu-header p { color: #8B6355; font-size: 14px; margin-top: 0.5rem; }

/* FILTERS */
.filters { display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; padding: 1rem 2rem; }
.filter-btn { padding: 8px 20px; border: 1px solid rgba(46,26,14,0.2); border-radius: 100px; background: transparent; font-size: 13px; cursor: pointer; color: #2E1A0E; transition: all 0.2s; }
.filter-btn:hover { border-color: #2E1A0E; }
.filter-btn.active { background: #2E1A0E; color: #fff; border-color: #2E1A0E; }

/* SEARCH */
.search-wrap { display: flex; justify-content: center; padding: 0 2rem 1.5rem; }
.search-input { width: 100%; max-width: 480px; padding: 11px 18px; border: 1px solid rgba(46,26,14,0.2); border-radius: 100px; font-size: 13px; outline: none; background: #fff; transition: border-color 0.2s; }
.search-input:focus { border-color: #C4622A; }

/* GRID */
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; padding: 0 2rem 4rem; max-width: 1200px; margin: 0 auto; }

.menu-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(46,26,14,0.08); transition: transform 0.2s, box-shadow 0.2s; }
.menu-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(46,26,14,0.12); }

.card-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #EDD5CC; }
.card-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
.menu-card:hover .card-img-wrap img { transform: scale(1.05); }
.badge { position: absolute; top: 10px; left: 10px; background: #2E7D32; color: #fff; font-size: 10px; font-weight: 600; border-radius: 4px; padding: 3px 8px; }

.card-body { padding: 1rem; }
.card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.card-top h3 { font-size: 15px; font-weight: 600; color: #2E1A0E; }
.rating { font-size: 12px; color: #2E1A0E; }
.card-desc { font-size: 12px; color: #8B6355; line-height: 1.6; margin-bottom: 0.8rem; }
.card-footer { display: flex; justify-content: space-between; align-items: center; }
.price { font-size: 16px; font-weight: 700; color: #C4622A; }
.btn-add { width: 32px; height: 32px; background: #2E1A0E; color: #fff; border: none; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
.btn-add:hover { background: #C4622A; }

.loading { text-align: center; padding: 3rem; color: #8B6355; font-size: 16px; }
.error-msg { text-align: center; padding: 2rem; color: #B91C1C; background: #FEE2E2; margin: 1rem 2rem; border-radius: 8px; }
.empty { text-align: center; padding: 3rem; color: #8B6355; grid-column: 1/-1; }

/* TOAST */
.toast { position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%) translateY(80px); background: #2E1A0E; color: #fff; padding: 12px 24px; border-radius: 100px; font-size: 13px; transition: transform 0.3s; pointer-events: none; z-index: 999; }
.toast.show { transform: translateX(-50%) translateY(0); }
</style>