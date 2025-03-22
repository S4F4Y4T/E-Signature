<script setup>
import { ref } from 'vue';

const currentYear = ref(new Date().getFullYear());
</script>

<template>
  <div class="footer mt-3">
    <span class="footer_logo">
      <span>© {{ currentYear }} E-signature</span>
    </span>
  </div>
</template>

<style scoped lang="scss">
.footer_logo {
  text-align: center;
}
.footer {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
