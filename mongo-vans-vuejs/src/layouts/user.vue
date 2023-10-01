<template>
    <user-header-vue></user-header-vue>
    <router-view></router-view>
    <page-footer></page-footer>
</template>

<script>
import UserHeaderVue from '../components/user/Header.vue';
import PageFooter from '../components/PageFooter.vue';
export default {
    components:{
        UserHeaderVue,
        PageFooter
    }
}
</script>