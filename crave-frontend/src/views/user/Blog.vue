
<template>
  <div class="blog-page">
    <Navbar />

    <main class="blog-main">
      <section class="blog-header container text-center">
        <h1 class="page-title">Discover our latest posts</h1>
        <p class="page-subtitle">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor<br />
          incididunt ut labore et dolore magna aliqua.
        </p>

        <div class="search-bar-wrapper">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input type="text" v-model="search" placeholder="Search post..." />
        </div>
      </section>

      <RouterLink to="/blog-detail" class="blog-link featured-link">
        <section class="featured-post container">
          <div class="featured-image">
            <img :src="blogImages[0]" alt="Restaurant Interior" />
          </div>
          <div class="featured-content">
            <span class="post-badge badge-blue">MENU</span>
            <h2 class="featured-title">Introducing Crave's Biggest App Updates in a Decade</h2>
            <p class="featured-desc">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
            <div class="author-info">
              <img :src="profileImg" alt="Esther Howard" class="author-img" />
              <span class="author-details"><strong>Esther Howard</strong> | 21 March, 2024</span>
            </div>
            <button class="btn btn-outline">Read more</button>
          </div>
        </section>
      </RouterLink>

      <section class="all-posts container">
        <h2 class="section-title">All blog post</h2>

        <div class="blog-grid">
          <RouterLink
            v-for="post in filteredPosts"
            :key="post.id"
            :to="post.link"
            class="blog-link"
          >
            <article class="blog-card">
              <div class="card-img-wrapper">
                <img :src="post.image" :alt="post.title" />
              </div>
              <span class="post-badge" :class="post.badgeClass">{{ post.category }}</span>
              <div class="card-title-row">
                <h3>{{ post.title }}</h3>
                <button title="btn" class="btn-arrow"><i class="fa-solid fa-arrow-right icon-up-right"></i></button>
              </div>
              <p class="card-excerpt">{{ post.excerpt }}</p>
            </article>
          </RouterLink>
        </div>
      </section>
    </main>

    <footer>
      <div class="container footer-grid">
        <div class="footer-brand">
          <img :src="LogoImg" alt="Crave" />
          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
          </p>
        </div>
        <div class="footer-links">
          <h4>Pages</h4>
          <ul>
            <li><RouterLink to="/">Home</RouterLink></li>
            <li><RouterLink to="/menu">Menu</RouterLink></li>
            <li><RouterLink to="/order">Order online</RouterLink></li>
            <li><RouterLink to="/reservation">Reservation</RouterLink></li>
          </ul>
        </div>
        <div class="footer-links">
          <h4>Information</h4>
          <ul>
            <li><RouterLink to="/about">About us</RouterLink></li>
            <li><a href="#">Testimonials</a></li>
            <li><a href="#">Event</a></li>
          </ul>
        </div>
        <div class="footer-newsletter">
          <h4>Get in touch</h4>
          <div class="social-icons">
            <a href="#" class="social-circle"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="social-circle"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="#" class="social-circle"><i class="fa-brands fa-instagram"></i></a>
          </div>
          <form class="subscribe-form" @submit.prevent="subscribe">
            <input type="email" placeholder="Enter email address" v-model="email" required />
            <button type="submit">Subscribe</button>
          </form>
        </div>
      </div>
      <div class="footer-bottom container">
        <p>Copyright &copy; 2026 Crave. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import Navbar from '../../components/Navbar.vue'

import LogoImg from '../../../asset/BlogpageImage/Logo1.png'
import Blog1 from '../../../asset/BlogpageImage/1.png'
import Blog2 from '../../../asset/BlogpageImage/2.png'
import Blog3 from '../../../asset/BlogpageImage/3.png'
import Blog4 from '../../../asset/BlogpageImage/4.png'
import Blog5 from '../../../asset/BlogpageImage/5.png'
import ProfileImg from '../../../asset/BlogpageImage/pf.png'

const cartCount = ref(0)
const email = ref('')
const search = ref('')

const profileImg = ProfileImg

const posts = [
  {
    id: 1,
    title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt...',
    category: 'RESTAURANT',
    badgeClass: 'badge-green',
    image: Blog1,
    link: '/blog-detail'
  },
  {
    id: 2,
    title: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem.',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt...',
    category: 'CULTURE',
    badgeClass: 'badge-gray',
    image: Blog2,
    link: '/blog-detail'
  },
  {
    id: 3,
    title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt...',
    category: 'POLICY',
    badgeClass: 'badge-purple',
    image: Blog3,
    link: '/blog-detail'
  },
  {
    id: 4,
    title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt...',
    category: 'NEWS',
    badgeClass: 'badge-blue',
    image: Blog4,
    link: '/blog-detail'
  },
  {
    id: 5,
    title: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem.',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt...',
    category: 'FOOD INGREDIENTS',
    badgeClass: 'badge-green',
    image: Blog5,
    link: '/blog-detail'
  },
  {
    id: 6,
    title: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem.',
    excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt...',
    category: 'CULTURE',
    badgeClass: 'badge-gray',
    image: Blog2,
    link: '/blog-detail'
  }
]

const blogImages = [Blog1, Blog2, Blog3, Blog4, Blog5]

