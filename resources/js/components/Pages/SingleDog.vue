<template>
  <section class="px-4 md:px-8 py-8 mt-10">
    <h1 class="text-6xl md:text-7xl font-semibold text-left py-4 uppercase">{{ dogRace.name }}</h1>
    <!-- Your dog race content -->
  </section>
</template>

<script setup>
import { ref } from 'vue'

// Get data from the global window object set in Blade
const dogRaces = ref(window.pageData.dogRaces)
const dogRace = ref(window.pageData.dogRace)

console.log('Dogs', dogRaces.value)
console.log('Dog', dogRace.value)
</script>
