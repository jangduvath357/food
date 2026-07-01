<template>
  <div class="home-page">
    <Navbar />

    <section class="hero container">
      <div class="hero-content">
        <p class="subtitle">100% QUALITY FOOD RECIPES</p>
        <h1>Disfruta de una comida sana y deliciosa.</h1>
        <div class="hero-buttons">
          <button class="btn btn-primary" type="button" @click="goToMenu">Order Now</button>
          <button class="btn btn-secondary" type="button">Watch Video</button>
        </div>
        <div class="reviews">
          <div class="avatars">
            <img :src="Pf41" alt="user" />
            <img :src="Pf5" alt="user" />
            <img :src="Pf6" alt="user" />
          </div>
          <div class="rating">
            <p><strong>4.8/5</strong> Excellent</p>
            <div class="stars">★★★★★</div>
          </div>
        </div>
      </div>
      <div class="hero-image">
        <img :src="TacoImg" alt="Taco" />
      </div>
    </section>

    <section class="welcome container">
      <h2>Welcome to Crave</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      <button class="btn btn-primary" type="button">Read more</button>
    </section>

    <section class="menu container">
      <div class="section-header">
        <div>
          <h2>Our popular menu</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
        <button class="btn btn-primary" type="button" @click="goToMenu">See all</button>
      </div>
      <div class="grid menu-grid">
        <div class="menu-link" v-for="(item, idx) in menuItems" :key="idx">
          <div class="card">
            <div class="badge" v-if="item.badge">{{ item.badge }}</div>
            <img :src="item.img" :alt="item.name" />
            <div class="card-content">
              <div class="card-title-row">
                <h3>{{ item.name }}</h3>
                <span class="price">${{ item.price }}</span>
              </div>
              <p class="rating">★ {{ item.rating }} <span>({{ item.reviews }} reviews)</span></p>
              <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
              <button class="add-btn" type="button" @click.stop="addToCart(item)"><i class="fa-solid fa-plus"></i></button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="reserve container split">
      <div class="reserve-text">
        <h2>Let's reserve a table</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <button class="btn btn-primary mt-2" type="button">Reserve now</button>
      </div>
      <div class="reserve-image">
        <img :src="FoodCollageImg" alt="Food Collage" />
      </div>
    </section>

    <section class="team container split">
      <div class="team-text">
        <h2>Meet our best team</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <button class="btn btn-primary mt-2" type="button">View all</button>
      </div>
      <div class="grid team-grid">
        <div class="team-card" v-for="member in teamMembers" :key="member.name">
          <img :src="member.img" :alt="member.name" />
          <h4>{{ member.name }}</h4>
          <p>{{ member.role }}</p>
        </div>
      </div>
    </section>

    <section class="testimonials container" id="about">
      <div class="section-header">
        <div>
          <h2>Our customers say</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
        <div class="arrows">
          <button class="arrow-btn" type="button"><i class="fa-solid fa-arrow-left"></i></button>
          <button class="arrow-btn" type="button"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
      </div>
      <div class="grid test-grid">
        <div class="test-card" v-for="t in testimonials" :key="t.name">
          <p class="quote">"{{ t.quote }}"</p>
          <div class="user-info">
            <img :src="t.img" :alt="t.name" />
            <div>
              <h4>{{ t.name }}</h4>
              <div class="stars">★★★★★</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="contact-info container">
      <div class="pizza-img">
        <img :src="PizzaImg" alt="Pizza" />
      </div>
      <div class="info-blocks">
        <div class="info-block">
          <h3>Contact us</h3>
          <p>+1(415) 555-2671</p>
          <p>info@crave.com</p>
          <p>2972 Westheimer Rd. Santa Ana, Illinois 85486</p>
          <button class="btn btn-primary mt-2" type="button">Get directions</button>
        </div>
        <div class="info-block">
          <h3>We are open from</h3>
          <p><strong>Monday - Friday:</strong></p>
          <p>Lunch: 11:30am - 2:30pm<br />Dinner: 5:00pm - 10:00pm</p>
          <button class="btn btn-outline-orange mt-2" type="button">Make a reservation</button>
        </div>
      </div>
    </section>

    <section class="features container" id="blog">
      <div class="feature-item">
        <div class="icon-circle pink"><i class="fa-solid fa-medal"></i></div>
        <div><h4>Quality food</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>
      </div>
      <div class="feature-item">
        <div class="icon-circle green"><i class="fa-solid fa-truck"></i></div>
        <div><h4>Fast delivery</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>
      </div>
      <div class="feature-item">
        <div class="icon-circle orange"><i class="fa-regular fa-credit-card"></i></div>
        <div><h4>Easy payment</h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>
      </div>
    </section>

    <footer>
      <div class="container footer-grid">
        <div class="footer-brand">
          <img :src="LogoImg" alt="Crave" />
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
        <div class="footer-links">
          <h4>Pages</h4>
          <ul>
            <li><RouterLink to="/">Home</RouterLink></li>
            <li><RouterLink to="/menu">Menu</RouterLink></li>
            <li><RouterLink to="/about">About us</RouterLink></li>
            <li><RouterLink to="/reservation">Reservation</RouterLink></li>
            <li><RouterLink to="/blog">Blog</RouterLink></li>
          </ul>
        </div>
        <div class="footer-links">
          <h4>Information</h4>
          <ul>
            <li><a href="#">Terms & Conditions</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">FAQ</a></li>
          </ul>
        </div>
        <div class="footer-newsletter">
          <h4>Get in touch</h4>
          <div class="social-icons">
            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
            <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          </div>
          <form class="subscribe-form" @submit.prevent="subscribe">
            <input type="email" placeholder="Enter your email" v-model="email" required />
            <button type="submit">Subscribe</button>
          </form>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Navbar from '../../components/Navbar.vue'

