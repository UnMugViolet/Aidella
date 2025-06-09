<template>
	<footer class="w-full text-black p-4">
		<div class="w-full flex flex-col md:flex-row gap-3 md:gap-6 my-20">
			<div class="w-full md:w-1/2 flex flex-col  items-start">
				<h2 class="text-5xl md:text-7xl font-semibold text-left pb-4 uppercase">Nous contacter</h2>
				<p class="pr-2 text-lg md:pr-8">
					Demander un renseignement ou simplement nous poser une question, <br/>
					N'hésitez pas à nous contacter par téléphone ou en utilisant notre formulaire de contact !</p>
			</div>
			<form @submit="submitForm" class="flex flex-col items-center justify-center md:w-1/2 mt-5 md:px-4">
				<div class="w-full flex flex-col md:flex-row md:gap-4 md:mb-2">
					<input v-model="form.nom" type="text" placeholder="Nom"
						class="w-full p-2 border border-gray-300 rounded mb-2" required />
					<input v-model="form.prenom" type="text" placeholder="Prénom"
						class="w-full p-2 border border-gray-300 rounded mb-2" required />
				</div>
				<input v-model="form.email" type="email" placeholder="Email"
					class="w-full p-2 border border-gray-300 rounded mb-2" required />
				<textarea v-model="form.message" placeholder="Votre message" rows="5" cols="50"
					class="w-full p-2 border border-gray-300 rounded mb-2" required />
				<button type="submit" :disabled="loading"
					class="w-full cursor-pointer bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition-colors duration-300">
					{{ loading ? 'Envoi...' : 'Envoyer' }}
				</button>
				<p v-if="success" class="text-green-600 mt-2">{{ responseData.message }}</p>
				<p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
			</form>
		</div>
		<div class="w-full flex flex-col justify-center text-center text-xs md:text-sm">
			<p class="text-center">&copy; {{ year }} Aidella. Tous droits réservés</p>
			<p>
				<a href="/mentions-legales" class="text-blue-400 hover:underline">Mentions légales</a> |
				<a href="/politique-de-confidentialite" class="text-blue-400 hover:underline">Politique de
					confidentialité</a>
			</p>
			<p class="flex gap-1 justify-center mt-5">
				Site web réalisé par
				<a href="https://pauljaguin.com" class="text-blue-400 hover:underline" target="_blank"
					rel="noopener noreferrer">Paul Jaguin</a>
			</p>
		</div>
	</footer>
</template>

<script setup>
import { ref } from 'vue'

let year = new Date().getFullYear();

const form = ref({
	nom: '',
	prenom: '',
	email: '',
	message: ''
});
const loading = ref(false);
const success = ref(false);
const error = ref('');
const responseData = ref({}); 

const submitForm = async (e) => {
    e.preventDefault();
    loading.value = true;
    error.value = '';
    success.value = false;

    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(form.value)
        });

        const data = await response.json();

        if (!response.ok) throw new Error(data.message || 'Erreur lors de l\'envoi du message.');

		responseData.value = data;

        success.value = true;
        form.value = { nom: '', prenom: '', email: '', message: '' };
    } catch (err) {
        error.value = err.message;
    } finally {
        loading.value = false;
    }
};
</script>
