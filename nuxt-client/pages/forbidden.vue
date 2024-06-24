<template>
    <div class="flex flex-col gap-4 items-center">
      <h1 class="text-4xl font-bold">403 - Forbidden</h1>
      <p class="text-xl">You do not have permission to access this page.</p>
      <p>
        Go back
        <NuxtLink to="/" class="nav-link underline">here</NuxtLink>
      </p>
    </div>
  </template>

<script setup>
    definePageMeta({
        title: 'Forbidden',
        layout: ''
    })
</script>