import LogoImg from '../../../asset/homepageImge/Logo1.png'
import Pf41 from '../../../asset/homepageImge/Pf4 (1).png'
import Pf5 from '../../../asset/homepageImge/Pf5.png'
import Pf6 from '../../../asset/homepageImge/Pf6.png'
import TacoImg from '../../../asset/homepageImge/Taco.png'
import FoodCollageImg from '../../../asset/homepageImge/foodcollage.png'
import PizzaImg from '../../../asset/homepageImge/Pizza.png'
import Chef1 from '../../../asset/homepageImge/Chef1.png'
import Chef2 from '../../../asset/homepageImge/Chef2.png'
import Chef3 from '../../../asset/homepageImge/Chef3.png'
import Chef4 from '../../../asset/homepageImge/Chef4.png'
import Chef5 from '../../../asset/homepageImge/Chef5.png'
import Chef6 from '../../../asset/homepageImge/Chef6.png'
import Av1 from '../../../asset/homepageImge/Pf1.png'
import Av2 from '../../../asset/homepageImge/Pf2.png'
import Av3 from '../../../asset/homepageImge/Pf3.png'
import SpaghettiImg from '../../../asset/homepageImge/spagatti.png'
import GnocchiImg from '../../../asset/homepageImge/gnochhi.png'
import RavioliImg from '../../../asset/homepageImge/ravioli.png'
import PenneImg from '../../../asset/homepageImge/penne arrabbiata.png'
import RisottoImg from '../../../asset/homepageImge/Toasted Ravioli.png'

const router = useRouter()
const cartCount = ref(0)
const email = ref('')

const menuItems = ref([
  { name: 'Spaghetti', price: '12.00', rating: '4.8', reviews: '120', badge: '15% Off', img: '../../../asset/homepageImge/spagatti.png' },
  { name: 'Gnocchi', price: '14.00', rating: '4.7', reviews: '98', badge: null, img: '../../../asset/homepageImge/gnochhi.png' },
  { name: 'Ravioli', price: '16.00', rating: '4.9', reviews: '210', badge: '20% Off', img: '../../../asset/homepageImge/ravioli.png' },
  { name: 'Penne Arrabbiata', price: '11.00', rating: '4.6', reviews: '85', badge: '10% Off', img: '../../../asset/homepageImge/penne%20arrabbiata.png' },
  { name: 'Mushroom Risotto', price: '15.00', rating: '4.8', reviews: '140', badge: null, img: '../../../asset/homepageImge/Toasted Ravioli.png' },
  { name: 'Margherita Pizza', price: '18.00', rating: '4.9', reviews: '300', badge: null, img: '../../../asset/homepageImge/Pizza.png' }
])

const teamMembers = ref([
  { name: 'Esther Howard', role: 'Head Chef', img: Chef1 },
  { name: 'Wade Warren', role: 'Chef', img: Chef2 },
  { name: 'Leslie Alexander', role: 'Chef', img: Chef3 },
  { name: 'Robert Fox', role: 'Chef', img: Chef4 },
  { name: 'Jacob Jones', role: 'Chef', img: Chef5 },
  { name: 'Savannah Nguyen', role: 'Chef', img: Chef6 }
])

