<template>
  <section class="px-4 md:px-8 py-11 mt-11">
    <h1 class="text-5xl md:text-7xl font-semibold text-left py-4 uppercase">{{ blogPost.dog_race.name }}</h1>
    <p class="text-lg">{{  blogPost.dog_race.description }}</p>

    <div class="w-full md:w-3/4 mx-auto mt-8">
      <div class="wf-1/2 flex flex-col md:flex-row items-center justify-between gap-4">
        <img :src="blogPost.pictures" :alt="'Chiot '+ blogPost.dog_race.name " class="w-full h-auto rounded-lg shadow-lg">
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const blogPost = ref(window.pageData.blogPost)
const dogRaces = ref(window.pageData.dogRaces)

console.log('Dogs', dogRaces.value)

console.log('DogPages', window.pageData.dogPages)
console.log('BlogPost', window.pageData.blogPost)
</script>
