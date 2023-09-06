<template>
    <div>
        day la trang quan ly users
    </div>
</template>

<script>
import { useMenu } from '../../../stores/use-menu';
export default {
    setup() {
        useMenu().onSelectedKeys(["admin-users"]);
    },
}
</script>