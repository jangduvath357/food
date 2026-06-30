<template>
  <div class="contact-page">
    <Navbar />

    <section class="contact-section">
      <div class="contact-left">
        <p class="eyebrow"><span class="eyebrow-line"></span> Contact Us</p>
        <h1>How we can help?</h1>
        <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>

        <div class="info-list">
          <div class="info-item">
            <div class="info-icon">📞</div>
            <div>
              <h4>Phone</h4>
              <p>(803) 555-0123</p>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon">✉️</div>
            <div>
              <h4>Email</h4>
              <p>support@craveapp.com</p>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon">📍</div>
            <div>
              <h4>Location</h4>
              <p>2972 Westheimer Rd. Santa Ana, Illinois 85486</p>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon">⏰</div>
            <div>
              <h4>We are open from</h4>
              <p>Monday–Sunday<br/>Lunch: 11:00am–02:00pm<br/>Dinner: 04:00pm–08:00pm</p>
            </div>
          </div>
        </div>
      </div>

      <div class="contact-form">
        <form @submit.prevent="submitContact">
          <div class="form-group">
            <label for="name">Name</label>
            <input id="name" v-model="contactName" type="text" placeholder="Enter your name" />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input id="email" v-model="contactEmail" type="email" placeholder="Enter your email" />
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" v-model="contactMessage" placeholder="Enter message"></textarea>
          </div>
          <button class="btn-book" type="submit">Send message</button>
        </form>
      </div>
    </section>

    <section class="faq-section">
      <h2>Frequently asked questions</h2>
      <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>

      <div class="faq-list">
        <div v-for="item in faqItems" :key="item.id" class="faq-item" :class="{ open: selectedFaq === item.id }">
          <button class="faq-question" @click="toggleFaq(item.id)">
            <span>{{ item.question }}</span>
            <span class="faq-icon">+</span>
          </button>
          <div class="faq-answer">
            <p>{{ item.answer }}</p>
            <ul v-if="item.list?.length">
              <li v-for="(entry, i) in item.list" :key="i">{{ entry }}</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <div class="map-wrap">
      <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1600&auto=format&fit=crop&q=80" alt="Map" />
    </div>

    <footer>
      <div class="footer-grid">
        <div class="footer-brand">
          <RouterLink to="/" class="logo">
            <div class="logo-circle">C</div>
            <span>Crave</span>
          </RouterLink>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>

        <div class="footer-col">
          <h4>Pages</h4>
          <ul>
            <li><RouterLink to="/">Home</RouterLink></li>
            <li><RouterLink to="/menu">Menu</RouterLink></li>
            <li><RouterLink to="/order">Order online</RouterLink></li>
            <li><RouterLink to="/reservation">Reservation</RouterLink></li>
            <li><RouterLink to="/blog">Blog</RouterLink></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Information</h4>
          <ul>
            <li><RouterLink to="/about">About us</RouterLink></li>
            <li><a href="#">Testimonials</a></li>
            <li><a href="#">Event</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Get in touch</h4>
          <div class="social-row">
            <div class="social-circle">f</div>
            <div class="social-circle">w</div>
            <div class="social-circle">ig</div>
          </div>
          <form class="subscribe-form" @submit.prevent="handleSubscribe">
            <input type="email" v-model="subscribeEmail" placeholder="Enter email address" />
            <button type="submit">Subscribe</button>
          </form>
        </div>
      </div>

      <div class="footer-bottom">
        <p>Copyright © 2026 Crave</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import Navbar from '../../components/Navbar.vue'

const contactName = ref('')
const contactEmail = ref('')
const contactMessage = ref('')
const subscribeEmail = ref('')
const selectedFaq = ref(4)

