<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { DateTime } from 'luxon';  // Import luxon

const formatLocalDateTime = () => {
    const now = DateTime.local().setZone('Europe/Bratislava');
    return now.toISO({ suppressMilliseconds: true }).slice(0, 16); // Formát pre datetime-local
};

const startTime = ref(formatLocalDateTime());
const endTime = ref(formatLocalDateTime());

// Sledujeme zmenu startTime a aktualizujeme endTime
// watch(startTime, (newStartTime) => {
//     if (newStartTime) {
//         const startDate = DateTime.fromISO(newStartTime, { zone: 'Europe/Bratislava' });
//         const updatedEndTime = startDate.plus({ minutes: 1 });
//         endTime.value = updatedEndTime.toISO({ suppressMilliseconds: true }).slice(0, 16); // Formát pre datetime-local
//     }
// });

watch(startTime, (newStartTime) => {
    if (newStartTime) {
        const startDate = DateTime.fromISO(newStartTime, { zone: 'Europe/Bratislava' });
        const updatedEndTime = startDate.plus({ minutes: 1 });
        endTime.value = updatedEndTime.toISO({ suppressMilliseconds: true }).slice(0, 16); // Formát pre datetime-local
    }
});


const saveContraction = () => {
    router.post('/contractions', {
        start_time: startTime.value,
        end_time: endTime.value,
    });
};
</script>

<style>
body {
    background-color: #000000;
    color: #ffffff;
}

input[type="datetime-local"] {
    width: 100%;
    padding: 0.5rem;
    font-size: 1rem;
    height: 3rem;
    line-height: 3rem;
    box-sizing: border-box;
}

</style>

<template>
    <div class="max-w-2xl mx-auto m-3">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-extrabold text-white m-4">Pridať kontrakciu</h1>

            <Link href="/" class="p-4 me-4 text-white font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                    <path d="M14.9994 15L9 9M9.00064 15L15 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12Z" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </Link>
        </div>

        <div class="m-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-200">Začiatok kontrakcie</label>
                <input type="datetime-local" v-model="startTime" class="w-full rounded p-2" style="color: #000000;" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-200">Koniec kontrakcie (voliteľné)</label>
                <input type="datetime-local" v-model="endTime" class="w-full rounded p-2" style="color: #000000;" />
            </div>

            <button @click="saveContraction" class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600">
                Pridať
            </button>
        </div>
    </div>
</template>
