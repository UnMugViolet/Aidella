<template>
  <div>
    <h1>Accueil Aidella</h1>
    <div class="flex justify-center">
    <div v-for="(dogRaces, index) in dogRaces" :key="index">
      <a :href="'race/'+ dogRaces.slug">{{ dogRaces.name }}</a>
        <div class="flex flex-row w-10/12">
          <div v-if="getMainPicture(dogRaces.pictures)">
            <img :src="getMainPicture(dogRaces.pictures).path" :alt="getMainPicture(dogRaces.pictures).alt_text"/>
          </div>
          <div v-else>
            <img :src="noImagePath" alt="No image available"/>
          </div>
        </div>
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
