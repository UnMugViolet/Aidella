<template>
    <section class="px-4 md:px-8 md:py-8 mt-20 md:mt-18">
        <div class="w-full flex flex-col md:flex-row justify-between items-center mb-10">
            <div>
                <h1 class="text-6xl md:text-7xl font-semibold text-left py-4 uppercase">Nos Articles</h1>
                <p>Retrouvez ici tous nos conseils, afin de préparer au mieux l'arrivée de votre compagnon !</p>
            </div>
            <input v-model="search" type="text" placeholder="Rechercher un article..."
                class="p-2 border rounded w-full max-w-md mt-3 md:mt-0" />
        </div>

        <div v-for="(posts, category) in filteredPostsByCategory" :key="category" class="mb-8">
            <h2 class="text-2xl font-bold mb-4">{{ category.name }}</h2>
            <transition-group name="fade" tag="ul"  class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a v-for="post in posts" :key="post.id" class="rounded shadow flex flex-col items-center"
                        :href="`/${post.category.slug}/${post.slug}`">
                        <div class="w-full h-80 md:h-120 bg-center bg-cover rounded-sm " 
							:style="{
								backgroundImage: `url('${post.attachments[0]?.relativeUrl || placeholder }')`
								}"
                            />
                        <div class="w-full px-4 py-2">
                            <div class="text-xl font-semibold mb-1">{{ post.title }}</div>
                            <div class="text-sm text-gray-500 capitalize mb-2">
                                <span v-if="post.dog_race?.name">{{ post.dog_race.name }} - </span>
                                {{ post.category?.name || 'Sans catégorie' }}
                            </div>
                            <p class="text-sm mb-1 italic">{{ post.author.name }} - {{ formatDate(post.published_at) }}
                            </p>
                        </div>
                    </a>
            </transition-group>
            <div v-if="posts.length === 0" class="text-red-600 text-lg md:text-2xl font-semibold italic md:py-28">Aucun
                article trouvé dans cette catégorie ou race de chien.</div>
        </div>

        <!-- Loader at the bottom, centered -->
        <div v-if="loading" class="flex justify-center items-center py-8">
            <!-- Tailwind spinner -->
            <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        </div>
    </section>
</template>
<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';

const props = defineProps(['initialData']);
const placeholder = 'https://placehold.co/250x150';

const posts = ref(props.initialData.blogPosts.data || []);
const currentPage = ref(props.initialData.blogPosts.current_page || 1);
const lastPage = ref(props.initialData.blogPosts.last_page || 1);
const loading = ref(false);
const search = ref('');


// Group posts by category
const postsByCategory = computed(() => {
	const grouped = {};
	posts.value.forEach(post => {
		const cat = post.category || 'Sans catégorie';
		if (!grouped[cat]) grouped[cat] = [];
		grouped[cat].push(post);
	});
	return grouped;
});

// Filtered and grouped posts
const filteredPostsByCategory = computed(() => {
	const result = {};
	Object.entries(postsByCategory.value).forEach(([cat, postsArr]) => {
		const filtered = postsArr.filter(post => {
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

async function fetchMorePosts() {
	if (loading.value || currentPage.value >= lastPage.value) return;
	loading.value = true;
	try {
		const res = await fetch(`/articles?page=${currentPage.value + 1}`, {
			headers: { 'X-Requested-With': 'XMLHttpRequest' }
		});
		const data = await res.json();
		posts.value.push(...data.data);
		currentPage.value = data.current_page;
		lastPage.value = data.last_page;
	} finally {
		loading.value = false;
	}
}

// Infinite scroll
function handleScroll() {
	const scrollY = window.scrollY || window.pageYOffset;
	const visible = window.innerHeight;
	const pageHeight = document.documentElement.scrollHeight;
	if (scrollY + visible + 200 >= pageHeight) {
		fetchMorePosts();
	}
}

onMounted(() => {
	window.addEventListener('scroll', handleScroll);
});
onUnmounted(() => {
	window.removeEventListener('scroll', handleScroll);
});
</script>


<style scoped>
.fade-enter-active,
.fade-leave-active {
	transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
	opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
	opacity: 1;
}
</style>
