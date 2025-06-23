<template>
  <div class="px-4 md:px-8 pt-11 md:pb-11 mt-11 overflow-hidden flex flex-col items-center justify-center">
    <h1 class="text-5xl md:text-7xl font-semibold text-left py-4 uppercase w-full">A propos</h1>
    <section class="flex md:flex-row flex-col w-full gap-4 md:gap-15 md:items-center mt-2 md:mt-10">
      <img src="/public/images/place/elevage-canin-grilles.webp" alt="a propos de notre elevage"
        class="w-full md:w-1/2 h-auto rounded-lg shadow-lg mb-4">
      <div class="w-full md:w-1/2 h-auto flex items-center justify-center">
        <div class="flex flex-col items-start gap-4 w-full">
          <h2 class="text-3xl md:text-5xl font-semibold text-left py-4">Un seul mot d’ordre : <br/>la qualité</h2>
          <p class="text-lg md:text-xl">
            Les Terres d’Aidella est un élevage canin spécialisé dans les chiens de race LOF situé en plein cœur des Vosges près d’Epinal.
          </p>
          <p class="text-lg md:text-xl">
            Passionné et rigoureux, nous sélectionnons des chiens de qualité, issus de lignées pures et soumises à des tests de santé complets. <br/>
            Nos chiots grandissent dans un environnement stable, garantissant des compagnons équilibrés et en parfaite santé.
          </p>
          <a href="/" class="text-lg md:text-xl text-blue-400 hover:text-blue-500 hover:underline">
            Découvrir nos races disponibles
          </a>
        </div>
      </div>
    </section>
    <section class="flex md:flex-row flex-col-reverse w-full gap-4 md:gap-15 md:items-center mt-10 md:mt-30">
      <div class="w-full md:w-1/2 h-auto flex items-center justify-center">
        <div class="flex flex-col items-start gap-4 w-full">
          <h2 class="text-3xl md:text-5xl font-semibold text-left py-4">Une question ?<br/>Toujours là pour vous conseiller</h2>
          <p class="text-lg md:text-xl">
            N'hésitez pas à nous joindre pour nous parler de votre projet d'adoption et prendre un rendez-vous afin que nous prenions le temps de choisir ensemble votre futur compagnon !
          </p>
          <p class="text-lg md:text-xl">
            Nous sommes à votre disposition pour répondre à toutes vos questions et vous accompagner dans le choix de votre futur chien.
          </p>
          <p class="text-lg md:text-xl">
            Nous recevons uniquement sur rendez-vous du <strong>lundi</strong> au <strong>dimanche</strong>.
          </p>
          <h3 class="text-xl md:text-2xl font-bold mt-2">Contacts</h3>
          <ul class="list-disc pl-6">
            <li class="text-lg md:text-xl">Par téléphone au <a href="tel:+33612345678" class="text-blue-400 hover:text-blue-500 hover:underline">06 69 10 76 61</a></li>
            <li class="text-lg md:text-xl">Via notre <a href="#contact" class="text-blue-400 hover:text-blue-500 hover:underline">formulaire de contact</a></li>
            <li class="text-lg md:text-xl">Sur place au <a href="#find" class="text-blue-400 hover:text-blue-500 hover:underline">29 route de Vaudeville 88600 Aydoilles</a></li>
          </ul>
        </div>
      </div>
      <img src="/public/images/place/elevage-vue-route.webp" alt="a propos de notre elevage"
        class="w-full md:w-1/2 h-auto rounded-lg shadow-lg mb-4">
    </section>
    
    <h2 id="find" class="text-5xl md:text-7xl font-semibold w-full text-right py-4 uppercase mt-4 md:mt-20">Nous trouver</h2>
    <div class="w-full mt-2 md:mt-10">
      <GoogleMap :api-key="apiKey" :map-id="mapID" style="width: 100%; height: 50svh;" :center="companyCoords"
        :zoom="12">
        <AdvancedMarker :options="markerOptions" />
      </GoogleMap>
    </div>
  </div>
</template>


<script setup>
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'

const companyCoords = { lat: 48.2126579284668, lng: 6.561935901641846 }
const apiKey = import.meta.env.VITE_APP_GOOGLE_MAPS_API_KEY
const mapID = import.meta.env.VITE_APP_AIDELLA_GOOGLE_MAPS_MAP_ID
const markerOptions = { position: companyCoords, title: 'Les terres d\'Aidella' }

</script>

