<template>
  <div>
    <h1 class="text-5xl font-semibold p-2">Accueil Aidella</h1>
    <div class="flex justify-center">
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full max-w-6xl p-2">
        <a :href="'race/' + dogRaces.slug" v-for="(dogRaces, index) in dogRaces" :key="index">
          <div class="flex flex-col w-full p-2">
            <div
              class="w-full h-80 bg-center bg-cover rounded-xs mb-2"
              :style="{
                backgroundImage: getMainPicture(dogRaces.pictures)
                  ? `url('${getMainPicture(dogRaces.pictures).path}')`
                  : `url('${noImagePath}')`
              }"
            ></div>
            <span class="text-center font-semibold">{{ dogRaces.name }}</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const props = defineProps(['initialData'])

let dogRaces = ref(props.initialData.dogRaces);
const noImagePath = '/img/no-image.jpg';

console.log(dogRaces.value);

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