const faqItems = [
  { id: 1, question: 'Lorem ipsum dolor sit amet consectetur adipiscing elit?', answer: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' },
  { id: 2, question: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem?', answer: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.' },
  { id: 3, question: 'Nemo enim ipsam voluptatem quia voluptas sit?', answer: 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur.' },
  { id: 4, question: 'Excepteur sint occaecat cupidatat non proident?', answer: 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim.', list: [
      'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur.',
      'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.'
    ]
  }
]

function toggleFaq(id) {
  selectedFaq.value = selectedFaq.value === id ? null : id
}

function submitContact() {
  alert(`Thanks, ${contactName.value || 'guest'}! Your message has been sent.`)
  contactName.value = ''
  contactEmail.value = ''
  contactMessage.value = ''
}

function handleSubscribe() {
  if (!subscribeEmail.value) return
  alert(`Subscribed with ${subscribeEmail.value}`)
  subscribeEmail.value = ''
}
</script>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root { --cream: #F7F0E6; --warm-brown: #C4622A; --deep-brown: #2E1A0E; --text-muted: rgba(46,26,14,0.6); }
.contact-page { font-family: 'DM Sans', sans-serif; color: var(--deep-brown); background: #fff; }
nav { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 3rem; border-bottom: 0.5px solid rgba(46,26,14,0.08); }
.logo { display: flex; align-items: center; gap: 8px; text-decoration: none; color: inherit; }
.logo-circle { width: 30px; height: 30px; background: var(--warm-brown); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-family: 'Cormorant Garant', serif; font-style: italic; font-size: 15px; }
.logo span { font-family: 'Cormorant Garant', serif; font-size: 19px; font-weight: 600; color: var(--deep-brown); }
.nav-links { display: flex; gap: 2rem; list-style: none; }
.nav-links a { text-decoration: none; font-size: 13px; color: var(--text-muted); transition: color 0.2s; }
.nav-links a:hover, .router-link-active { color: var(--deep-brown); }
.nav-actions { display: flex; align-items: center; gap: 0.8rem; }
.icon-btn, .cart-btn { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; }
.icon-btn { background: #F7F0E6; color: var(--deep-brown); }
.icon-btn:hover { background: #EDD5CC; }
.cart-btn { background: var(--warm-brown); color: #fff; position: relative; }
.cart-badge { position: absolute; top: -3px; right: -3px; background: #2E1A0E; color: #fff; font-size: 9px; border-radius: 50%; width: 15px; height: 15px; display: flex; align-items: center; justify-content: center; }
.contact-section { padding: 3rem 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; max-width: 1100px; margin: 0 auto; }
.eyebrow-line { display: inline-block; width: 24px; height: 1px; background: var(--warm-brown); margin-right: 8px; }
.contact-left .eyebrow { display: flex; align-items: center; gap: 10px; font-size: 11px; font-weight: 500; letter-spacing: 0.14em; text-transform: uppercase; color: var(--warm-brown); margin-bottom: 1rem; }
.contact-left h1 { font-family: 'Cormorant Garant', serif; font-size: 2.2rem; font-weight: 500; color: var(--deep-brown); margin-bottom: 1rem; }
.subtitle { font-size: 14px; color: var(--text-muted); line-height: 1.75; max-width: 420px; margin-bottom: 2rem; }
.info-list { display: flex; flex-direction: column; gap: 1.2rem; }
.info-item { display: flex; gap: 1rem; align-items: flex-start; }
.info-icon { width: 40px; height: 40px; border-radius: 50%; background: #F7F0E6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--deep-brown); }
.info-item h4 { font-size: 14px; font-weight: 600; color: var(--deep-brown); margin-bottom: 4px; }
.info-item p { font-size: 13px; color: var(--text-muted); line-height: 1.6; }
.contact-form .form-group { margin-bottom: 1rem; }
.contact-form label { display: block; font-size: 13px; font-weight: 500; color: var(--deep-brown); margin-bottom: 6px; }
.contact-form input, .contact-form textarea { width: 100%; padding: 12px 16px; border: 1px solid rgba(46,26,14,0.15); border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 13px; outline: none; transition: border-color 0.2s; color: var(--deep-brown); }
.contact-form input:focus, .contact-form textarea:focus { border-color: var(--warm-brown); }
.contact-form textarea { resize: vertical; min-height: 90px; }
.btn-book { width: 100%; padding: 12px; background: var(--deep-brown); color: #fff; border: none; border-radius: 100px; font-size: 14px; font-weight: 500; cursor: pointer; margin-top: 0.5rem; transition: background 0.2s, transform 0.15s; }
.btn-book:hover { background: var(--warm-brown); transform: translateY(-1px); }
.faq-section { max-width: 800px; margin: 0 auto; padding: 0 1.5rem 3rem; text-align: center; }
.faq-section h2 { font-family: 'Cormorant Garant', serif; font-size: 2rem; font-weight: 500; color: var(--deep-brown); margin-bottom: 0.8rem; }
.faq-list { text-align: left; display: flex; flex-direction: column; }
.faq-item { border-bottom: 1px solid rgba(46,26,14,0.1); }
.faq-question { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 0; cursor: pointer; background: none; border: none; width: 100%; text-align: left; font-size: 14px; font-weight: 500; color: var(--deep-brown); font-family: 'DM Sans', sans-serif; }
.faq-icon { font-size: 18px; color: var(--warm-brown); transition: transform 0.25s; flex-shrink: 0; margin-left: 1rem; }
.faq-item.open .faq-icon { transform: rotate(45deg); }
.faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.3s ease, padding 0.3s ease; }
.faq-item.open .faq-answer { max-height: 220px; padding-bottom: 1.3rem; }
.faq-answer p { font-size: 13px; color: var(--text-muted); line-height: 1.75; margin-bottom: 0.6rem; }
.map-wrap { width: 100%; height: 320px; overflow: hidden; margin-top: 1rem; }
.map-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
footer { background: var(--deep-brown); color: var(--cream); padding: 3rem 1.5rem 1.5rem; }
.footer-grid { display: grid; grid-template-columns: 1.6fr 1fr 1fr 1.3fr; gap: 1.25rem; max-width: 1100px; margin: 0 auto; padding-bottom: 1.5rem; }
.footer-brand p { font-size: 13px; color: rgba(247,240,230,0.55); line-height: 1.6; margin-top: 0.6rem; max-width: 260px; }
.footer-col h4 { font-size: 14px; font-weight: 600; margin-bottom: 0.9rem; color: var(--cream); }
.footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; }
.footer-col a { text-decoration: none; font-size: 13px; color: rgba(247,240,230,0.6); transition: color 0.2s; }
.subscribe-form { display: flex; gap: 0; border-radius: 100px; overflow: hidden; background: rgba(247,240,230,0.08); padding: 4px; }
.subscribe-form input { flex: 1; background: transparent; border: none; padding: 10px 16px; font-size: 12px; color: var(--cream); outline: none; }
.subscribe-form button { background: var(--cream); color: var(--deep-brown); border: none; border-radius: 100px; padding: 10px 20px; font-size: 12px; font-weight: 500; cursor: pointer; }
.footer-bottom { text-align: center; padding-top: 1rem; border-top: 0.5px solid rgba(247,240,230,0.1); font-size: 12px; color: rgba(247,240,230,0.4); }
@media (max-width: 900px) { .nav-links { display: none; } .contact-section { grid-template-columns: 1fr; padding: 2rem 1rem; } .footer-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 520px) { .footer-grid { grid-template-columns: 1fr; } }
</style>
