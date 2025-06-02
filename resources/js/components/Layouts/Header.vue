<template>
    <header class="fixed top-0 left-0 w-full text-white p-5 z-50 backdrop-blur-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-white hover:text-gray-400 transition-colors duration-600 ease-in-out">
                <h1 class="text-2xl font-bold">My Application</h1>
            </a>
            <nav class="flex space-x-6">
                <ul v-for="dogRace in dogRaces" :key="dogRace.id" class="flex space-x-4">
                    <li>
                        <a :href="dogRace.slug" class="hover:underline text-shadow-custom transition-all duration-400 ease-out">
                            {{ dogRace.name }}
                        </a>
                    </li>
                </ul>
                <ul class="flex space-x-4">
                    <li><a href="/a-propos" class="hover:underline text-shadow-custom transition-all duration-400 ease-out">A propos</a></li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script setup>
import { computed } from 'vue';
const props = defineProps(['dogRaces'])

const sortedDogRaces = computed(() =>
  [...dogRaces.value].sort((a, b) => (a.order ?? 0) - (b.order ?? 0))
);

</script>

<style scoped>
.text-shadow-custom {
  text-shadow: 0 2px 8px rgba(0,0,0,1);
}
</style>
