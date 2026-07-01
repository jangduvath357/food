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
        <h1>Menu Management</h1>
        <button class="btn-add-item" @click="openModal()">+ Add Item</button>
      </div>

      <div class="loading" v-if="loading">Loading menu...</div>

      <div class="menu-grid" v-else>
        <div class="menu-card" v-for="item in items" :key="item.id">
          <div class="card-img">
            <img :src="item.image_url || 'https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?w=300&auto=format&fit=crop'" :alt="item.name" />
            <span class="discount-badge" v-if="item.has_discount">{{ item.discount_pct }}% Off</span>
          </div>
          <div class="card-body">
            <div class="card-top">
              <h4>{{ item.name }}</h4>
              <span class="category-tag">{{ item.category }}</span>
            </div>
            <p class="card-desc">{{ item.description }}</p>
            <div class="card-footer">
              <span class="price">${{ item.price }}</span>
              <div class="card-actions">
                <button class="btn-edit" @click="openModal(item)">✏️</button>
                <button class="btn-delete" @click="deleteItem(item.id)">🗑️</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL -->
      <div class="modal-overlay" v-if="showModal" @click.self="closeModal">
        <div class="modal">
          <div class="modal-header">
            <h3>{{ editingItem ? 'Edit Item' : 'Add New Item' }}</h3>
            <button class="btn-close" @click="closeModal">✕</button>
          </div>

          <div class="alert error"   v-if="modalError">{{ modalError }}</div>
          <div class="alert success" v-if="modalSuccess">{{ modalSuccess }}</div>

          <div class="form-group">
            <label>Name</label>
            <input v-model="form.name" type="text" placeholder="Item name" />
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea v-model="form.description" placeholder="Item description" rows="2"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Price ($)</label>
              <input v-model="form.price" type="number" step="0.01" placeholder="0.00" />
            </div>
            <div class="form-group">
              <label>Category</label>
              <select v-model="form.category">
                <option value="dinner">Dinner</option>
                <option value="lunch">Lunch</option>
                <option value="dessert">Dessert</option>
                <option value="drink">Drink</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Image URL (optional)</label>
            <input v-model="form.image_url" type="text" placeholder="https://..." />
          </div>
          <div class="form-row">
            <div class="form-group checkbox-group">
              <input type="checkbox" v-model="form.has_discount" id="discount" />
              <label for="discount">Has Discount</label>
            </div>
            <div class="form-group" v-if="form.has_discount">
              <label>Discount %</label>
              <input v-model="form.discount_pct" type="number" placeholder="25" />
            </div>
          </div>
          <div class="form-group checkbox-group">
            <input type="checkbox" v-model="form.available" id="available" />
            <label for="available">Available</label>
          </div>

          <div class="modal-footer">
            <button class="btn-cancel" @click="closeModal">Cancel</button>
            <button class="btn-save" @click="saveItem" :disabled="modalLoading">
              {{ modalLoading ? 'Saving...' : editingItem ? 'Update' : 'Add Item' }}
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import api from '../../api'

const router    = useRouter()
const authStore = useAuthStore()
const loading   = ref(true)
const items     = ref([])
const showModal = ref(false)
const editingItem = ref(null)
const modalLoading = ref(false)
const modalError   = ref('')
const modalSuccess = ref('')

const defaultForm = {
  name: '', description: '', price: '',
  category: 'dinner', image_url: '',
  has_discount: false, discount_pct: 0, available: true
}

const form = ref({ ...defaultForm })

async function fetchItems() {
  loading.value = true
  try {
    const res  = await api.get('/menu')
    items.value = res.data
  } catch {} finally {
    loading.value = false
  }
}

function openModal(item = null) {
  editingItem.value  = item
  modalError.value   = ''
  modalSuccess.value = ''
  form.value = item ? { ...item } : { ...defaultForm }
  showModal.value = true
}

function closeModal() {
  showModal.value   = false
  editingItem.value = null
}

