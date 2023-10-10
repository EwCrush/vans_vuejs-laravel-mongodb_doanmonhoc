<template>
    day la page chart
</template>

<script>
import { defineComponent } from 'vue'
import { useMenu } from "../../../stores/use-menu";

export default defineComponent({
    setup() {
        useMenu().onSelectedKeys(["admin-charts"]);
    },
})
</script>

