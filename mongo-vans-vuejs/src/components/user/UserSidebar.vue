<template>
    <a-list bordered>
        <template #header>
            <div class="text-black text-lg">
                <span class="ml-2">Danh mục</span>
            </div>
        </template>
        <user-menu></user-menu>
  </a-list>
</template>

<script>
    import UserMenu from './UserMenu.vue';
    export default {
    components:{
        UserMenu
    }
}
</script>