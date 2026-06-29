<template>
  <div class="menu-page">
    <nav class="navbar">
      <RouterLink to="/" class="logo">
        <img :src="LogoImg" alt="Crave" />
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
        <RouterLink to="/profile" v-else class="btn-login">{{ authStore.user?.name || 'Profile' }}</RouterLink>
      </div>
    </nav>

    <section class="menu-header">
      <h1>Our Menu</h1>
      <p>Fresh, seasonal & crafted with care.</p>
    </section>

    <section class="filter-bar">
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
      <div class="search-wrap">
        <input v-model="search" type="text" placeholder="Search dishes..." class="search-input" />
      </div>
    </section>

    <section class="menu-items">
      <div class="loading" v-if="loading">Loading menu...</div>
      <div class="error-msg" v-if="error">{{ error }}</div>

      <div class="grid menu-grid" v-if="!loading && filteredItems.length > 0">
        <div class="menu-card" v-for="item in pagedItems" :key="item.id">
          <div class="card-img-wrap">
            <span class="badge" v-if="item.has_discount">{{ item.discount_pct }}% Off</span>
            <img :src="item.image" :alt="item.name" />
          </div>
          <div class="card-body">
            <div class="card-top">
              <h3>{{ item.name }}</h3>
              <span class="rating">{{ item.rating }} ⭐</span>
            </div>
            <p class="card-desc">{{ item.description }}</p>
            <div class="card-footer">
              <span class="price">${{ item.price.toFixed(2) }}</span>
              <button class="btn-add" @click="openModal(item)">+</button>
            </div>
          </div>
        </div>
      </div>

      <div class="empty" v-if="!loading && filteredItems.length === 0">
        No dishes found.
      </div>

      <div class="pagination" v-if="pages > 1">
        <button class="page-nav" @click="prevPage" :disabled="activePage === 1">Previous</button>
        <button
          class="page-num"
          v-for="page in pages"
          :key="page"
          :class="{ active: activePage === page }"
          @click="activePage = page"
        >
          {{ page }}
        </button>
        <button class="page-nav" @click="nextPage" :disabled="activePage === pages">Next</button>
      </div>
    </section>

    <footer>
      <div class="footer-grid container">
        <div class="footer-brand">
          <img :src="LogoImg" alt="Crave" />
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
        <div class="footer-links">
          <h4>Pages</h4>
          <ul>
            <li><RouterLink to="/">Home</RouterLink></li>
            <li><RouterLink to="/menu">Menu</RouterLink></li>
            <li><RouterLink to="/order">Order online</RouterLink></li>
            <li><RouterLink to="/reservation">Reservation</RouterLink></li>
            <li><RouterLink to="/profile">Profile</RouterLink></li>
          </ul>
        </div>
        <div class="footer-links">
          <h4>Information</h4>
          <ul>
            <li><a href="#">About us</a></li>
            <li><a href="#">Testimonials</a></li>
            <li><a href="#">Event</a></li>
          </ul>
        </div>
        <div class="footer-newsletter">
          <h4>Get in touch</h4>
          <div class="social-icons">
            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          </div>
          <form class="subscribe-form" @submit.prevent="subscribe">
            <input v-model="subscriberEmail" type="email" placeholder="Enter email address" required />
            <button type="submit">Subscribe</button>
          </form>
        </div>
      </div>
      <div class="footer-bottom container">
        <p>Copyright &copy; 2026 Crave. All rights reserved.</p>
      </div>
    </footer>

    <div class="product-modal-overlay" v-if="showModal">
      <div class="product-modal-container">
        <div class="product-modal-header">
          <span class="modal-discount-badge" v-if="selectedItem?.has_discount">{{ selectedItem.discount_pct }}% Off</span>
          <button class="modal-close-btn" @click="closeModal"><i class="fa-solid fa-xmark"></i></button>
          <div class="product-image-wrapper">
            <img :src="selectedItem?.image" :alt="selectedItem?.name" />
          </div>
        </div>
        <div class="product-modal-scrollable">
          <div class="product-info-section">
            <div class="veg-indicator"><div class="dot"></div></div>
            <div class="product-title-row">
              <h2>{{ selectedItem?.name }}</h2>
              <div class="stars">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
            </div>
            <p class="delivery-info">{{ selectedItem?.deliveryInfo }}</p>
            <p class="product-desc">{{ selectedItem?.description }}</p>
            <div class="price-qty-row">
              <span class="total-price">${{ modalPrice.toFixed(2) }}</span>
              <div class="qty-selector">
                <button class="qty-btn" @click="decreaseQuantity"><i class="fa-solid fa-minus"></i></button>
                <span class="qty-num">{{ quantity }}</span>
                <button class="qty-btn" @click="increaseQuantity"><i class="fa-solid fa-plus"></i></button>
              </div>
            </div>
          </div>
          <hr class="dotted-divider" />
          <div class="product-customization">
            <div class="option-group">
              <h3>Variants</h3>
              <div class="variants-row">
                <label
                  v-for="variant in selectedItem?.variants || []"
                  :key="variant.label"
                  class="variant-pill"
                  :class="{ active: selectedVariant === variant.price }"
                >
                  <input type="radio" name="variant" :value="variant.price" v-model.number="selectedVariant" />
                  <span class="var-icon"><i class="fa-solid fa-dollar-sign"></i></span>
                  <span>{{ variant.label }}: <strong>${{ variant.price.toFixed(2) }}</strong></span>
                </label>
              </div>
            </div>
            <div class="option-group">
              <h3>Upgrade base</h3>
              <div class="option-list">
                <label class="opt-item" v-for="addon in addons" :key="addon.label">
                  <div class="opt-left">
                    <div class="veg-indicator small"><div class="dot"></div></div>
                    <span>{{ addon.label }}</span>
                  </div>
                  <div class="opt-right">
                    <span class="opt-price">${{ addon.price.toFixed(2) }}</span>
                    <input type="checkbox" :value="addon.price" v-model.number="selectedAddons" />
                  </div>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="product-modal-footer-action">
          <button class="btn btn-primary btn-full" @click="addItemToCart">Add to cart</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useCartStore } from '../../stores/cart'
