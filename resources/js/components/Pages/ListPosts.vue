<template>
	<section class="px-4 md:px-8 md:py-8 mt-20 md:mt-18">
		<div class="w-full flex flex-col md:flex-row justify-between items-center mb-10">
			<div>
				<h1 class="text-6xl md:text-7xl font-semibold text-left py-4 uppercase">Nos Articles</h1>
				<p>Retrouvez ici tous nos conseils, afin de préparer au mieux l'arrivée de votre compagnon !</p>
			</div>
			<input v-model="search" type="text" placeholder="Rechercher un article..."
				class="p-2 border rounded w-full max-w-md mt-3 md:mt-0" 
			/>
		</div>

		<div v-for="(posts, category) in filteredPostsByCategory" :key="category" class="mb-8">
			<h2 class="text-2xl font-bold mb-4">{{ category.name }}</h2>
			<ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<a v-for="post in posts" 
					:key="post.id" 
					class="rounded shadow flex flex-col items-center"
					:href="`/${post.category.slug}/${post.slug}`">
					<img :src="post.pictures[0]?.path || placeholder" :alt="post.title"
						class="w-full h-auto object-cover mb-2 rounded" />
					<div class="w-full px-4 py-2">
						<div class="text-xl font-semibold mb-1">{{ post.title }}</div>
						<div class="text-sm text-gray-500 capitalize mb-2">
							<span v-if="post.dog_race?.name">{{ post.dog_race.name }} - </span>
							{{ post.category?.name || 'Sans catégorie' }}
						</div>
						<p class="text-sm mb-1 italic">{{ post.author.name }} - {{ formatDate(post.published_at) }}</p>
					</div>
				</a>
			</ul>
			<div v-if="posts.length === 0" class="text-red-600 text-lg md:text-2xl font-semibold italic md:py-28">Aucun article trouvé dans cette catégorie ou race de chien.</div>
		</div>
	</section>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps(['initialData', 'blogPosts']);

const placeholder = 'https://placehold.co/250x150';
const allPosts = props.initialData.blogPosts?.data || [];
const search = ref('');

console.log('All posts', allPosts)
// Group posts by category
const postsByCategory = computed(() => {
	const grouped = {};
	allPosts.forEach(post => {
		const cat = post.category || 'Sans catégorie';
		if (!grouped[cat]) grouped[cat] = [];
		grouped[cat].push(post);
	});
	return grouped;
});

// Filtered and grouped posts
const filteredPostsByCategory = computed(() => {
    const result = {};
    Object.entries(postsByCategory.value).forEach(([cat, posts]) => {
        const filtered = posts.filter(post => {
            const titleMatch = post.title.toLowerCase().includes(search.value.toLowerCase());
            const categoryName = post.category?.name || post.category || 'Sans catégorie';
			const dogRaceName = post.dog_race?.name || '';
            const categoryMatch = categoryName.toLowerCase().includes(search.value.toLowerCase());
			const dogRaceMatch = dogRaceName.toLowerCase().includes(search.value.toLowerCase());
            return titleMatch || categoryMatch || dogRaceMatch;
        });
        result[cat] = filtered;
    });
    return result;
});

function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: '2-digit',
        day: 'numeric'
		
    });
}
</script>
