import { createApp } from 'vue'
import App from './components/App.vue'
import Homepage from './components/Pages/Homepage.vue'
import About from './components/Pages/About.vue'
import SingleDog from './components/Pages/SingleDog.vue'

const app = createApp(App)

app.component('Homepage', Homepage)
app.component('About', About)
app.component('SingleDog', SingleDog)

app.mount('#app')