import { useAuthStore } from '../../stores/auth'

import LogoImg from '../../../asset/homepageImge/Logo1.png'
import DishSpaghetti from '../../../asset/homepageImge/spagatti.png'
import DishGnocchi from '../../../asset/homepageImge/gnochhi.png'
import DishRavioli from '../../../asset/homepageImge/ravioli.png'
import DishPenne from '../../../asset/homepageImge/penne arrabbiata.png'
import DishPizza from '../../../asset/homepageImge/Pizza.png'
import DishRisotto from '../../../asset/homepageImge/Toasted Ravioli.png'
import DishTaco from '../../../asset/homepageImge/Taco.png'

const cartStore = useCartStore()
const authStore = useAuthStore()

const search = ref('')
const selectedCategory = ref('All')
const subscriberEmail = ref('')
const loading = ref(false)
const error = ref('')
const showModal = ref(false)
const selectedItem = ref(null)
const quantity = ref(1)
const selectedVariant = ref(0)
const selectedAddons = ref([])
const activePage = ref(1)

const categories = ['All', 'Dinner', 'Lunch', 'Dessert', 'Drink']

const menuItems = ref([
  {
    id: 1,
    name: 'Spaghetti Bolognese',
    category: 'Dinner',
    description: 'Classic Italian pasta with rich tomato and beef sauce.',
    price: 14.5,
    rating: 4.9,
    has_discount: true,
    discount_pct: 25,
    deliveryInfo: '10 min • 3 km • $0 delivery fee',
    image: DishSpaghetti,
    variants: [
      { label: 'Small', price: 14.5 },
      { label: 'Medium', price: 20.0 },
      { label: 'Large', price: 24.0 }
    ]
  },
  {
    id: 2,
    name: 'Gnocchi Pesto',
    category: 'Lunch',
    description: 'Soft potato gnocchi in a creamy pesto sauce.',
    price: 12.0,
    rating: 4.7,
    has_discount: false,
    discount_pct: 0,
    deliveryInfo: '12 min • 2.5 km • $0 delivery fee',
    image: DishGnocchi,
    variants: [
      { label: 'Small', price: 12.0 },
      { label: 'Medium', price: 16.5 },
      { label: 'Large', price: 20.0 }
    ]
  },
  {
    id: 3,
    name: 'Ravioli',
    category: 'Dinner',
    description: 'Cheese-filled ravioli with a garlic butter finish.',
    price: 15.5,
    rating: 4.8,
    has_discount: true,
    discount_pct: 20,
    deliveryInfo: '11 min • 2.8 km • $0 delivery fee',
    image: DishRavioli,
    variants: [
      { label: 'Small', price: 15.5 },
      { label: 'Medium', price: 21.0 },
      { label: 'Large', price: 26.0 }
    ]
  },
  {
    id: 4,
    name: 'Penne Arrabbiata',
    category: 'Lunch',
    description: 'Spicy tomato sauce with penne and fresh herbs.',
    price: 13.0,
    rating: 4.6,
    has_discount: false,
    discount_pct: 0,
    deliveryInfo: '10 min • 3 km • $0 delivery fee',
    image: DishPenne,
    variants: [
      { label: 'Small', price: 13.0 },
      { label: 'Medium', price: 17.5 },
      { label: 'Large', price: 22.0 }
    ]
  },
  {
    id: 5,
    name: 'Margherita Pizza',
    category: 'Dinner',
    description: 'Classic pizza with tomato, mozzarella, and basil.',
    price: 18.0,
    rating: 4.9,
    has_discount: false,
    discount_pct: 0,
    deliveryInfo: '14 min • 3.5 km • $0 delivery fee',
    image: DishPizza,
    variants: [
      { label: 'Small', price: 18.0 },
      { label: 'Medium', price: 23.0 },
      { label: 'Large', price: 28.0 }
    ]
  },
  {
    id: 6,
    name: 'Taco Salad',
    category: 'Dessert',
    description: 'Fresh taco salad with crispy tortilla strips and avocado.',
    price: 11.0,
    rating: 4.5,
    has_discount: false,
    discount_pct: 0,
    deliveryInfo: '9 min • 2 km • $0 delivery fee',
    image: DishTaco,
    variants: [
      { label: 'Single', price: 11.0 },
      { label: 'Double', price: 16.0 }
    ]
  }
])

