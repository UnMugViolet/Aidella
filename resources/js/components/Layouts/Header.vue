<template>
    <header class="fixed top-0 left-0 w-full text-white p-5 z-50 backdrop-blur-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-white hover:text-gray-300 transition-colors duration-600 ease-in-out flex items-center gap-2">
                <img :src="logoPath" alt="Logo Aidella" class="h-14 w-auto">
                <h1 class="text-2xl font-bold text-shadow-custom">Terres d'Aidella</h1>
            </a>
            <!-- Burger Button -->
            <button
                class="md:hidden flex flex-col justify-center items-center w-10 h-10 focus:outline-none z-50"
                @click="burgerOpen = !burgerOpen"
                aria-label="Menu"
            >
                <span :class="['block w-7 h-1 bg-white rounded burger-shadow transition-all duration-300', burgerOpen ? 'rotate-45 translate-y-2' : '']"></span>
                <span :class="['block w-7 h-1 bg-white rounded burger-shadow my-1 transition-all duration-300', burgerOpen ? 'opacity-0' : '']"></span>
                <span :class="['block w-7 h-1 bg-white rounded burger-shadow transition-all duration-300', burgerOpen ? '-rotate-45 -translate-y-2' : '']"></span>
            </button>
            <!-- Desktop Nav -->
            <nav 
                class="hidden md:flex space-x-4"
                aria-label="Menu Header Desktop"
            >
                <div class="relative" @mouseenter="dropDownOpen = true" @mouseleave="dropDownOpen = false">
                    <button class="flex items-center gap-1 focus:outline-none">
                        <span class="hover:underline text-shadow-custom font-medium transition-all duration-400 ease-out">Nos Chiots</span>
                        <svg :class="['transition-transform duration-300', dropDownOpen ? 'rotate-180' : 'rotate-0']" width="16" height="16" fill="none" viewBox="0 0 24 24">
                            <path d="M8 10l4 4 4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <ul v-if="dropDownOpen" class="absolute left-0 text-black rounded shadow-lg min-w-max z-50">
                        <div class="bg-white rounded mt-2">
                            <li v-for="dogRace in sortedDogRaces" :key="dogRace.id">
                                <a :href="dogRace.slug" class="block px-4 py-2 rounded hover:bg-gray-100">{{ dogRace.name }}</a>
                            </li>
                        </div>
                    </ul>
                </div>
                <ul class="flex space-x-6">
                    <li>
                        <a href="/a-propos" class="hover:underline text-shadow-custom font-medium transition-all duration-400 ease-out">A propos</a>
                    </li>
                    <li class="flex items-center gap-1 hover:text-gray-100 transition-colors duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" style="filter: drop-shadow(0 2px 8px rgba(0,0,0,1));">
                            <path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24c1.12.37 2.33.57 3.57.57c.55 0 1 .45 1 1V20c0 .55-.45 1-1 1c-9.39 0-17-7.61-17-17c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1c0 1.25.2 2.45.57 3.57c.11.35.03.74-.25 1.02z"/>
                        </svg>
                        <a href="tel:+33669107661" class="hover:underline text-shadow-custom font-medium transition-all duration-400 ease-out">06 69 10 76 61</a>
                    </li>
                </ul>
            </nav>
            <!-- Mobile Nav -->
            <transition name="slide" mode="out-in">
                <nav
                    v-if="burgerOpen"
                    class="fixed inset-0 bg-black bg-opacity-90 flex flex-col items-center justify-center space-y-6 md:hidden h-svh z-40"
                    aria-label="Mobile Menu"
                >
                    <div class="relative w-full flex flex-col justify-center px-4">
                        <span class="text-2xl font-semibold mb-3">Nos Chiots</span>
                        <ul class="rounded shadow-lg min-w-max truncate text-white">
                            <li v-for="dogRace in sortedDogRaces" :key="dogRace.id" class="py-1.5 ml-4 underline">
                                <a :href="dogRace.slug" >{{ dogRace.name }}</a>
                            </li>
                        </ul>
                    </div>
                    <ul class="flex flex-col w-full items-left jusify-between">
                        <li>
                            <a href="/a-propos" class="text-2xl hover:underline text-shadow-custom font-medium transition-all duration-400 ease-out px-4">A propos</a>
                        </li>
                        <li class="absolute bottom-0 py-2 flex justify-center w-full gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-auto" style="filter: drop-shadow(0 2px 8px rgba(0,0,0,1));">
                                <path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24c1.12.37 2.33.57 3.57.57c.55 0 1 .45 1 1V20c0 .55-.45 1-1 1c-9.39 0-17-7.61-17-17c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1c0 1.25.2 2.45.57 3.57c.11.35.03.74-.25 1.02z"/>
                            </svg>
                            <a href="tel:+33669107661" class="text-xl hover:underline text-shadow-custom font-medium transition-all duration-400 ease-out">06 69 10 76 61</a>
                        </li>
                    </ul>
                </nav>
            </transition>
        </div>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue';
const props = defineProps(['dogRaces'])
const dropDownOpen = ref(false);
const burgerOpen = ref(false);
const mobileDropDownOpen = ref(false);

const logoPath = '/images/logo-aidella.webp'; 

const sortedDogRaces = computed(() =>
  [...props.dogRaces].sort((a, b) => (a.order ?? 0) - (b.order ?? 0))
);
</script>

<style scoped>
.text-shadow-custom {
  text-shadow: 0 2px 8px rgba(0,0,0,1);
}

.burger-shadow {
  box-shadow: 0 2px 8px rgba(0,0,0,0.6);
}

.slide-enter-active, .slide-leave-active {
  transition: all 0.3s;
}
.slide-enter-from, .slide-leave-to {
    transform: translateX(100svw);
}
</style>
