<template>
     <div class=" bg-black w-full h-20">
        <div class="container flex items-center justify-between h-full">
            <div class="lg:hidden px-4 py-4 text-lg">
                <i class="fa-solid fa-bars text-white" @click="showDrawer()"></i>
            </div>
            <div class="header-img">
                <img src="../../assets//imgs/logo.webp" alt="">
            </div>
            <div class="text-white px-4 py-4">Admin</div>
        </div>
     </div>
     <a-drawer
     v-model:visible="visible"
        title="Danh mục"
        placement="left"
    >
    <admin-menu></admin-menu>
  </a-drawer>
</template>

<script>
    import { defineComponent, ref } from 'vue';
    import AdminMenu from './AdminMenu.vue';
    export default defineComponent({
        components: { AdminMenu },
        setup() {
            const visible = ref(false);
            const showDrawer = () => {
                visible.value = true;
            };
            return {
                visible,
                showDrawer,
            };
        },
        // components: {
        //     Sidebar
        // }
    });
</script>


