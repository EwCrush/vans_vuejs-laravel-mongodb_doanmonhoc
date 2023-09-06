<template>
    <div>
        day la trang quan ly products
    </div>
</template>

<script>
import { useMenu } from '../../../stores/use-menu';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-products"]);
    },
}
</script>

