<template>
  <div class="container w-full px-4 border-t border-gray-200 dark:border-gray-700 dark:bg-gray-900">
    <span class="block py-4 text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">Sản phẩm cùng loại</span>
    <div class="grid__row">
      <div
        class="grid__column-content-item-suggest"
        v-for="data in data"
        v-bind:key="data._id"
      >
        <div
          class="relative m-2 flex w-full max-w-xs flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md"
        >
          <router-link :to="{ name: 'detail', params: { id: data._id } }"
            ><div
              class="relative mx-3 mt-3 flex h-60 overflow-hidden rounded-xl hover:cursor-pointer"
            >
              <shop-image :path="'products/' + data.thumbnail"></shop-image>
              <span
                v-if="data.originalprice != data.sellingprice"
                class="absolute top-0 left-0 m-2 rounded-full bg-primary px-2 text-center text-sm font-medium text-white"
                >{{
                  Math.round(
                    ((data.originalprice - data.sellingprice) /
                      data.originalprice) *
                      100
                  )
                }}% OFF</span
              >
            </div></router-link
          >
          <div class="mt-4 px-5 pb-5">
            <router-link :to="{ name: 'detail', params: { id: data._id } }"
              ><div>
                <h5
                  class="text-base font-medium text-center line-clamp-2 h-12 tracking-tight text-slate-900 hover:text-primary"
                >
                  {{ data.productname }}
                </h5>
              </div></router-link
            >
            <div class="mt-2 mb-5 flex items-center justify-between">
              <p>
                <span class="text-xl mr-2 font-bold text-primary"
                  >{{ data.sellingprice.toLocaleString() }}đ</span
                >
                <span
                  v-if="data.originalprice != data.sellingprice"
                  class="text-xs text-slate-900 line-through"
                  >{{ data.originalprice.toLocaleString() }}đ</span
                >
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useRoute } from "vue-router";
import ShopImage from "./ShopImage.vue";

export default defineComponent({
    components: { ShopImage },
  setup() {
    const data = ref([]);
    const route = useRoute();

    async function getProducts() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/products/categories/${route.params.id}`
        );
        data.value = response.data;
      } catch (error) {
        console.error(error);
      }
    }

    getProducts();
    return { data }
  },
});
</script>
