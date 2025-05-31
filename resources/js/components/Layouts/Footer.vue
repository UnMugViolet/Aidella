<template>
  <footer class="bg-gray-800 text-white p-4">
    <p>&copy; {{ year }} Aidella. Tous droits réservés.</p>
    <p>
      <a href="/mentions-legales" class="text-blue-400 hover:underline">Mentions légales</a> |
      <a href="/politique-de-confidentialite" class="text-blue-400 hover:underline">Politique de confidentialité</a>
    </p>
  </footer>
</template>

<script setup>
let year = new Date().getFullYear();

</script>
