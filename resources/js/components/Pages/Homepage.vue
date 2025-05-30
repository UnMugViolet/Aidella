<template>
  <div>
    <h1>Accueil Aidella</h1>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps(['initialData'])

let dogRaces = ref(props.initialData.dogRaces);

console.log('Homepage initial data: ' + JSON.stringify(dogRaces.value))
</script>
