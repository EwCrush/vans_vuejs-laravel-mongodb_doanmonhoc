<template>
    <user-header-vue></user-header-vue>
    <router-view></router-view>
</template>

<script>
import UserHeaderVue from '../components/user/UserHeader.vue'
export default {
    components:{
        UserHeaderVue,
    }
}
</script>