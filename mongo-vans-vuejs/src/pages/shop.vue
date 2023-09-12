<template>
  <div class="container">
    <div class="grid__row">
      <div class="grid__column-sidebar">
        <sidebar></sidebar>
      </div>
      <div class="grid__column-content">
        {{ data }}
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, watch } from "vue";
import { useRoute } from "vue-router";
import Sidebar from "../components/user/UserSidebar.vue";
import { useMenu } from "../stores/category-menu";
export default defineComponent({
  components: { Sidebar },
  setup() {
    const route = useRoute();
    const data = ref([""]);
    const id = !route.params.id ? "all" : route.params.id;
    useMenu().onSelectedKeys([`${id}`]);

    async function getAllCategories() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/categories/get/${route.params.id}`
        );
        console.log(response.data);
        data.value = response.data;
        // this.renderComponent = true;
      } catch (error) {
        console.error(error);
      }
    }

    watch(
      () => route.params.id,
      async () => {
        getAllCategories();
      }
    );

    getAllCategories();

    return { data };
  },
});
</script>
