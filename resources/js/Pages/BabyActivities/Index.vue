<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    activities: Array,
    selectedDate: String,
});

const selectedDate = ref(new Date(props.selectedDate));
const activities = ref(props.activities);

function getMonday(date) {
    const d = new Date(date); // zabezpečí, že `date` bude platný dátum
    if (isNaN(d)) {
        console.error("Neplatný dátum:", date);
        return new Date(); // alebo nastavte iný predvolený dátum
    }
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(d.setDate(diff));
}


function formatISODate(date) {
    return date.toISOString().split("T")[0];
}

// Nastavenie aktuálneho týždňa podľa vybraného dátumu
const currentWeekStart = ref(getMonday(selectedDate.value));

// Funkcia na zmenu týždňa
const changeWeek = (offset) => {
    const newDate = new Date(currentWeekStart.value);
    newDate.setDate(newDate.getDate() + offset * 7);
    currentWeekStart.value = newDate;
};

// Výpočet dní v aktuálnom týždni
const weekDays = computed(() => {
    return Array.from({ length: 7 }, (_, i) => {
        const day = new Date(currentWeekStart.value);
        day.setDate(day.getDate() + i);

        return {
            fullDate: formatISODate(day),
            shortDayName: day.toLocaleDateString("sk-SK", { weekday: "short" }),
            day: day.toLocaleDateString("sk-SK", { day: "numeric" }),
            monthShort: day.toLocaleDateString("sk-SK", { month: "short" }),
            // year: day.toLocaleDateString("sk-SK", { year: "numeric" }),
        };
    });
});

// Ak sa vyberie dátum mimo aktuálneho týždňa, presunieme zobrazený týždeň na nový
const selectDate = (date) => {
    selectedDate.value = date;
    currentWeekStart.value = getMonday(date); // Posunieme týždeň podľa zvoleného dátumu
    router.get("/", { date });
};

const deleteActivity = (id) => {
    if (window.confirm("Naozaj chcete zmazať tento záznam?")) {
        router.delete(`/baby-activities/${id}`);
    }
};
</script>


<style>
body {
    background-color: #000000;
    color: #ffffff;
}
</style>

