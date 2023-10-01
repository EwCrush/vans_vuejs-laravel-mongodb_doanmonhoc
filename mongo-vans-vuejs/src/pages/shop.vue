<template>
  <div class="container">
    <div class="grid__row">
      <div class="grid__column-sidebar">
        <sidebar></sidebar>
      </div>
      <div class="grid__column-content">
        <div class="w-full flex items-center justify-between h-14 px-4">
          <div class="flex items-center">
            <a-button
              v-if="$route.params.id"
              :class="{
                'bg-primary text-white': saleoff != 'true' || !saleoff,
                'mr-2': true,
              }"
              ><router-link
                :to="{
                  name: 'shop-category',
                  params: { id: $route.params.id },
                  query: RemoveQuery('saleoff'),
                }"
                >Tất cả</router-link
              ></a-button
            >
            <a-button
              v-if="!$route.params.id"
              :class="{
                'bg-primary text-white': saleoff != 'true' || !saleoff,
                'mr-2': true,
              }"
              ><router-link
                :to="{
                  name: 'shop',
                  query: RemoveQuery('saleoff'),
                }"
                >Tất cả</router-link
              ></a-button
            >
            <a-button
              v-if="$route.params.id"
              :class="{
                'bg-primary text-white': saleoff == 'true',
                'mr-2': true,
              }"
              ><router-link
                :to="{
                  name: 'shop-category',
                  params: { id: $route.params.id },
                  query: { ...$route.params.query, saleoff: 'true' },
                }"
                >Đang giảm giá</router-link
              ></a-button
            >
            <a-button
              v-if="!$route.params.id"
              :class="{
                'bg-primary text-white': saleoff == 'true',
                'mr-2': true,
              }"
              ><router-link
                :to="{
                  name: 'shop',
                  query: { ...$route.params.query, saleoff: 'true' },
                }"
                >Đang giảm giá</router-link
              ></a-button
            >
            <a-dropdown v-if="$route.params.id">
              <template #overlay>
                <a-menu>
                  <router-link
                    :to="{
                      name: 'shop-category',
                      params: { id: $route.params.id },
                      query: RemoveQuery('order'),
                    }"
                    ><a-menu-item key="1"> Mặc định </a-menu-item></router-link
                  >
                  <router-link
                    :to="{
                      name: 'shop-category',
                      params: { id: $route.params.id },
                      query: {
                        ...$route.query,
                        order: 'az',
                      },
                    }"
                    ><a-menu-item key="2">
                      Tên sản phẩm A-Z
                    </a-menu-item></router-link
                  >
                  <router-link
                    :to="{
                      name: 'shop-category',
                      params: { id: $route.params.id },
                      query: {
                        ...$route.query,
                        order: 'za',
                      },
                    }"
                    ><a-menu-item key="3">
                      Tên sản phẩm Z-A
                    </a-menu-item></router-link
                  >
                  <router-link
                    :to="{
                      name: 'shop-category',
                      params: { id: $route.params.id },
                      query: {
                        ...$route.query,
                        order: 'increase',
                      },
                    }"
                    ><a-menu-item key="4">
                      Giá tăng dần
                    </a-menu-item></router-link
                  >
                  <router-link
                    :to="{
                      name: 'shop-category',
                      params: { id: $route.params.id },
                      query: {
                        ...$route.query,
                        order: 'decrease',
                      },
                    }"
                    ><a-menu-item key="5">
                      Giá giảm dần
                    </a-menu-item></router-link
                  >
                </a-menu>
              </template>
              <a-button>
                Sắp xếp theo<i class="ml-2 fa-solid fa-chevron-down"></i
              ></a-button>
            </a-dropdown>
            <a-dropdown v-if="!$route.params.id">
              <template #overlay>
                <a-menu>
                  <router-link
                    :to="{
                      name: 'shop',
                      query: RemoveQuery('order'),
                    }"
                    ><a-menu-item key="1"> Mặc định </a-menu-item></router-link
                  >
                  <router-link
                    :to="{
                      name: 'shop',
                      query: {
                        ...$route.query,
                        order: 'az',
                      },
                    }"
                    ><a-menu-item key="2">
                      Tên sản phẩm A-Z
                    </a-menu-item></router-link
                  >
                  <router-link
                    :to="{
                      name: 'shop',
                      query: {
                        ...$route.query,
                        order: 'za',
                      },
                    }"
                    ><a-menu-item key="3">
                      Tên sản phẩm Z-A
                    </a-menu-item></router-link
                  >
                  <router-link
                    :to="{
                      name: 'shop',
                      query: {
                        ...$route.query,
                        order: 'increase',
                      },
                    }"
                    ><a-menu-item key="4">
                      Giá tăng dần
                    </a-menu-item></router-link
                  >
                  <router-link
                    :to="{
                      name: 'shop',
                      query: {
                        ...$route.query,
                        order: 'decrease',
                      },
                    }"
                    ><a-menu-item key="5">
                      Giá giảm dần
                    </a-menu-item></router-link
                  >
                </a-menu>
              </template>
              <a-button>
                Sắp xếp theo<i class="ml-2 fa-solid fa-chevron-down"></i
              ></a-button>
            </a-dropdown>
          </div>
          <div class="flex items-center justify-center">
            <span class="text-primary font-semibold text-base">{{
              currentPage
            }}</span
            ><span class="text-base mr-2">/{{ lastPage }}</span>
            <button
              title="Đến trang trước đó"
              class="px-4 py-2 bg-gray-100 hover:text-primary text-base border-r-2"
              @click="goToPrevPage(!prevPage_url ? lastPage_url : prevPage_url)"
            >
              <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button
              title="Đến trang kế tiếp"
              class="px-4 py-2 bg-gray-100 hover:text-primary text-base"
              @click="
                goToNextPage(!nextPage_url ? firstPage_url : nextPage_url)
              "
            >
              <i class="fa-solid fa-chevron-right"></i>
            </button>
          </div>
        </div>
        <div class="grid__row">
          <div
            class="grid__column-content-item"
            v-for="data in data"
            v-bind:key="data._id"
          >
            <div
              class="relative m-2 flex w-full max-w-xs flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md"
            >
              <!-- router here -->
              <div
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
              </div>
              <div class="mt-4 px-5 pb-5">
                <a href="#">
                  <h5
                    class="text-base font-medium text-center line-clamp-2 h-12 tracking-tight text-slate-900 hover:text-primary"
                  >
                    {{ data.productname }}
                  </h5>
                </a>
                <div class="mt-2 mb-5 flex items-center justify-between">
                  <p>
                    <span class="text-xl mr-2 font-bold text-slate-900"
                      >{{ data.sellingprice.toLocaleString() }}đ</span
                    >
                    <span
                      v-if="data.originalprice != data.sellingprice"
                      class="text-xs text-slate-900 line-through"
                      >{{ data.originalprice.toLocaleString() }}đ</span
                    >
                  </p>
                </div>
                <a
                  href="#"
                  class="flex items-center justify-center rounded-md bg-primary px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mr-2 h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                    />
                  </svg>
                  Add to cart</a
                >
              </div>
            </div>
          </div>
        </div>
        <div
          class="flex justify-center items-center mt-4 text-black font-[600]"
        >
          <div class="flex items-center justify-center">
            <button
              title="Đến trang trước đó"
              class="p-4 hover:text-primary text-lg"
              @click="goToPrevPage(!prevPage_url ? lastPage_url : prevPage_url)"
            >
              <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div v-for="(item, index) in lastPage" v-bind:key="index">
              <button
                :class="[
                  item == currentPage
                    ? 'bg-primary text-white rounded-lg hover:text-white'
                    : '',
                  'py-2 px-4 hover:text-primary',
                ]"
                @click="goToPage(item)"
              >
                {{ item }}
              </button>
            </div>
            <button
              title="Đến trang kế tiếp"
              class="p-4 hover:text-primary text-lg"
              @click="
                goToNextPage(!nextPage_url ? firstPage_url : nextPage_url)
              "
            >
              <i class="fa-solid fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, watch } from "vue";
