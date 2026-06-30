<template>
  <div class="about-page">
    <Navbar />

    <section class="about-hero container">
      <p class="section-subtitle">ABOUT US</p>
      <h1 class="hero-title">Your go-to source for tips and advice on healthy eating</h1>
      <button class="btn btn-primary mt-2" @click="goToContact">Contact us</button>
    </section>

    <section class="restaurant-info container split">
      <div class="restaurant-image">
        <img :src="FoodCollageImg" alt="Healthy Sandwich" />
      </div>
      <div class="restaurant-text"  >
        <h2>Our restaurant</h2>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
          labore et dolore magna aliqua.
        </p>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
          labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
          nisi ut aliquip ex ea commodo consequat.
        </p>
      </div>
    </section>

    <section class="features-section container">
      <h2 class="section-title">An extraordinary experience for all</h2>
      <div class="features-grid">
        <div class="feature-block" v-for="feature in features" :key="feature.title">
          <h3>{{ feature.title }}</h3>
          <p>{{ feature.text }}</p>
        </div>
      </div>
    </section>

    <section class="team-section container">
      <div class="team-header">
        <h2>Meet our best team</h2>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
          labore et dolore magna aliqua.
        </p>
      </div>
      <div class="team-grid">
        <div class="team-member" v-for="member in teamMembers" :key="member.name">
          <div class="member-img-wrapper" :class="member.bgClass">
            <img :src="member.img" :alt="member.name" />
          </div>
          <h4>{{ member.name }}</h4>
          <p>{{ member.role }}</p>
        </div>
      </div>
    </section>

    <section class="testimonials container">
      <div class="section-header-row">
        <div>
          <h2>Our customers say</h2>
          <p class="subtitle-text">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.
          </p>
        </div>
      </div>
      <div class="test-grid">
        <div class="test-card" v-for="testimonial in testimonials" :key="testimonial.name">
          <p class="quote">"{{ testimonial.quote }}"</p>
          <div class="user-info">
            <img :src="testimonial.img" :alt="testimonial.name" />
            <div>
              <h4>{{ testimonial.name }}</h4>
              <div class="stars">★★★★★</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer>
      <div class="container footer-grid">
        <div class="footer-brand">
          <img :src="LogoImg" alt="Crave" />
          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
            et dolore magna aliqua.
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
            <li><RouterLink to="/testimonials">Testimonials</RouterLink></li>
            <li><RouterLink to="/event">Event</RouterLink></li>
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Navbar from '../../components/Navbar.vue'

import LogoImg from '../../../asset/homepageImge/Logo1.png'
import FoodCollageImg from '../../../asset/homepageImge/foodcollage.png'
import Chef1 from '../../../asset/homepageImge/Chef1.png'
import Chef2 from '../../../asset/homepageImge/Chef2.png'
import Chef3 from '../../../asset/homepageImge/Chef3.png'
import Chef4 from '../../../asset/homepageImge/Chef4.png'
import Chef5 from '../../../asset/homepageImge/Chef5.png'
import Chef6 from '../../../asset/homepageImge/Chef6.png'
import Pf1 from '../../../asset/homepageImge/Pf1.png'
import Pf2 from '../../../asset/homepageImge/Pf2.png'
import Pf3 from '../../../asset/homepageImge/Pf3.png'

const router = useRouter()
const cartCount = ref(0)
const email = ref('')

const features = [
  {
    title: 'Passionate Chef',
    text:
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
  },
  {
    title: '100% Fresh Food',
    text:
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
  },
  {
    title: 'Special Dish for Occasions',
    text:
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
  },
  {
    title: 'Memorable Ambience',
    text:
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
  }
]

const teamMembers = [
  { name: 'Esther Howard', role: 'Head Chef', img: Chef1, bgClass: 'bg-gray' },
  { name: 'Wade Warren', role: 'Chef', img: Chef2, bgClass: 'bg-blue' },
  { name: 'Leslie Alexander', role: 'Chef', img: Chef3, bgClass: 'bg-orange' },
  { name: 'Robert Fox', role: 'Chef', img: Chef4, bgClass: 'bg-brown' },
  { name: 'Jenny Wilson', role: 'Chef', img: Chef5, bgClass: 'bg-pink' },
  { name: 'Jacob Jones', role: 'Chef', img: Chef6, bgClass: 'bg-gray' }
]

const testimonials = [
  {
    name: 'Brooklyn Simmons',
    quote:
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    img: Pf1
  },
  {
    name: 'Leslie Alexander',
    quote:
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    img: Pf2
  },
  {
    name: 'Jenny Wilson',
    quote:
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    img: Pf3
  }
]

function goToContact() {
  router.push('/contact')
}

