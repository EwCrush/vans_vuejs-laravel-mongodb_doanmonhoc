<template>
    <a-list bordered>
        <template #header>
            <div class="text-black text-lg">
                <span class="ml-2">Danh mục</span>
            </div>
        </template>
        <admin-menu></admin-menu>
  </a-list>
</template>

<script>
    import AdminMenu from './AdminMenu.vue';
    export default {
    components:{
        AdminMenu
    }
}
</script>