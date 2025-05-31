<script setup>
import { shallowRef, onMounted } from 'vue'
import { defineAsyncComponent } from 'vue'
import Header from './Layouts/Header.vue'
import Footer from './Layouts/Footer.vue'

const currentComponent = shallowRef(null)
const pageData = shallowRef({})

onMounted(() => {
  const name = window.currentComponent || 'Homepage'
  pageData.value = window.pageData || {}

  try {
    currentComponent.value = defineAsyncComponent(() => import(`./Pages/${name}.vue`))
  } 
  catch (error) {
    console.error(`Failed to load component: ${name}`, error)
    currentComponent.value = defineAsyncComponent(() => import('./Pages/Homepage.vue'))
  }
})

</script>

<template>
  <div id="app">
    <Header :dogRaces="pageData.dogRaces"/>
    <main>
      <component :is="currentComponent" :initial-data="pageData" />
    </main>
    <Footer />
  </div>
</template>
