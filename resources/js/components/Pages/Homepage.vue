<template>
  <div>
    <div class="relative w-full ">
      <video autoplay muted loop class="w-full [height:93svh] object-cover">
        <source :src="videoPath" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <!-- Dark overlay -->
      <div class="absolute inset-0 bg-black opacity-50"></div>
      <!-- Centered text -->
      <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white ">
        <h1 class="text-6xl md:text-8xl font-bold mb-2">Aidella</h1>
        <p class="p-2 text-base md:text-xl">Bienvenue sur notre site web elevage de chiens depuis longtemps</p>
      </div>
    </div>
    <DogRaceGrid :dogRaces="dogRaces"/>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import DogRaceGrid from '../Homepage/DogRaceGrid.vue';

const props = defineProps(['initialData'])
const videoPath = '/storage/videos/video_banniere_aidella.mp4';

let dogRaces = ref(props.initialData.dogRaces);

</script>
