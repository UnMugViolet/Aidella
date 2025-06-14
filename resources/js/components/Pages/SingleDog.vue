<template>
  <section class="px-4 md:px-8 pt-11 md:pb-11 mt-11 overflow-hidden flex flex-col items-center justify-center">
    <div class="w-full md:w-10/12">
      <h1 class="text-5xl md:text-7xl font-semibold text-left py-4 uppercase">{{ blogPost.dog_race.name }}</h1>
      <div class="w-full flex flex-col md:flex-row items-center justify-between gap-6 mt-2 md:mt-8">
        <Carroussel :pictures="pictures" />
        <div class="w-full md:w-3/12">
          <p class="text-2xl font-semibold text-left w-full">{{ blogPost.title }}</p>
          <p class="text-lg text-left w-full">{{ blogPost.dog_race.description }}</p>
        </div>
      </div>
      <div class="flex items-center justify-center w-full">
        <div v-html="blogPost.content" class="px-4 md:px-8 md:py-8 mt-8 single-wysiwyg-content">
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import Carroussel from '../Carroussel.vue';
const props = defineProps(['initialData'])

const blogPost = ref(window.pageData.blogPost)
const pictures = ref(blogPost.value.pictures);

</script>