const addons = [
  { label: 'Double Burst', price: 3.0 },
  { label: 'Ultra Thin Crust', price: 3.0 }
]

const filteredItems = computed(() => {
  return menuItems.value
    .filter(item => selectedCategory.value === 'All' || item.category === selectedCategory.value)
    .filter(item => item.name.toLowerCase().includes(search.value.toLowerCase()))
})

const pages = computed(() => Math.max(1, Math.ceil(filteredItems.value.length / 6)))

const pagedItems = computed(() => {
  const start = (activePage.value - 1) * 6
  return filteredItems.value.slice(start, start + 6)
})

const modalPrice = computed(() => {
  if (!selectedItem.value) return 0
  const variantPrice = selectedVariant.value || selectedItem.value.price
  const addonsTotal = selectedAddons.value.reduce((sum, value) => sum + Number(value), 0)
  return (variantPrice + addonsTotal) * quantity.value
})

function openModal(item) {
  selectedItem.value = item
  selectedVariant.value = item.variants[0]?.price || item.price
  selectedAddons.value = []
  quantity.value = 1
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedItem.value = null
}

function increaseQuantity() {
  quantity.value += 1
}

function decreaseQuantity() {
  quantity.value = Math.max(1, quantity.value - 1)
}

function addItemToCart() {
  if (!selectedItem.value) return
  cartStore.addItem({
    ...selectedItem.value,
    quantity: quantity.value,
    price: modalPrice.value
  })
  closeModal()
}

