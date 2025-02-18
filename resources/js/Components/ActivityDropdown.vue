<script setup>
import { ref } from "vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

const activities = [
    { id: "sleep", label: "Spánok" },
    { id: "feeding", label: "Kŕmenie" },
    { id: "diaper_change", label: "Prebaľovanie" },
];

const selectedActivity = ref(null);

defineProps(["onSelect"]);
</script>

<template>
    <Menu as="div" class="relative inline-block text-left w-full">
        <MenuButton class="w-full bg-gray-200 px-4 py-2 rounded-lg text-left">
            {{ selectedActivity ? selectedActivity.label : "Vyber aktivitu" }}
        </MenuButton>

        <MenuItems
            class="absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
        >
            <MenuItem
                v-for="activity in activities"
                :key="activity.id"
                v-slot="{ active }"
            >
                <button
                    @click="selectedActivity = activity; $emit('onSelect', activity.id)"
                    :class="[
                        'w-full px-4 py-2 text-left',
                        active ? 'bg-gray-100' : 'bg-white',
                    ]"
                >
                    {{ activity.label }}
                </button>
            </MenuItem>
        </MenuItems>
    </Menu>
</template>
