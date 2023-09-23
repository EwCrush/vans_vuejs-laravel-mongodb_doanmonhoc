<template>
  <div class="bg-black w-full h-20">
    <div class="container flex items-center justify-between h-full">
      <div class="header-img">
        <router-link :to="{ name: 'home' }"
          ><img src="../../assets/imgs/logo.webp" alt=""
        /></router-link>
      </div>
      <nav
        class="header-nav text-white flex justify-evenly items-center w-full"
      >
        <div class="header-nav-item">
          <router-link :to="{ name: 'shop' }">SHOP</router-link>
        </div>
        <div class="header-nav-item">
          <router-link :to="{ name: 'size-chart' }">SIZE CHART</router-link>
        </div>
        <div class="header-nav-item">
          <router-link :to="{ name: 'about-us' }">ABOUT US</router-link>
        </div>
        <div class="header-nav-item">
          <router-link :to="{ name: 'contact' }">CONTACT</router-link>
        </div>
        <div class="header-search">
          <input
            type="text"
            placeholder="Search for products"
            class="header-search-textbox"
          />
          <i
            class="fa-solid fa-magnifying-glass text-black header-nav-item header-search-icon"
          ></i>
        </div>
      </nav>
      <div class="header-user text-white w-1/6">
        <div class="flex justify-evenly" v-if="!token">
          <div class="header-nav-item">
            <router-link :to="{ name: 'login' }">SIGN IN</router-link>
          </div>
          <span>/</span>
          <div class="header-nav-item">
            <router-link :to="{ name: 'register' }">SIGN UP</router-link>
          </div>
        </div>
        <div class="" v-if="token">
          <div class="header-nav-item">
            <div class="header-nav-item">
              <span @click="LogOut">Dang xuat</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, watch, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const token = ref(JSON.parse(localStorage.getItem("token")));
    const LogOut = () => {
      axios
        .post("http://127.0.0.1:8000/api/logout", undefined, {
          headers: { Authorization: `Bearer ${JSON.parse(localStorage.getItem("token")).access_token}` },
        })
        .then(function (response) {
          localStorage.removeItem("token");
        })
        .catch(function (error) {
          console.log(error);
        })
        .finally( function(){
          router.push({ name: "login" });
        });
    };

    watch(
      () => (route.path),
      async () => {
        token.value = JSON.parse(localStorage.getItem("token"));
      }
    );



    // const LogOut = () => {
    //   localStorage.removeItem("token")
    //   router.push({name: "login"})
    // }
    return { token, LogOut };
  },
});
</script>
