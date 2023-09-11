<template>
    <admin-header></admin-header>
    <div class="container">
        <div class="grid__row">
            <div class="grid__column-sidebar">
                <sidebar></sidebar>
            </div>
            <div class="grid__column-content">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>

import AdminHeader from '../components/admin/AdminHeader.vue';
import Sidebar from '../components/admin/Sidebar.vue';

export default {
    components:{
        AdminHeader,
        Sidebar
    }
}
</script>