import { createApp } from 'vue'
import App from './components/App.vue'
import Homepage from './components/Pages/Homepage.vue'
import About from './components/Pages/About.vue'
import SingleDog from './components/Pages/SingleDog.vue'
import SinglePost from './components/Pages/SinglePost.vue'
import LegalMentions from './components/Pages/LegalMentions.vue'
import PrivacyPolicy from './components/Pages/PrivacyPolicy.vue'
import CGU from './components/Pages/CGU.vue'

const app = createApp(App)

app.component('Homepage', Homepage)
app.component('About', About)
app.component('SingleDog', SingleDog)
app.component('SinglePost', SinglePost)
app.component('LegalMentions', LegalMentions)
app.component('PrivacyPolicy', PrivacyPolicy)
app.component('CGU', CGU)

app.mount('#app')