const testimonials = ref([
  { name: 'Brooklyn Simmons', quote: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.', img: Av1 },
  { name: 'Kristin Watson', quote: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.', img: Av2 },
  { name: 'Guy Hawkins', quote: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.', img: Av3 }
])

function addToCart() {
  cartCount.value++
}

function goToMenu() {
  router.push('/menu')
}

function subscribe() {
  if (!email.value) return
  alert(`Subscribed with ${email.value}`)
  email.value = ''
}
</script>

<style scoped>
* { box-sizing: border-box; }
.home-page { font-family: 'Inter', sans-serif; background: #fefcf5; color: #1e1e1e; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
img { max-width: 100%; display: block; }
a { text-decoration: none; color: inherit; }
.btn {
  border: none;
  padding: 12px 28px;
  border-radius: 40px;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: 0.2s;
}
.btn-primary {
  background: #f97316;
  color: #fff;
}
.btn-primary:hover { background: #e0660a; transform: scale(1.02); }
.btn-secondary {
  background: transparent;
  border: 1.5px solid #f97316;
  color: #f97316;
}
.btn-secondary:hover { background: #f97316; color: #fff; }
.btn-outline-orange {
  background: transparent;
  border: 1.5px solid #f97316;
  color: #f97316;
}
.btn-outline-orange:hover { background: #f97316; color: #fff; }
.mt-2 { margin-top: 16px; }
.grid { display: grid; gap: 28px; }
.split { display: flex; flex-wrap: wrap; align-items: center; gap: 40px; }
.split > * { flex: 1 1 280px; }
.section-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  flex-wrap: wrap;
  margin-bottom: 30px;
}
.section-header h2 { font-family: 'Playfair Display', serif; font-size: 2.2rem; }
.stars { color: #f59e0b; letter-spacing: 2px; }
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1rem 0;
  background: #fff;
  border-bottom: 1px solid rgba(15, 23, 42, 0.08);
  box-shadow: 0 1px 0 rgba(15, 23, 42, 0.08);
  position: sticky;
  top: 0;
  z-index: 10;
  border-radius: 10px;
}
.logo img { height: 48px; }
.nav-links {
  display: flex;
  list-style: none;
  gap: 32px;
  font-weight: 500;
}
.nav-icons {
  display: flex;
  align-items: center;
  gap: 18px;
}
.icon-btn, .icon-btn2 {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #f1f1f1;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: 0.2s;
}
.icon-btn:hover, .icon-btn2:hover { background: #e2e2e2; }
.cart-wrapper {
  position: relative;
  cursor: pointer;
}
.cart-wrapper i { font-size: 1.4rem; }
.cart-badge {
  position: absolute;
  top: -8px;
  right: -10px;
  background: #f97316;
  color: #fff;
  border-radius: 50%;
  padding: 2px 8px;
  font-size: 0.7rem;
  font-weight: 600;
}
.hero { display: flex; flex-wrap: wrap; align-items: center; gap: 30px; padding: 20px 0; }
.hero-content { flex: 1 1 300px; }
.hero-content .subtitle { color: #f97316; font-weight: 600; letter-spacing: 1px; font-size: 0.9rem; }
.hero-content h1 { font-family: 'Playfair Display', serif; font-size: 3rem; line-height: 1.2; margin: 12px 0; }
.hero-buttons { display: flex; flex-wrap: wrap; gap: 14px; margin: 20px 0; }
.reviews { display: flex; align-items: center; gap: 20px; margin-top: 20px; }
.avatars { display: flex; }
.avatars img { width: 44px; height: 44px; border-radius: 50%; margin-right: -12px; border: 2px solid #fff; }
.hero-image { flex: 1 1 280px; text-align: center; }
.welcome { text-align: center; padding: 50px 0; max-width: 800px; margin: 0 auto; }
.welcome h2 { font-family: 'Playfair Display', serif; font-size: 2.5rem; }
.welcome p { margin: 20px 0; color: #4b4b4b; line-height: 1.7; }
.menu-grid { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
.menu-link { display: block; text-decoration: none; color: inherit; }
.card {
  background: #fff;
  border-radius: 28px;
  padding: 18px 18px 20px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.04);
  transition: 0.2s;
  position: relative;
}
.card:hover { transform: translateY(-6px); box-shadow: 0 14px 30px rgba(0,0,0,0.08); }
.badge {
  position: absolute;
  top: 16px;
  left: 16px;
  background: #f97316;
  color: #fff;
  padding: 4px 12px;
  border-radius: 30px;
  font-size: 0.7rem;
  font-weight: 600;
}
.card img { width: 100%; height: 160px; object-fit: contain; margin-bottom: 10px; }
.card-title-row { display: flex; justify-content: space-between; align-items: center; }
.card-title-row h3 { font-size: 1.1rem; }
.price { font-weight: 700; color: #f97316; }
.rating { font-size: 0.9rem; margin: 6px 0; }
.rating span { color: #7a7a7a; }
.desc { font-size: 0.85rem; color: #5a5a5a; margin: 6px 0 12px; }
.add-btn {
  background: #f97316;
  border: none;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  color: #fff;
  font-size: 1.2rem;
  cursor: pointer;
  transition: 0.2s;
}
.add-btn:hover { background: #d95d0a; transform: scale(1.05); }
.reserve-text h2, .team-text h2 { font-family: 'Playfair Display', serif; font-size: 2.4rem; }
.reserve-image img { border-radius: 30px; }
.team-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
.team-card { background: #fff; border-radius: 24px; padding: 20px 10px; text-align: center; box-shadow: 0 6px 18px rgba(0,0,0,0.03); }
.team-card img { width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 12px; object-fit: cover; }
.team-card h4 { margin-top: 6px; }
.test-grid { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
.test-card { background: #fff; border-radius: 28px; padding: 24px; box-shadow: 0 6px 18px rgba(0,0,0,0.03); }
.test-card .quote { font-style: italic; color: #2e2e2e; line-height: 1.6; }
.user-info { display: flex; align-items: center; gap: 14px; margin-top: 16px; }
.user-info img { width: 48px; height: 48px; border-radius: 50%; }
.arrows { display: flex; gap: 10px; }
.arrow-btn {
  width: 40px; height: 40px; border-radius: 50%;
  border: 1px solid #ddd; background: #fff;
  cursor: pointer;
  transition: 0.2s;
}
.arrow-btn:hover { background: #f97316; color: #fff; border-color: #f97316; }
.contact-info { display: flex; flex-wrap: wrap; align-items: center; gap: 30px; padding: 20px 0; }
.pizza-img { flex: 1 1 200px; }
.info-blocks { flex: 2 1 300px; display: flex; flex-wrap: wrap; gap: 30px; }
.info-block { background: #fff; border-radius: 28px; padding: 28px; flex: 1 1 200px; box-shadow: 0 6px 18px rgba(0,0,0,0.03); }
.info-block h3 { margin-bottom: 12px; font-family: 'Playfair Display', serif; }
.features { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 20px; padding: 20px 0 40px; }
.feature-item { display: flex; align-items: center; gap: 16px; flex: 1 1 180px; }
.icon-circle {
  width: 56px; height: 56px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; color: #fff;
}
.pink { background: #f472b6; }
.green { background: #34d399; }
.orange { background: #fb923c; }
footer { background: #1e1e1e; color: #ddd; padding: 40px 0; margin-top: 30px; }
.footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 30px; }
.footer-brand img { height: 40px; margin-bottom: 12px; filter: brightness(0) invert(1); }
.footer-links ul { list-style: none; padding: 0; }
.footer-links li { margin: 8px 0; }
.footer-links a { color: #ccc; transition: 0.2s; }
.footer-links a:hover { color: #f97316; }
.social-icons { display: flex; gap: 16px; font-size: 1.4rem; margin: 12px 0; }
.social-icons i { cursor: pointer; transition: 0.2s; }
.social-icons i:hover { color: #f97316; }
.subscribe-form { display: flex; margin-top: 10px; }
.subscribe-form input {
  flex: 1; padding: 10px 14px; border: none; border-radius: 40px 0 0 40px;
  outline: none;
}
.subscribe-form button {
  background: #f97316; border: none; padding: 10px 20px;
  border-radius: 0 40px 40px 0; color: #fff; font-weight: 600;
  cursor: pointer; transition: 0.2s;
}
.subscribe-form button:hover { background: #d95d0a; }
@media (max-width: 768px) {
  .navbar { flex-direction: column; gap: 14px; padding: 16px; }
  .nav-links { flex-wrap: wrap; justify-content: center; gap: 16px; }
  .hero-content h1 { font-size: 2.2rem; }
  .section-header h2 { font-size: 1.8rem; }
}
</style>
