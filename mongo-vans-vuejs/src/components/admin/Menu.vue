<template>
    <a-menu
        mode="inline"
        v-model:openKeys="openKeys"
        v-model:selectedKeys="selectedKeys"
    >
      <a-menu-item key="admin-categories">
          <router-link :to="{ name: 'admin-categories'}">
              <i class="fa-solid fa-tag"></i>
              <span class="ml-4">Loại sản phẩm</span>
          </router-link>
      </a-menu-item>
      <a-menu-item key="admin-products">
          <router-link :to="{ name: 'admin-products'}">
              <i class="fa-solid fa-shoe-prints"></i>
              <span class="ml-4">Sản phẩm</span>
          </router-link>
      </a-menu-item>
      <a-menu-item key="admin-users">
          <router-link :to="{ name: 'admin-users'}">
              <i class="fa-solid fa-user"></i>
              <span class="ml-4">Người dùng</span>
          </router-link>
      </a-menu-item>
    </a-menu>
</template>

<script>
import { defineComponent } from 'vue';
import { storeToRefs } from 'pinia';
import { useMenu } from '../../stores/use-menu';

export default defineComponent({
  setup() {
    const store = useMenu();
    return {
      ...storeToRefs(store)
    };
  },
});
</script>