<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link :href="'/'">
    <img src="/images/logo/Logo-Vertical.png" alt="logo" class="logo" />
    </Link>
</template>

<style scoped>
.logo {
    width: 50%;
    height: auto;
    margin: 0 auto 5% auto;
}
</style>