function subscribe() {
  if (!email.value) return
  alert(`Subscribed with ${email.value}`)
  email.value = ''
}
</script>

<style scoped>
* { box-sizing: border-box; }
.about-page { font-family: 'Inter', sans-serif; background: #fefcf5; color: #1f2937; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.navbar { 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    gap: 1rem; 
    padding: 1rem 0; 
    flex-wrap: wrap; }
.logo img { height: 44px; }
.nav-links { display: flex; gap: 1.5rem; flex-wrap: wrap; list-style: none; padding: 0; margin: 0; }
.nav-links li a { color: #4b5563; text-decoration: none; font-weight: 500; }
.router-link-active { color: #f97316; }
.nav-icons { display: flex; align-items: center; gap: 1rem; }
.icon-btn, .icon-btn2 { width: 40px; height: 40px; border-radius: 50%; background: #f1f1f1; display: inline-flex; align-items: center; justify-content: center; color: #374151; text-decoration: none; }
.cart-wrapper { position: relative; display: inline-flex; align-items: center; gap: 0.6rem; color: #f97316; }
.cart-badge { background: #f97316; color: #fff; border-radius: 9999px; padding: 0.15rem 0.65rem; font-size: 0.75rem; }
.about-hero { text-align: center; padding: 4rem 0 2rem; }
.section-subtitle { color: #f97316; letter-spacing: 0.2em; font-weight: 700; margin-bottom: 1rem; }
.hero-title { font-family: 'Playfair Display', serif; font-size: clamp(2.3rem, 3vw, 3.6rem); margin-bottom: 1.25rem; }
.btn { border: none; padding: 0.95rem 2rem; border-radius: 9999px; cursor: pointer; transition: transform 0.2s ease; }
.btn-primary { background: #f97316; color: white; }
.btn-primary:hover { transform: scale(1.02); }
.split { display: grid; grid-template-columns: 1.1fr 1fr; gap: 3rem; align-items: center; margin: 3rem 0; }
.restaurant-image img { width: 100%; border-radius: 24px; }
.section-title { font-size: 2.2rem; margin-bottom: 1.5rem; }
.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; }
.feature-block { background: #fff; border-radius: 24px; padding: 1.6rem; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08); }
.team-header h2 { font-size: 2.2rem; margin-bottom: 0.75rem; }
.team-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; }
.team-member { background: #fff; border-radius: 24px; padding: 1.4rem; text-align: center; box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06); }
.member-img-wrapper { width: 120px; height: 120px; margin: 0 auto 1rem; border-radius: 30px; display: grid; place-items: center; overflow: hidden; }
.member-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
.bg-gray { background: #f3f4f6; }
.bg-blue { background: #dbeafe; }
.bg-orange { background: #ffe7d9; }
.bg-brown { background: #f8ede7; }
.bg-pink { background: #fde4ec; }
.testimonials { margin: 3rem 0; }
.section-header-row { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
.subtitle-text { color: #6b7280; max-width: 560px; }
.test-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; }
.test-card { background: #fff; border-radius: 24px; padding: 1.6rem; box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06); }
.quote { color: #374151; line-height: 1.75; margin-bottom: 1.25rem; }
.user-info { display: flex; align-items: center; gap: 1rem; }
.user-info img { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; }
.stars { color: #f59e0b; letter-spacing: 0.1em; }
.footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; padding: 3rem 0; }
.footer-brand img { height: 40px; margin-bottom: 1rem; }
.footer-links ul { list-style: none; padding: 0; margin: 0; }
.footer-links li { margin-bottom: 0.8rem; }
.footer-links a { color: #4b5563; text-decoration: none; }
.footer-newsletter .social-icons { display: flex; gap: 0.75rem; margin-bottom: 1rem; }
.social-circle { display: inline-flex; width: 38px; height: 38px; border-radius: 50%; background: #f3f4f6; align-items: center; justify-content: center; color: #374151; }
.subscribe-form { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.subscribe-form input { flex: 1 1 200px; padding: 0.9rem 1rem; border-radius: 9999px; border: 1px solid #d1d5db; }
.subscribe-form button { border: none; background: #f97316; color: #fff; padding: 0.95rem 1.4rem; border-radius: 9999px; cursor: pointer; }
.footer-bottom { border-top: 1px solid #e5e7eb; padding: 1.25rem 0; color: #6b7280; text-align: center; }
@media (max-width: 900px) {
  .split { grid-template-columns: 1fr; }
}
@media (max-width: 680px) {
  .navbar { flex-direction: column; align-items: flex-start; }
  .nav-links { flex-wrap: wrap; gap: 0.75rem; }
}
</style>