<template>
  <div>
    <h1>{{ dogRace.name }}</h1>
    <!-- Your dog race content -->
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps(['initialData'])
const dogRace = ref(props.initialData)

console.log('Dogs' + JSON.stringify(dogRace.value))
</script>
