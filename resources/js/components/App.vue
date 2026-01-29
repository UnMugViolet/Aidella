<script setup>
import { shallowRef, onMounted, defineAsyncComponent } from 'vue'
import Header from './Layouts/Header.vue'
import Footer from './Layouts/Footer.vue'

const currentComponent = shallowRef(null)
const pageData = shallowRef({})

onMounted(() => {
  const name = globalThis.currentComponent || 'Homepage'
  pageData.value = globalThis.pageData || {}

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
    <Header :dogPages="pageData.dogPages"/>
    <main>
      <component :is="currentComponent" :initial-data="pageData" />
    </main>
    <Footer />
  </div>
</template>
