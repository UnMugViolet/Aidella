<template>
  <section class="px-4 md:px-8 pt-11 md:pb-11 mt-11 w-full overflow-hidden flex justify-center">
    <div class="w-full md:w-10/12 flex flex-col md:flex-row items-center justify-between gap-6 mt-2 md:mt-8">
      <div class="md:w-1/2">
        <h1 class="text-5xl md:text-7xl font-semibold text-left py-4 uppercase">{{ blogPost.title }}</h1>
        <div class="ml-2 capitalize text-2xl font-semibold text-left w-full flex gap-1.5">
          <h2>
            {{ blogPost.category.name || 'Sans catégorie' }}
          </h2>
          <h2 v-if="blogPost.dog_race?.name"> - {{ blogPost.dog_race.name }}</h2>
        </div>
        <p class="italic text-gray-600 capitalize ml-2">
          {{ blogPost.author.name }} - {{ formatDate(blogPost.published_at) }}
        </p>
      </div>
      <Carroussel :pictures="attachments" class="w-1/2 "/>
    </div>
  </section>
  <section class="flex items-center justify-center">
    <div v-html="blogPost.content"
      class="px-4 md:px-8 md:py-8 mt-8 md:w-10/12 single-wysiwyg-content">
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import Carroussel from '../Carroussel.vue';
const props = defineProps(['initialData'])

const blogPost = ref(window.pageData.blogPost)
const attachments = ref(blogPost.value.attachments);

function formatDate(dateStr) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long'
  });
}

</script>
