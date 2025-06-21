<template>
  <div>
    <div class="relative w-full ">
      <video autoplay muted loop class="w-full [height:93svh] object-cover">
        <source :src="videoPath" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="absolute inset-0 bg-black opacity-50"></div>
      <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white ">
        <h1 class="text-6xl md:text-8xl font-bold mb-2">Les Terres d'Aidella</h1>
        <p class="p-2 text-base md:text-xl">Élevage de chiens LOF à Aydoilles dans les Vosges.</p>
      </div>
    </div>
    <DogRaceGrid :dogPages="dogPages"/>
    <section class="px-4 md:px-8 md:py-8 mt-10">
      <h2 class="text-6xl md:text-7xl font-semibold text-right py-4 uppercase">Qui sommes nous ?</h2>
      <div class="flex flex-col md:flex-row gap-4 md:items-center mt-2">
        <img src="https://placehold.co/600x400" alt="a propos de notre elevage" class="w-full md:w-1/2 h-auto rounded-lg shadow-lg mb-4">
        <p class="w-full md:w-1/2 text-lg h-auto md:text-xl">
          Situé dans les Vosges, près d'Epinal, notre élevage canin est spécialisé dans les chiens de race LOF Berger Australien, Labrador Retriver, Bouledogue Français, Cavalier King Charles Coton de Tulear et Spitz Nain. <br/> <br/>
          Nous sommes passionnés par les chiens et nous nous engageons à offrir un environnement sain et aimant pour nos animaux. Notre élevage est reconnu pour sa qualité et son sérieux, avec des chiots élevés dans le respect des standards de la race. <br/>
          Nous mettons un point d'honneur à la socialisation, la santé et le bien-être de nos chiots, garantissant des compagnons fidèles et équilibrés. <br/> <br/>
          Venez nous rencontrer ! <br/>
          <a href="/a-propos" class="text-blue-400 hover:text-blue-500 hover:underline">En savoir plus sur notre élevage</a>
        </p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import DogRaceGrid from '../Homepage/DogRaceGrid.vue';

const props = defineProps(['initialData'])
const videoPath = '/videos/les-terres-daidella-elevage-canin-grand-est.mp4';

let dogPages = ref(props.initialData.dogPages);

</script>
