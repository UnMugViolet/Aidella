<template>
  <div>
    <h1 class="text-5xl font-semibold p-2">Accueil Aidella</h1>
    <div class="flex justify-center">
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full max-w-6xl p-2">
        <a :href="'race/' + dogRace.slug" v-for="(dogRace, index) in sortedDogRaces" :key="index">
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
const noImagePath = '/img/no-image.jpg';

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
