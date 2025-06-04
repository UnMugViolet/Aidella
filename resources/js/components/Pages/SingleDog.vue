<template>
  <section class="px-4 md:px-8 py-11 mt-11">
    <h1 class="text-5xl md:text-7xl font-semibold text-left py-4 uppercase">{{ blogPost.dog_race.name }}</h1>
    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-6 mt-8">
      <div class="w-full md:w-1/2">
        <img :src="blogPost.pictures" :alt="'Chiot '+ blogPost.dog_race.name " class="w-full h-auto rounded-lg shadow-lg">
      </div>
      <div class="w-full md:w-1/2">
        <p class="text-2xl font-semibold text-left w-full">{{ blogPost.title }}</p>
        <p class="text-lg text-left w-full">{{  blogPost.dog_race.description }}</p>
      </div>
    </div>
  </section>
  <section 
    v-html="blogPost.content"
    class="px-4 md:px-8 md:py-8 mt-8 w-full">
  </section>
</template>

<script setup>
import { ref } from 'vue'

const blogPost = ref(window.pageData.blogPost)
const dogRaces = ref(window.pageData.dogRaces)

</script>
