<template>
  <div>
    <h1>{{ dogRace.name }}</h1>
    <!-- Your dog race content -->
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Get data from the global window object set in Blade
const dogRaces = ref(window.pageData.dogRaces)
const dogRace = ref(window.pageData.dogRace)

console.log('Dogs', dogRaces.value)
console.log('Dog', dogRace.value)
</script>
