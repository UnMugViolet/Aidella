<template>
  <div>
    <div class="relative w-full">
      <video autoplay muted loop class="w-full [height:93svh] object-cover">
        <source :src="videoPath" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <!-- Dark overlay -->
      <div class="absolute inset-0 bg-black opacity-50"></div>
      <!-- Centered text -->
      <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white ">
        <h1 class="text-6xl md:text-8xl font-semibold mb-2">Aidella</h1>
        <p class="p-2 text-base md:text-xl">Bienvenue sur notre site dédié aux races de chiens</p>
      </div>
    </div>
    <div class="flex justify-center">
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full max-w-6xl p-2">
        <a :href="'/race/' + dogRace.slug" v-for="(dogRace, index) in sortedDogRaces" :key="index">
          <div class="flex flex-col w-full p-2">
            <div
              class="w-full h-80 bg-center bg-cover rounded-xs mb-2"
              :style="{
                backgroundImage: getMainPicture(dogRace.pictures)
                  ? `url('${getMainPicture(dogRace.pictures).path}')`
                  : `url('${noImagePath}')`
              }"
            ></div>
            <span class="text-center font-semibold">{{ dogRace.name }}</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
const props = defineProps(['initialData'])

let dogRaces = ref(props.initialData.dogRaces);
const noImagePath = 'https://placehold.co/800x800?text=NO+IMAGE';
const videoPath = '/videos/video_banniere_aidella.mp4';

console.log(dogRaces.value);

const sortedDogRaces = computed(() =>
  [...dogRaces.value].sort((a, b) => (a.order ?? 0) - (b.order ?? 0))
);

const getMainPicture = (pictures) => {
  if (!pictures || pictures.length === 0) {
    return null;
  }
  
  for (const picture of pictures) {
    if (picture.is_main) {
      return picture;
    }
  }
  return null;
}

</script>
