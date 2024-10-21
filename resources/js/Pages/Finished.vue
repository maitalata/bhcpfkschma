<script setup>
import { Head, Link, router } from "@inertiajs/vue3";

</script>
<template>
    <div class="p-8 text-center" >
        <h1>Finished</h1>
        <p class="m-8" >You have successfully submitted the data.</p>
        <Link href="/" class="px-4 py-2 m-8 font-bold text-white bg-blue-500 rounded hover:bg-blue-600" >Go back to the home page</Link>
    </div>
</template>