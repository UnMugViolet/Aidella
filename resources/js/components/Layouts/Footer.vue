<template>
  <footer class="bg-gray-800 text-white p-4">
    <p>&copy; {{ year }} Aidella. Tous droits réservés.</p>
  </footer>
</template>

<script setup>
let year = new Date().getFullYear();

</script>
