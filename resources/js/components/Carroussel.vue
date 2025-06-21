<template>
    <div class="w-full md:w-9/12 flex flex-col items-center">
        <div class="relative w-full">
            <div v-if="pictures.length > 0" class="w-full [height:58svh] rounded-lg shadow-lg" :style="{
                backgroundImage: pictures[currentIndex].relativeUrl
                    ? `url('${pictures[currentIndex].relativeUrl}')`
                    : `url('${pictures[currentIndex].placeholdUrl}')`,
                backgroundSize: 'cover',
                backgroundPosition: 'center'
            }">
                <button v-if="pictures.length > 1" @click="prevImage"
                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-white bg-opacity-70 rounded-full p-2 shadow cursor-pointer"
                    aria-label="Previous image">
                    ‹
                </button>
                <button v-if="pictures.length > 1" @click="nextImage"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-white bg-opacity-70 rounded-full p-2 shadow cursor-pointer"
                    aria-label="Next image">
                    ›
                </button>
            </div>
            <div v-if="pictures.length > 1" class="flex gap-2 mt-2 justify-center">
                <span v-for="(pic, idx) in pictures" :key="idx" class="w-3 h-3 rounded-full"
                    :class="currentIndex === idx ? 'bg-blue-600' : 'bg-gray-300'"></span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps(['pictures'])

const pictures = computed(() => {
    return props.pictures && props.pictures.length > 0
        ? props.pictures
        : [{ placeholdUrl: 'https://placehold.co/450x300' }]
})

const currentIndex = ref(0)

function prevImage() {
    currentIndex.value =
        (currentIndex.value - 1 + pictures.value.length) % pictures.value.length
}

function nextImage() {
    currentIndex.value = (currentIndex.value + 1) % pictures.value.length
}

</script>