import { useRoute } from "vue-router";
import ShopImage from "../components/user/ShopImage.vue";
import Sidebar from "../components/user/UserSidebar.vue";
import { useMenu } from "../stores/category-menu";
export default defineComponent({
  components: { Sidebar, ShopImage },
  setup() {
    const route = useRoute();
    const data = ref([]);

    const nextPage_url = ref("");
    const prevPage_url = ref("");
    const firstPage_url = ref("");
    const lastPage_url = ref("");
    const lastPage = ref("");
    const currentPage = ref("");
    const saleoff = ref("");

    const setKey = () => {
      if (!route.params.id) {
        useMenu().onSelectedKeys(["all"]);
      } else {
        useMenu().onSelectedKeys([parseInt(route.params.id)]);
      }
    };

    const checkSaleoff = () => {
      if(route.query.saleoff == 'true'){
        saleoff.value='true';
      }
      else{
        saleoff.value=''
      }
    }

    const saleoffQuery = () => {
      if (route.query.saleoff == "true") {
        saleoff.value = "true";
      }
    };

    async function getAllProducts() {
      try {
        const id = route.params.id ? route.params.id : "";
        const keyword = route.query.keyword ? route.query.keyword : "";
        const saleoff = route.query.saleoff ? route.query.saleoff : "";
        const order = route.query.order ? route.query.order : "";
        const response = await axios.get(
          `http://127.0.0.1:8000/api/products/filter?id=${id}&keyword=${keyword}&saleoff=${saleoff}&order=${order}`
        );
        data.value = response.data.data;
        firstPage_url.value = response.data.first_page_url;
        lastPage_url.value = response.data.last_page_url;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        lastPage.value = response.data.last_page;
        currentPage.value = response.data.current_page;
        // this.renderComponent = true;
      } catch (error) {
        console.error(error);
      }
    }

    watch(
      () => route.fullPath,
      async () => {
        getAllProducts();
        setKey();
        checkSaleoff();
      }
    );

    const goToNextPage = async (url) => {
      try {
        const response = await axios.get(url);
        data.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
      } catch (error) {
        console.log(error);
      }
    };

    const goToPrevPage = async (url) => {
      try {
        const response = await axios.get(url);
        data.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
      } catch (error) {
        console.log(error);
      }
    };

    const goToPage = async (number) => {
      try {
        // const url =
        //   keyword === null || keyword === ""
        //     ? "http://127.0.0.1:8000/api/products?page=" + number
        //     : "http://127.0.0.1:8000/api/products/search/" +
        //       keyword +
        //       "?page=" +
        //       number;
        const url = `http://127.0.0.1:8000/api/products/filter?id=${route.params.id}?page=+${number}`;
        const response = await axios.get(url);
        data.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
      } catch (error) {
        console.log(error);
      }
    };

    const RemoveQuery = (query) => {
      const newQuery = { ...route.query };
      if (newQuery.hasOwnProperty(query)) {
        delete newQuery[query];
      }

      return newQuery;
    };

    getAllProducts();

    setKey();

    saleoffQuery();

    return {
      data,
      goToNextPage,
      goToPrevPage,
      goToPage,
      nextPage_url,
      prevPage_url,
      firstPage_url,
      lastPage_url,
      lastPage,
      currentPage,
      saleoff,
      RemoveQuery,
    };
  },
});
</script>
