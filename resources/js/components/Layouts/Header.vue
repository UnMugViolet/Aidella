<template>
	<header class="bg-gray-800 text-white p-4">
		<div class="container mx-auto flex justify-between items-center">
			<a href="/" class="text-white hover:text-gray-400">
				<h1 class="text-2xl font-bold">My Application</h1>
			</a>
			<nav class="flex space-x-6">
				<ul v-for="dogRace in dogRaces" :key="dogRace.id" class="flex space-x-4">
					<li>
						<a :href="'/race/' + dogRace.slug" class="hover:text-gray-400">
							{{ dogRace.name }}
						</a>
					</li>
				</ul>
				<ul class="flex space-x-4">
					<li><a href="/a-propos" class="hover:text-gray-400">A propos</a></li>
				</ul>
			</nav>
		</div>
	</header>
</template>

<script setup>
import { ref, computed } from 'vue';
const props = defineProps(['dogRaces'])

const sortedDogRaces = computed(() =>
  [...dogRaces.value].sort((a, b) => (a.order ?? 0) - (b.order ?? 0))
);

</script>
