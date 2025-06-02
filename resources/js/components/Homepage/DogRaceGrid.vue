<template>
  <section class="px-4 md:px-8 md:py-8">
    <h2 class="text-6xl md:text-7xl font-semibold text-left py-4 uppercase">Nos Chiots</h2>
    <div class="flex justify-center">
      <div class="grid grid-cols-1 md:grid-cols-3 md:gap-4 w-full">
        <a :href="dogRace.slug" v-for="(dogRace, index) in sortedDogRaces" :key="index">
          <div class="flex flex-col w-full p-2">
            <div
              class="w-full h-60 md:h-80 bg-center bg-cover rounded-sm mb-2 shadow-lg hover:"
              :style="{
                backgroundImage: getMainPicture(dogRace.pictures)
                  ? `url('${getMainPicture(dogRace.pictures).path}')`
                  : `url('${noImagePath}')`
              }"
            ></div>
            <span class="text-left uppercase text-xl font-semibold">{{ dogRace.name }}</span>
          </div>
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
const props = defineProps(['dogRaces'])

let dogRaces = ref(props.dogRaces);
const noImagePath = 'https://placehold.co/800x800?text=NO+IMAGE';

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
