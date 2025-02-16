<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';

// defineProps({ contractions: Object });
const props = defineProps({ contractions: Object, required: true });
const contractions = computed(() => props.contractions || {});


const timeSinceLast = ref("00:00");
let sortOrder = ref('desc');

// Funkcia na prepínanie poradia
const toggleSortOrder = () => {
    // Ak sortOrder nie je nastavený, nastavíme ho na 'desc'
    if (!sortOrder.value) {
        sortOrder.value = 'desc';
    } else {
        // Prepneme medzi 'desc' a 'asc'
        sortOrder.value = sortOrder.value === 'desc' ? 'asc' : 'desc';
    }
};

// Po zmene sortOrder, aktualizujeme URL parametre
watch(sortOrder, (newSortOrder) => {
    console.log(newSortOrder)
    router.replace({ query: { sortOrder: newSortOrder } });
    router.get('/', { sortOrder: newSortOrder });
});

const startContraction = () => {
    router.post('/contractions/start', {});
};

const endContraction = () => {
    router.patch('/contractions/end', {});
};

const deleteContraction = (id) => {
    if (window.confirm("Naozaj chcete zmazať tento záznam?")) {
        router.delete(`/contractions/${id}`);
    }
};

const tableToggle = (date) => {
    const table = document.getElementById(`table-${date}`);
    table.classList.toggle('hidden');
};

const parseStartTime = (dateStr, timeStr) => {
    try {
        const [day, month, year] = dateStr.split(".").map(Number);
        const [hours, minutes] = timeStr.split(":").map(Number);
        return new Date(year, month - 1, day, hours, minutes);
    } catch (e) {
        console.error("Chyba pri parsovaní času:", dateStr, timeStr, e);
        return null;
    }
};

const updateTimeSinceLast = () => {
    if (!props.contractions || Object.keys(props.contractions).length === 0) {
        timeSinceLast.value = "—";
        return;
    }

    const allContractions = Object.values(props.contractions)
        .flatMap(dateGroup => Object.values(dateGroup)) // Extrahujeme objekty kontrakcií
        .filter(c => c?.start_time) // Filtrovanie záznamov bez start_time
        .map(c => ({
            ...c,
            start_time: parseStartTime(c.date, c.start_time)
        }))
        .filter(c => c.start_time); // Znova filtrujeme, aby sme odstránili neplatné dátumy

    if (allContractions.length === 0) {
        timeSinceLast.value = "—";
        return;
    }

    const lastContraction = allContractions
        .sort((a, b) => b.start_time - a.start_time)[0];

    if (!lastContraction || !lastContraction.start_time) {
        timeSinceLast.value = "—";
        return;
    }

    const lastTime = lastContraction.start_time;
    const now = new Date();
    const diff = Math.floor((now - lastTime) / 1000);

    if (diff < 0) {
        timeSinceLast.value = "—";
        return;
    }

    const hours = Math.floor(diff / 3600);
    const minutes = Math.floor((diff % 3600) / 60);
    const seconds = diff % 60;

    timeSinceLast.value =
        hours > 0 ? `${hours}:${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}` :
            `${minutes}:${String(seconds).padStart(2, "0")}`;
};


let interval;
onMounted(() => {
    const firstTable = document.querySelector('.table-list');
    if (firstTable) {
        firstTable.classList.remove('hidden');
    }

    updateTimeSinceLast();
    interval = setInterval(updateTimeSinceLast, 1000);
});

onUnmounted(() => {
    clearInterval(interval);
});
</script>

<style>
body {
    background-color: #000000;
    color: #ffffff;
}
</style>

<template>
    <div class="max-w-2xl mx-auto m-3 mb-5 pb-5">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-extrabold text-white m-4">Kontrakcie</h1>

            <Link href="/contractions/create" class="p-4 me-4 text-white font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                    <path d="M12 8V16M16 12L8 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12Z" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </Link>
        </div>

        <div class="text-center mt-4">
            <p class="text-lg font-bold text-gray-300">
                Od poslednej kontrakcie: <span class="text-green-400">{{ timeSinceLast }}</span>
            </p>
        </div>

        <div class="flex gap-4 m-4 justify-center">
            <button @click="startContraction" class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600">
                Začiatok
            </button>
            <button @click="endContraction" class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600">
                Koniec
            </button>

<!--            <button-->
<!--                @click="toggleSortOrder"-->
<!--                class="px-4 py-2 text-white font-semibold">-->
<!--                {{ sortOrder === 'desc' ? 'Najnovšie' : 'Najstaršie' }}-->
<!--            </button>-->
        </div>

        <div v-for="(list, date) in contractions" :key="date" class="m-4">
            <div class="flex">
                <h2 @click="tableToggle(date)" class="text-xl font-extrabold text-white m-2">
                    {{ date }}
<!--                    <span class="bg-gray-400 text-black text-sm font-semibold px-2 ms-2 rounded-full">-->
<!--                        {{ list.length || 0 }}&times;-->
<!--                    </span>-->
                </h2>
            </div>

            <table class="w-full text-white table-list hidden" :id="`table-${date}`">
                <tbody>
                    <tr v-for="contraction in list" :key="contraction.id">
                        <td class="border border-gray-800 p-2 text-left">
                            <p>
                                <strong class="text-green-500">{{ contraction.start_time }}</strong>
                                <span v-if="contraction.end_time">
                                    — <strong>{{ contraction.end_time }}</strong>
                                </span>
                            </p>
                            <small>Trvanie: <strong>{{ contraction.duration ? contraction.duration : '—' }}</strong></small><br>
                            <small>Od poslednej: <strong>{{ contraction.since_last ? contraction.since_last : '—' }}</strong></small>
                        </td>
                        <td class="border border-gray-800 p-2 text-center">
                            <button @click="deleteContraction(contraction.id)" class="px-2 py-.5 text-white rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                                    <path d="M19.5 5.5L18.8803 15.5251C18.7219 18.0864 18.6428 19.3671 18.0008 20.2879C17.6833 20.7431 17.2747 21.1273 16.8007 21.416C15.8421 22 14.559 22 11.9927 22C9.42312 22 8.1383 22 7.17905 21.4149C6.7048 21.1257 6.296 20.7408 5.97868 20.2848C5.33688 19.3626 5.25945 18.0801 5.10461 15.5152L4.5 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M3 5.5H21M16.0557 5.5L15.3731 4.09173C14.9196 3.15626 14.6928 2.68852 14.3017 2.39681C14.215 2.3321 14.1231 2.27454 14.027 2.2247C13.5939 2 13.0741 2 12.0345 2C10.9688 2 10.436 2 9.99568 2.23412C9.8981 2.28601 9.80498 2.3459 9.71729 2.41317C9.32164 2.7167 9.10063 3.20155 8.65861 4.17126L8.05292 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M9.5 16.5L9.5 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M14.5 16.5L14.5 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