<template>
    <div class="max-w-2xl mx-auto m-3 mb-3 pb-5">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-extrabold text-white m-4">Richard</h1>

            <Link href="/baby-activities/create" class="p-4 me-4 text-white font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                    <path d="M12 8V16M16 12L8 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12Z" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </Link>
        </div>

        <div class="m-3 px-1 py-2 flex items-center justify-between bg-gray-800 text-white rounded-lg shadow">
            <button @click="changeWeek(-1)" class="p-1 text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                    <path d="M15 6C15 6 9.00001 10.4189 9 12C8.99999 13.5812 15 18 15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <div class="flex space-x-1">
                <div
                    v-for="day in weekDays"
                    :key="day.fullDate"
                    class="px-2.5 py-1 rounded cursor-pointer text-center text-xs"
                    :class="day.fullDate === formatISODate(selectedDate) ? 'bg-blue-500' : 'bg-gray-700'"
                    @click="selectDate(day.fullDate)"
                >
                    <small>{{ day.shortDayName }}</small><br>
                    <strong>{{ day.day }}</strong><br>
                    <small>{{ day.monthShort }}</small>
                </div>
            </div>

            <button @click="changeWeek(1)" class="p-1 text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                    <path d="M9.00005 6C9.00005 6 15 10.4189 15 12C15 13.5812 9 18 9 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <div class="m-3 p-2 bg-gray-900 shadow-lg rounded-lg">
            <table class="w-full">
                <tbody>
                    <tr v-for="activity in activities" :key="activity.id">
                        <td class="p-2 border-b border-b-gray-800">
                            <div v-if="activity.type === 'sleep'">🌜</div>
                            <div v-if="activity.type === 'wakeup'">🌞</div>
                            <div v-if="activity.type === 'feeding'">🍼</div>
                            <div v-if="activity.type === 'diaper_change'">💩</div>
                            <div v-if="activity.type === 'bathing'">🛀</div>
                            <div v-if="activity.type === 'medicament'">💊</div>
                            <div v-if="activity.type === 'vaccination'">💊</div>
                            <div v-if="activity.type === 'temperature'">🌡️</div>
                            <div v-if="activity.type === 'tooth'">🦷</div>
                            <div v-if="activity.type === 'height'">📏</div>
                            <div v-if="activity.type === 'weight'">⚖️</div>
                        </td>

                        <td class="p-2 border-b border-b-gray-800">
                            <div v-if="activity.type === 'wakeup'" class="font-bold">Prebudenie</div>
                            <div v-if="activity.type === 'sleep'" class="font-bold">Spánok</div>
                            <div v-if="activity.type === 'feeding'" class="font-bold">Kŕmenie</div>
                            <div v-if="activity.type === 'diaper_change'" class="font-bold">Prebaľovanie</div>
                            <div v-if="activity.type === 'bathing'" class="font-bold">Kúpanie</div>
                            <div v-if="activity.type === 'medicament'" class="font-bold">Lieky</div>
                            <div v-if="activity.type === 'vaccination'" class="font-bold">Očkovanie</div>
                            <div v-if="activity.type === 'tooth'" class="font-bold">Zuby</div>
                            <div v-if="activity.type === 'temperature'" class="font-bold">Teplota: <strong>{{ activity.data.value.replace('.', ',') }} °C</strong></div>
                            <div v-if="activity.type === 'height'" class="font-bold">Výška: <strong>{{ activity.data.value.replace('.', ',') }} cm</strong></div>
                            <div v-if="activity.type === 'weight'" class="font-bold">Váha: <strong>{{ activity.data.value.replace('.', ',') }} Kg</strong></div>

                            <div v-if="activity.type === 'feeding' && activity.data">
                                <small v-if="activity.data.type === 'breast'">Dojčenie</small>
                                <small v-if="activity.data.type === 'breast' && activity.data.side === 'left'">, Ľavý prsník</small>
                                <small v-if="activity.data.type === 'breast' && activity.data.side === 'right'">, Pravý prsník</small>

                                <small v-if="activity.data.type === 'formula'">Umelé mlieko</small>
                                <small v-if="activity.data.type === 'formula' && activity.data.amount">, {{ activity.data.amount + ' ml' }}</small>

                                <div v-if="activity.data.type === 'infant_food' || activity.data.type === 'food'">
                                    <small v-if="activity.data.type === 'infant_food'" class="font-bold">Príkrm: </small>
                                    <small v-if="activity.data.type === 'food'" class="font-bold">Jedlo: </small>
                                    <small>{{ activity.data.value }}</small>
                                </div>
                            </div>

                            <div v-if="activity.type === 'diaper_change' && activity.data">
                                <small v-if="activity.data.type === 'wet' || activity.data.type === 'both'">Moč</small>
                                <small v-if="activity.data.type === 'both'">, </small>
                                <small v-if="activity.data.type === 'dirty' || activity.data.type === 'both'">Stolica</small>
                            </div>

                            <div v-if="activity.type === 'medicament' && activity.data">
                                <small><strong>{{ activity.data.type }}</strong> - {{ activity.data.amount }}</small>
                            </div>

                            <div v-if="activity.type === 'vaccination' && activity.data">
                                <small>{{ activity.data.type }}</small>
                            </div>

                            <div v-if="activity.type === 'tooth' && activity.data">
                                <small>{{ activity.data.value }}</small>
                            </div>
                        </td>

                        <td class="p-2 text-center border-b border-b-gray-800">
                            {{ new Date(activity.date).toLocaleTimeString("sk-SK", { hour: "2-digit", minute: "2-digit" }) }}
                        </td>

                        <td class="p-1 text-center border-b border-b-gray-800">
                            <button @click="deleteActivity(activity.id)" class="px-2 py-.5 text-white rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                                    <path d="M19.5 5.5L18.8803 15.5251C18.7219 18.0864 18.6428 19.3671 18.0008 20.2879C17.6833 20.7431 17.2747 21.1273 16.8007 21.416C15.8421 22 14.559 22 11.9927 22C9.42312 22 8.1383 22 7.17905 21.4149C6.7048 21.1257 6.296 20.7408 5.97868 20.2848C5.33688 19.3626 5.25945 18.0801 5.10461 15.5152L4.5 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M3 5.5H21M16.0557 5.5L15.3731 4.09173C14.9196 3.15626 14.6928 2.68852 14.3017 2.39681C14.215 2.3321 14.1231 2.27454 14.027 2.2247C13.5939 2 13.0741 2 12.0345 2C10.9688 2 10.436 2 9.99568 2.23412C9.8981 2.28601 9.80498 2.3459 9.71729 2.41317C9.32164 2.7167 9.10063 3.20155 8.65861 4.17126L8.05292 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M9.5 16.5L9.5 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M14.5 16.5L14.5 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="activities.length === 0">
                        <td colspan="3" class="text-center py-4 text-gray-600">
                            Žiadne aktivity v tento deň<br>

                            <Link href="/baby-activities/create" class="inline-block pl-1 pr-4 py-1 mt-2 text-blue-500 font-semibold border border-blue-500 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" class="inline-block mr-1">
                                    <path d="M12 8V16M16 12L8 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12Z" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Pridať
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
