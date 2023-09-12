<template>
    <a-menu
        mode="inline"
        v-model:openKeys="openKeys"
        v-model:selectedKeys="selectedKeys"
    >
    <a-menu-item key="all">
          <router-link :to="{ name: 'shop'}">
              <span class="ml-4">Tất cả</span>
          </router-link>
      </a-menu-item>
    <div v-for="(categories) in categories" v-bind:key="categories._id">
      <a-menu-item :key="categories._id">
          <router-link :to="{ name: 'shop-category', params: {id: categories._id}}">
              <span class="ml-4">{{ categories.categoryname }}</span>
          </router-link>
      </a-menu-item>
      </div>
    </a-menu>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { storeToRefs } from 'pinia';
import { useMenu } from '../../stores/category-menu';

export default defineComponent({
  setup() {
    const store = useMenu();
    const categories = ref([]);

    async function getAllCategories() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/allcategories');
        categories.value = response.data;
      } catch (error) {
        console.error(error);
      }
    }

    getAllCategories();
    return {
      ...storeToRefs(store),
      categories
    };
  },
});
</script>