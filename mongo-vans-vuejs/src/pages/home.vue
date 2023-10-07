<template>
  <header
    class="bg-gray-700 text-white h-screen flex flex-col justify-center items-center"
  >
    <h1 class="text-5xl font-bold mb-4">Step into Style with Vans Shoes</h1>
    <p class="text-lg mb-8">
      Discover the latest collection of Vans footwear. Find your perfect pair
      today.
    </p>
    <router-link :to="{ name: 'shop' }">
      <span
        class="bg-white text-gray-800 py-2 px-6 rounded-full font-bold hover:bg-gray-300"
        >Shop Now</span
      >
    </router-link>
  </header>

  <div class="relative h-screen">
    <img
      src="../assets/imgs/vanslandingpage-2.webp"
      class="object-cover w-full h-full"
    />
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="absolute inset-0 flex items-center justify-center">
      <h2 class="text-4xl font-bold text-white">Discover the Vans Lifestyle</h2>
    </div>
  </div>
  <div class="flex h-screen">
    <div class="w-1/2 relative">
        <img
          src="../assets/imgs/vanslandingpage-1.webp"
          alt="Vans Background"
          class="object-cover w-full h-full"
        />
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="absolute inset-0 flex items-center justify-center">
          <h2 class="text-4xl font-bold text-white">Love in Every Step, Walk Together</h2>
        </div>
    </div>
    <div class="w-1/2 relative">
        <img
          src="../assets/imgs/vanslandingpage-4.webp"
          class="object-cover w-full h-full"
        />
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="absolute inset-0 flex items-center justify-center">
          <h2 class="text-4xl font-bold text-white">Love Your Style, Love Your Stride</h2>
        </div>
    </div>
</div>





  <!-- Featured Products -->
  <section id="shop" class="container mx-auto mt-12">
    <h2 class="text-3xl font-bold mb-6">Classic Products</h2>
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
  </section>

  <!-- Contact Section -->
  <section class="bg-gray-800 text-white py-16">
    <div class="container mx-auto text-center">
      <h2 class="text-3xl font-bold mb-6">Contact Us</h2>
      <p class="text-lg mb-8">
        Have questions or need assistance? Reach out to us!
      </p>
      <router-link :to="{ name: 'contact' }">
        <span
        class="bg-white text-gray-800 py-2 px-6 rounded-full font-bold hover:bg-gray-300"
        >Contact Now</span
      >
    </router-link>
    </div>
  </section>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useRoute } from "vue-router";
import ShopImage from "../components/user/ShopImage.vue";

export default defineComponent({
    components: { ShopImage },
  setup() {
    const data = ref([]);
    const route = useRoute();

    async function getProducts() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/products/classic`
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

