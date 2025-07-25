<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import { DateTime } from "luxon";

const formatLocalDateTime = () => {
    const now = DateTime.local().setZone('Europe/Bratislava');
    return now.toISO({ suppressMilliseconds: true }).slice(0, 16);
};

const form = useForm({
    type: null,
    date: ref(formatLocalDateTime()),
    data_type: null,
    data_side: null,
    data_amount: null,
    data_value: null,
});

const submit = () => {
    form.post('/baby-activities', {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<style>
body {
    background-color: #000000;
    color: #ffffff;
}

input, select {
    color: black;
}
</style>

<template>
    <div class="max-w-2xl mx-auto m-3">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-extrabold text-white m-4">Pridať aktivitu</h1>

            <Link href="/" class="p-4 me-4 text-white font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                    <path d="M14.9994 15L9 9M9.00064 15L15 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12Z" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </Link>
        </div>

        <div class="m-4">
            <label class="block">Typ aktivity</label>
            <select v-model="form.type" class="w-full p-2 border rounded mt-2 text-black">
                <option value="" disabled selected>Vyberte...</option>
                <option value="sleep">🌜 Spánok</option>
                <option value="wakeup">🌞 Prebudenie</option>
                <option value="feeding">🍼 Kŕmenie</option>
                <option value="diaper_change">💩 Prebaľovanie</option>
                <option value="bathing">🛀 Kúpanie</option>
                <option value="medicament">💊 Lieky</option>
                <option value="vaccination">💉 Očkovanie</option>
                <option value="tooth">🦷 Zuby</option>
                <option value="temperature">🌡️ Teplota</option>
                <option value="height">📏 Výška</option>
                <option value="weight">⚖️ Váha</option>
            </select>

            <div class="mt-4">
                <label class="block">Dátum</label>
                <input type="datetime-local" v-model="form.date" class="w-full p-2 border rounded text-black" />
            </div>

            <div v-if="form.type === 'feeding'" class="mt-4">
                <label class="block">Typ kŕmenia</label>
                <select v-model="form.data_type" class="w-full p-2 border rounded text-black">
                    <option value="" disabled selected>Vyberte...</option>
                    <option value="breast">Dojčenie</option>
                    <option value="formula">Umelé mlieko</option>
                    <option value="infant_food">Príkrm</option>
                    <option value="food">Jedlo</option>
                </select>

                <div v-if="form.data_type === 'breast'" class="mt-4">
                    <label class="block">Strana prsníka</label>
                    <select v-model="form.data_side" class="w-full p-2 border rounded text-black">
                        <option value="" disabled selected>Vyberte...</option>
                        <option value="left">Ľavý</option>
                        <option value="right">Pravý</option>
                    </select>
                </div>

                <div v-if="form.data_type === 'formula'" class="mt-4">
                    <label class="block">Množstvo (ml)</label>
                    <input v-model="form.data_amount" type="number" class="w-full p-2 border rounded text-black" min="0" />
                </div>

                <div v-if="form.data_type === 'infant_food' || form.data_type === 'food'" class="mt-4">
                    <label class="block">Popis</label>
                    <textarea v-model="form.data_value" class="w-full p-2 border rounded text-black" />
                </div>
            </div>

            <div v-if="form.type === 'diaper_change'" class="mt-4">
                <label class="block">Typ prebaľovania</label>
                <select v-model="form.data_type" class="w-full p-2 border rounded text-black">
                    <option value="" disabled selected>Vyberte...</option>
                    <option value="wet">Moč</option>
                    <option value="dirty">Stolica</option>
                    <option value="both">Oboje</option>
                </select>
            </div>

            <div v-if="form.type === 'medicament'" class="mt-4">
                <label class="block">Typ lieku</label>
                <input v-model="form.data_type" class="w-full p-2 border rounded text-black" />

                <div class="mt-4">
                    <label class="block">Dávka</label>
                    <input v-model="form.data_amount" class="w-full p-2 border rounded text-black" />
                </div>
            </div>

            <div v-if="form.type === 'vaccination'" class="mt-4">
                <label class="block">Typ očkovania</label>
                <input v-model="form.data_type" class="w-full p-2 border rounded text-black" />
            </div>

            <div v-if="form.type === 'tooth'" class="mt-4">
                <label class="block">Popis</label>
                <textarea v-model="form.data_value" class="w-full p-2 border rounded text-black" />
            </div>

            <div v-if="form.type === 'temperature'" class="mt-4">
                <label class="block">Teplota v °C</label>
                <input v-model="form.data_value" class="w-full p-2 border rounded text-black" />
            </div>

            <div v-if="form.type === 'weight'" class="mt-4">
                <label class="block">Váha v Kg</label>
                <input v-model="form.data_value" class="w-full p-2 border rounded text-black" />
            </div>

            <div v-if="form.type === 'height'" class="mt-4">
                <label class="block">Výška v cm</label>
                <input v-model="form.data_value" class="w-full p-2 border rounded text-black" />
            </div>

            <button @click="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full mt-4">
                Uložiť
            </button>
        </div>
    </div>
</template>
