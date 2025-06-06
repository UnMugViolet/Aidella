<template>
  <section class="px-4 md:px-8 pt-11 md:pb-11 mt-11 w-full overflow-hidden">
    <h1 class="text-5xl md:text-7xl font-semibold text-left py-4 uppercase">{{ blogPost.title }}</h1>
  </section>
</template>

<script setup>
import { ref } from 'vue';
const props = defineProps(['initialData'])

const blogPost = ref(window.pageData.blogPost)
const dogRaces = ref(window.pageData.dogRaces)
const pictures = ref(blogPost.value.pictures);

</script>