async function saveItem() {
  modalError.value   = ''
  modalSuccess.value = ''
  if (!form.value.name)  return modalError.value = 'Name is required.'
  if (!form.value.price) return modalError.value = 'Price is required.'

  modalLoading.value = true
  try {
    if (editingItem.value) {
      await api.put(`/admin/menu/${editingItem.value.id}`, form.value)
      modalSuccess.value = 'Item updated! ✅'
    } else {
      await api.post('/admin/menu', form.value)
      modalSuccess.value = 'Item added! ✅'
    }
    await fetchItems()
    setTimeout(closeModal, 1000)
  } catch (err) {
    modalError.value = err.response?.data?.message || 'Failed to save.'
  } finally {
    modalLoading.value = false
  }
}

async function deleteItem(id) {
  if (!confirm('Delete this item?')) return
  try {
    await api.delete(`/admin/menu/${id}`)
    items.value = items.value.filter(i => i.id !== id)
  } catch {}
}

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}

onMounted(fetchItems)
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
.admin-main { padding: 2rem; }
.admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.admin-header h1 { font-size: 28px; font-weight: 700; color: #2E1A0E; }
.btn-add-item { padding: 10px 20px; background: #C4622A; color: #fff; border: none; border-radius: 100px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
.btn-add-item:hover { background: #a8521f; }
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.2rem; }
.menu-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(46,26,14,0.06); }
.card-img { position: relative; aspect-ratio: 4/3; overflow: hidden; }
.card-img img { width: 100%; height: 100%; object-fit: cover; }
.discount-badge { position: absolute; top: 8px; left: 8px; background: #2E7D32; color: #fff; font-size: 10px; font-weight: 600; border-radius: 4px; padding: 3px 8px; }
.card-body { padding: 1rem; }
.card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
.card-top h4 { font-size: 14px; font-weight: 600; color: #2E1A0E; }
.category-tag { font-size: 10px; background: #F5E6E0; color: #C4622A; border-radius: 100px; padding: 2px 8px; text-transform: capitalize; }
.card-desc { font-size: 11px; color: #8B6355; line-height: 1.5; margin-bottom: 0.8rem; }
.card-footer { display: flex; justify-content: space-between; align-items: center; }
.price { font-size: 15px; font-weight: 700; color: #C4622A; }
.card-actions { display: flex; gap: 6px; }
.btn-edit, .btn-delete { background: none; border: none; font-size: 16px; cursor: pointer; padding: 4px; border-radius: 6px; transition: background 0.2s; }
.btn-edit:hover  { background: #DBEAFE; }
.btn-delete:hover { background: #FEE2E2; }
.loading { text-align: center; padding: 2rem; color: #8B6355; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 1rem; }
.modal { background: #fff; border-radius: 16px; padding: 2rem; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.modal-header h3 { font-size: 18px; font-weight: 700; color: #2E1A0E; }
.btn-close { background: none; border: none; font-size: 18px; cursor: pointer; color: #9CA3AF; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; font-size: 12px; font-weight: 600; color: #2E1A0E; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.05em; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 14px; border: 1.5px solid #E5E7EB; border-radius: 8px; font-size: 13px; outline: none; font-family: 'Inter', sans-serif; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #C4622A; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.checkbox-group { display: flex; align-items: center; gap: 8px; }
.checkbox-group input { width: auto; }
.checkbox-group label { text-transform: none; font-size: 13px; margin: 0; }
.modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 1.5rem; }
.btn-cancel { padding: 10px 20px; background: #F3F4F6; color: #6B7280; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; }
.btn-save { padding: 10px 24px; background: #2E1A0E; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; }
.btn-save:hover { background: #C4622A; }
.btn-save:disabled { background: #9CA3AF; cursor: not-allowed; }
.alert { padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 1rem; }
.alert.error   { background: #FEE2E2; color: #B91C1C; }
.alert.success { background: #D1FAE5; color: #065F46; }
</style>