const filteredPosts = computed(() => {
  const query = search.value.toLowerCase().trim()
  return query ? posts.filter(post => post.title.toLowerCase().includes(query)) : posts
})

function subscribe() {
  if (!email.value) return
  alert(`Subscribed with ${email.value}`)
  email.value = ''
}
</script>

<style scoped>
* { box-sizing: border-box; }
.blog-page { font-family: 'Inter', sans-serif; background: #fefcf5; color: #1f2937; }
.blog-main { width: 100%; max-width: 1200px; margin: 0 auto; }
.container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.navbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 0; flex-wrap: wrap; }
.logo img { height: 44px; }
.nav-links { display: flex; gap: 1.5rem; flex-wrap: wrap; list-style: none; padding: 0; margin: 0; }
.nav-links li a { color: #4b5563; text-decoration: none; font-weight: 500; }
.nav-links li a.router-link-active { color: #f97316; }
.nav-icons { display: flex; align-items: center; gap: 1rem; }
.icon-btn, .icon-btn2 { width: 40px; height: 40px; border-radius: 50%; background: #f1f1f1; display: inline-flex; align-items: center; justify-content: center; color: #374151; text-decoration: none; }
.cart-wrapper { display: inline-flex; align-items: center; gap: 0.6rem; color: #f97316; }
.cart-badge { background: #f97316; color: #fff; border-radius: 9999px; padding: 0.15rem 0.65rem; font-size: 0.75rem; }
.blog-header { padding: 4rem 0 2rem; text-align: center; }
.page-title { font-family: 'Playfair Display', serif; font-size: clamp(2.4rem, 3vw, 3.6rem); margin-bottom: 1rem; }
.page-subtitle { color: #6b7280; margin-bottom: 2rem; }
.search-bar-wrapper { position: relative; display: inline-flex; align-items: center; width: min(520px, 100%); }
.search-bar-wrapper input { width: 100%; padding: 0.95rem 1.2rem 0.95rem 3rem; border-radius: 9999px; border: 1px solid #d1d5db; }
.search-icon { position: absolute; left: 1rem; color: #9ca3af; }
.featured-post { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center; background: #fff; border-radius: 32px; padding: 2rem; box-shadow: 0 12px 36px rgba(15,23,42,0.08); margin: 2rem 0; }
.featured-image img { width: 100%; border-radius: 24px; }
.post-badge { display: inline-flex; align-items: center; padding: 0.4rem 0.9rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; margin-bottom: 1rem; }
.badge-blue { background: #eff6ff; color: #2563eb; }
.btn { border: none; padding: 0.9rem 1.8rem; border-radius: 9999px; cursor: pointer; transition: transform 0.2s ease; }
.btn-outline { background: transparent; border: 1px solid #f97316; color: #f97316; }
.btn-outline:hover { transform: scale(1.02); }
.all-posts { margin: 2rem auto 4rem; }
.section-title { font-size: 2rem; margin-bottom: 1.5rem; }
.blog-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; justify-content: center; }
.blog-card { background: #fff; border-radius: 28px; padding: 1.5rem; box-shadow: 0 12px 30px rgba(15,23,42,0.08); transition: transform 0.2s ease; }
.blog-card:hover { transform: translateY(-4px); }
.card-img-wrapper { overflow: hidden; border-radius: 24px; margin-bottom: 1rem; }
.card-img-wrapper img { width: 100%; height: 220px; object-fit: cover; }
.badge-green { background: #ecfdf5; color: #16a34a; }
.badge-gray { background: #f3f4f6; color: #374151; }
.badge-purple { background: #f5f3ff; color: #7c3aed; }
.card-title-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 0.85rem; }
.card-title-row h3 { font-size: 1rem; margin: 0; }
.btn-arrow { background: #f97316; border: none; width: 40px; height: 40px; border-radius: 50%; color: white; cursor: pointer; }
.card-excerpt { color: #6b7280; line-height: 1.6; }
.footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; padding: 3rem 0; }
.footer-brand img { height: 40px; margin-bottom: 1rem; }
.footer-links ul { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 0.8rem; }
.footer-links a { color: #4b5563; text-decoration: none; }
.footer-newsletter .social-icons { display: flex; gap: 0.75rem; margin-bottom: 1rem; }
.social-circle { display: inline-flex; width: 38px; height: 38px; border-radius: 50%; background: #f3f4f6; align-items: center; justify-content: center; color: #374151; }
.subscribe-form { display: flex; flex-wrap: wrap; gap: 0.75rem; }
.subscribe-form input { flex: 1 1 200px; padding: 0.95rem 1rem; border: 1px solid #d1d5db; border-radius: 9999px; }
.subscribe-form button { border: none; background: #f97316; color: white; padding: 0.95rem 1.4rem; border-radius: 9999px; cursor: pointer; }
.footer-bottom { border-top: 1px solid #e5e6eb; padding: 1.25rem 0; color: #6b7280; text-align: center; }
@media (max-width: 900px) {
  .featured-post { grid-template-columns: 1fr; }
}
@media (max-width: 680px) {
  .navbar { flex-direction: column; align-items: flex-start; }
  .nav-links { flex-wrap: wrap; gap: 0.75rem; }
}
</style>