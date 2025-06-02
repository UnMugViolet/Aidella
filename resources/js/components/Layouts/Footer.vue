<template>
  <footer class="w-full text-black p-4">
    <div class="w-full flex flex-col md:flex-row gap-3 md:gap-6 my-20">
      <div class="w-full md:w-1/2 flex flex-col  items-start">
        <h2 class="text-5xl md:text-7xl font-semibold text-left pb-4 uppercase">Nous contacter</h2>
        <p class="pr-2 md:pr-8">Demander un renseignement ou simplement nous poser une question n'esithez pas a nous contacter !</p>
      </div>
      <form class="flex flex-col items-center justify-center md:w-1/2 mt-5 md:px-4">
        <div class="w-full flex flex-col md:flex-row md:gap-4 md:mb-2">
          <input
            type="text"
            placeholder="Nom"
            class="w-full p-2 border border-gray-300 rounded mb-2"
          />
          <input
            type="text"
            placeholder="Prénom"
            class="w-full p-2 border border-gray-300 rounded mb-2"
          />
        </div>
        <input
        type="email"
        placeholder="Email"
        class="w-full p-2 border border-gray-300 rounded mb-2"
        />
        <textarea
          placeholder="Votre message"
          rows="5"
          cols="50"
          class="w-full p-2 border border-gray-300 rounded mb-2"
        />
        <button
          type="submit"
          class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition-colors duration-300"
        >
          Envoyer
        </button>

      </form>
    </div>
    <div class="w-full flex flex-col justify-center text-center text-xs md:text-sm">
      <p class="text-center">&copy; {{ year }} Aidella. Tous droits réservés</p>
      <p>
        <a href="/mentions-legales" class="text-blue-400 hover:underline">Mentions légales</a> |
        <a href="/politique-de-confidentialite" class="text-blue-400 hover:underline">Politique de confidentialité</a>
      </p>
      <p class="flex gap-1 justify-center mt-5">
        Site web réalisé par
        <a href="https://pauljaguin.com" class="text-blue-400 hover:underline" target="_blank" rel="noopener noreferrer">Paul Jaguin</a>
      </p>
    </div>
  </footer>
</template>

<script setup>
let year = new Date().getFullYear();

</script>