function prevPage() {
  activePage.value = Math.max(1, activePage.value - 1)
}

function nextPage() {
  activePage.value = Math.min(pages.value, activePage.value + 1)
}

function subscribe() {
  if (!subscriberEmail.value) return
  alert(`Subscribed with ${subscriberEmail.value}`)
  subscriberEmail.value = ''
}
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }
.menu-page { min-height: 100vh; background: #fefcf5; font-family: 'Inter', sans-serif; color: #1f2937; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.navbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 0; }
.logo img { height: 42px; }
.nav-links { display: flex; gap: 1.5rem; flex-wrap: wrap; }
.nav-links a { color: #4b5563; text-decoration: none; font-weight: 500; }
.nav-links a.router-link-active, .nav-links a:hover { color: #f97316; }
.nav-actions { display: flex; align-items: center; gap: 1rem; }
.cart-btn { display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #111827; }
.cart-count { background: #f97316; color: #fff; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; border-radius: 9999px; font-size: 0.75rem; }
.btn-login { background: #f3f4f6; padding: 0.55rem 1rem; border-radius: 9999px; text-decoration: none; color: #111827; font-weight: 600; }
.menu-header { text-align: center; padding: 3rem 0 1rem; }
.menu-header h1 { font-size: 2.75rem; margin-bottom: 0.75rem; }
.menu-header p { color: #6b7280; }
.filter-bar { display: grid; gap: 1rem; margin-bottom: 1rem; }
.filters { display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; }
.filter-btn { border: 1px solid #d1d5db; background: transparent; border-radius: 9999px; padding: 0.8rem 1.2rem; cursor: pointer; font-weight: 600; color: #374151; }
.filter-btn.active { background: #f97316; color: white; border-color: #f97316; }
.search-wrap { display: flex; justify-content: center; }
.search-input { width: 100%; max-width: 520px; padding: 0.9rem 1.1rem; border-radius: 9999px; border: 1px solid #d1d5db; }
.menu-items { padding-bottom: 3rem; }
.menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; }
.menu-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(15,23,42,0.08); transition: transform 0.2s; }
.menu-card:hover { transform: translateY(-4px); }
.card-img-wrap { position: relative; min-height: 220px; overflow: hidden; }
.card-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
.badge { position: absolute; top: 16px; left: 16px; background: #f97316; color: white; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
.card-body { padding: 1.25rem; }
.card-top { display: flex; justify-content: space-between; margin-bottom: 0.75rem; }
.card-top h3 { font-size: 1rem; margin: 0; }
.rating { color: #d97706; font-weight: 700; }
.card-desc { color: #6b7280; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem; }
.card-footer { display: flex; justify-content: space-between; align-items: center; }
.price { font-size: 1.1rem; font-weight: 700; color: #111827; }
.btn-add { width: 36px; height: 36px; border-radius: 50%; border: none; background: #f97316; color: white; font-size: 1.1rem; cursor: pointer; }
.loading, .error-msg, .empty { text-align: center; padding: 2rem; }
.error-msg { color: #b91c1c; background: #fee2e2; border-radius: 16px; }
.pagination { display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; margin-top: 1.5rem; }
.page-nav, .page-num { border: 1px solid #d1d5db; background: white; color: #374151; padding: 0.75rem 1rem; border-radius: 9999px; cursor: pointer; }
.page-num.active { background: #f97316; color: white; border-color: #f97316; }
.page-nav:disabled { opacity: 0.5; cursor: not-allowed; }
.footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2rem; padding: 3rem 0 1.5rem; }
.footer-brand img { height: 40px; margin-bottom: 1rem; }
.footer-links ul { list-style: none; padding: 0; }
.footer-links li { margin-bottom: 0.8rem; }
.footer-links a { color: #4b5563; text-decoration: none; }
.social-icons { display: flex; gap: 0.75rem; margin-bottom: 1rem; }
.social-icons a { color: #374151; font-size: 1.2rem; }
.subscribe-form { display: flex; flex-wrap: wrap; gap: 0.75rem; }
.subscribe-form input { flex: 1 1 200px; padding: 0.85rem 1rem; border: 1px solid #d1d5db; border-radius: 9999px; }
.subscribe-form button { border: none; background: #f97316; color: white; padding: 0.85rem 1.25rem; border-radius: 9999px; cursor: pointer; }
.footer-bottom { border-top: 1px solid #e5e7eb; padding: 1.25rem 0; text-align: center; color: #6b7280; }
.product-modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); display: flex; justify-content: center; align-items: center; padding: 1.5rem; z-index: 50; }
.product-modal-container { background: white; width: min(100%, 740px); border-radius: 28px; overflow: hidden; }
.product-modal-header { position: relative; padding: 1.5rem; display: grid; gap: 1rem; }
.modal-discount-badge { position: absolute; top: 1.25rem; left: 1.25rem; background: #f97316; color: white; padding: 0.5rem 0.85rem; border-radius: 9999px; font-weight: 700; }
.modal-close-btn { position: absolute; top: 1rem; right: 1rem; border: none; background: transparent; font-size: 1.1rem; cursor: pointer; }
.product-image-wrapper { width: 100%; min-height: 260px; overflow: hidden; border-radius: 24px; }
.product-image-wrapper img { width: 100%; height: 100%; object-fit: cover; }
.product-modal-scrollable { padding: 1.5rem; border-top: 1px solid #f3f4f6; }
.veg-indicator { width: 16px; height: 16px; border-radius: 50%; background: #34d399; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
.veg-indicator.small { width: 12px; height: 12px; }
.veg-indicator .dot { width: 6px; height: 6px; border-radius: 50%; background: white; }
.product-title-row { display: flex; justify-content: space-between; gap: 1rem; }
.product-title-row h2 { margin: 0; }
.stars i { color: #f59e0b; margin-right: 0.25rem; }
.delivery-info { color: #6b7280; margin: 0.75rem 0; }
.product-desc { color: #4b5563; line-height: 1.75; margin-bottom: 1rem; }
.price-qty-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
.total-price { font-size: 1.5rem; font-weight: 700; color: #111827; }
.qty-selector { display: flex; align-items: center; gap: 0.75rem; }
.qty-btn { width: 32px; height: 32px; border: 1px solid #d1d5db; background: white; border-radius: 8px; cursor: pointer; }
.qty-num { min-width: 28px; text-align: center; font-weight: 700; }
.dotted-divider { border: none; border-top: 1px dashed #d1d5db; margin: 1.5rem 0; }
.product-customization { display: grid; gap: 1.25rem; }
.option-group h3 { margin-bottom: 0.75rem; }
.variants-row { display: flex; flex-wrap: wrap; gap: 0.75rem; }
.variant-pill { display: flex; align-items: center; gap: 0.5rem; border: 1px solid #d1d5db; border-radius: 9999px; padding: 0.75rem 1rem; cursor: pointer; }
.variant-pill.active { background: #f97316; color: white; border-color: #f97316; }
.variant-pill input { display: none; }
.option-list { display: grid; gap: 0.75rem; }
.opt-item { display: flex; justify-content: space-between; align-items: center; gap: 1rem; background: #f8fafc; padding: 0.85rem 1rem; border-radius: 16px; }
.opt-left { display: flex; align-items: center; gap: 0.75rem; }
.opt-right { display: flex; align-items: center; gap: 0.75rem; }
.opt-price { font-weight: 700; }
.product-modal-footer-action { padding: 1.5rem; border-top: 1px solid #f3f4f6; }
.btn-full { width: 100%; }
@media (max-width: 768px) {
  .navbar { flex-direction: column; align-items: flex-start; }
  .nav-actions { width: 100%; justify-content: space-between; }
  .menu-grid { grid-template-columns: 1fr; }
  .product-modal-container { width: 100%; }
}
</style>
