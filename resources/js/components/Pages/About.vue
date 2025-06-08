<template>
  <section class="px-4 md:px-8 pt-11 md:pb-11 mt-11">
    <h1 class="text-5xl md:text-7xl font-semibold text-left py-4 uppercase">A propos</h1>
    <h2 class="text-5xl md:text-7xl font-semibold text-right py-4 uppercase">Nous trouver</h2>
    <div class="w-full">
      <GoogleMap 
        :api-key="apiKey" 
        :map-id="mapID"
        style="width: 100%; height: 400px;" 
        :center="companyCoords" 
        :zoom="12">
        <AdvancedMarker :options="markerOptions" />
      </GoogleMap>
      </div>
    </section>
</template>


<script setup>
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'

const companyCoords = { lat: 48.2126579284668, lng: 6.561935901641846 }
const apiKey = import.meta.env.VITE_APP_GOOGLE_MAPS_API_KEY
const mapID = import.meta.env.VITE_APP_AIDELLA_GOOGLE_MAPS_MAP_ID
const markerOptions = { position: companyCoords, title: 'Les terres d\'Aidella' }

</script>
