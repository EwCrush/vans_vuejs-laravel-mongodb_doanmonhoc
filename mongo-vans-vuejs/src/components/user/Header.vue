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
            v-model="keyword"
          />
          <router-link
            v-if="!$route.params.id"
            :to="{ name: 'shop', query: { keyword: keyword } }"
          >
            <i
              class="fa-solid fa-magnifying-glass text-black header-nav-item header-search-icon"
            ></i>
          </router-link>
          <router-link
            v-if="$route.params.id"
            :to="{
              name: 'shop-category',
              params: { id: $route.params.id },
              query: { keyword: keyword },
            }"
          >
            <i
              class="fa-solid fa-magnifying-glass text-black header-nav-item header-search-icon"
            ></i>
          </router-link>
        </div>
      </nav>
      <div class="header-user text-white w-1/6">
        <div class="flex justify-evenly" v-if="!tokenHeader">
          <div class="header-nav-item">
            <router-link :to="{ name: 'login' }">SIGN IN</router-link>
          </div>
          <span>/</span>
          <div class="header-nav-item">
            <router-link :to="{ name: 'register' }">SIGN UP</router-link>
          </div>
        </div>
        <div v-if="tokenHeader">
          <div class="flex items-center">
            <a-popover placement="bottomLeft" class="hover:cursor-pointer">
              <template #content>
                <div class="flex items-center">
                  <a-avatar :size="20" class="mr-2" :src="src"></a-avatar>
                  <p>{{ user.fullname }}</p>
                </div>
                <hr class="my-2" />
                <router-link :to="{ name: 'cart' }">
                  <p class="hover:text-primary cursor-pointer">
                    <i class="mr-2 mb-2 fa-solid fa-cart-shopping"></i>Giỏ hàng
                    <span class="text-primary">({{ cartItem }})</span>
                  </p>
                </router-link>
                <p class="hover:text-primary cursor-pointer">
                  <i class="mr-2 my-2 fa-solid fa-truck-fast"></i>Đơn hàng
                </p>
                <p class="hover:text-primary cursor-pointer">
                  <i class="mr-2 my-2 fa-solid fa-user-pen"></i>Chỉnh sửa thông
                  tin
                </p>
                <p
                  @click="showChangePasswordModal"
                  class="hover:text-primary cursor-pointer"
                >
                  <i class="mr-2 mt-2 fa-solid fa-key"></i>Đổi mật khẩu
                </p>
                <hr class="my-2" />
                <p @click="LogOut" class="hover:text-primary cursor-pointer">
                  <i class="mr-2 fa-solid fa-right-from-bracket"></i>Đăng xuất
                </p>
              </template>
              <a-badge :count="cartItem" :show-zero="false">
                <a-avatar :size="40" class="ml-2" :src="src"></a-avatar>
              </a-badge>
            </a-popover>
          </div>
        </div>
      </div>
    </div>
    <a-modal
      cancelText="Hủy"
      :okButtonProps="{ style: { backgroundColor: '#3060FF' } }"
      v-model:visible="ChangePasswordModal"
      title="Đổi mật khẩu"
      @ok="ChangePassword"
    >
      <div class="w-full pb-4">
        <label
          :class="{
            'text-red': errors.oldpassword,
            'min-w-label inline-block': true,
          }"
          >Mật khẩu cũ:
        </label>
        <a-input-password
          placeholder="Nhập vào mật khẩu cũ..."
          allow-clear
          style="width: 60%"
          v-model:value="oldpassword"
          :class="{ 'border-1 border-rose-600': errors.oldpassword }"
        />
        <small class="text-red ml-fromLabel block" v-if="errors.oldpassword">{{
          errors.oldpassword[0]
        }}</small>
      </div>
      <div class="w-full pb-4">
        <label
          :class="{
            'text-red': errors.newpassword,
            'min-w-label inline-block': true,
          }"
          >Mật khẩu mới:
        </label>
        <a-input-password
          placeholder="Nhập vào mật khẩu mới..."
          allow-clear
          style="width: 60%"
          v-model:value="newpassword"
          :class="{ 'border-1 border-rose-600': errors.newpassword }"
        />
        <small class="text-red ml-fromLabel block" v-if="errors.newpassword">{{
          errors.newpassword[0]
        }}</small>
      </div>
      <div class="w-full pb-4">
        <label
          :class="{
            'text-red': errors.newpassword,
            'min-w-label inline-block': true,
          }"
          >Xác nhận mật khẩu:
        </label>
        <a-input-password
          placeholder="Nhập lại mật khẩu để xác nhận..."
          allow-clear
          style="width: 60%"
          v-model:value="newpassword_confirmation"
          :class="{ 'border-1 border-rose-600': errors.newpassword }"
        />
      </div>
    </a-modal>
  </div>
</template>

<script>
import { defineComponent, watch, ref, reactive, toRefs } from "vue";
import { useRouter, useRoute } from "vue-router";
import { storage } from "../../firebase";
import { ref as fbref, getDownloadURL } from "firebase/storage";
export default defineComponent({
  setup() {
    const router = useRouter();
    const route = useRoute();
    const tokenHeader = ref(JSON.parse(localStorage.getItem("token")));
    const keyword = ref("");
    const user = ref([]);
    const src = ref(
      "data:image/png;base64,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"
    );
    const cartItem = ref(0);
    const ChangePasswordModal = ref(false);

    const password = reactive({
      oldpassword: "",
      newpassword: "",
      newpassword_confirmation: "",
    });
    const errors = ref({});

    const showChangePasswordModal = () => {
      ChangePasswordModal.value = true;
    };

    const LogOut = () => {
      axios
        .post("http://127.0.0.1:8000/api/logout", undefined, {
          headers: {
            Authorization: `Bearer ${
              JSON.parse(localStorage.getItem("token")).access_token
            }`,
          },
        })
        .then(function (response) {
          localStorage.removeItem("token");
        })
        .catch(function (error) {
          console.log(error);
        })
        .finally(function () {
          router.push({ name: "login" });
        });
    };

    async function getUserFromTokenHeader() {
      if (tokenHeader) {
        try {
          const response = await axios.get(
            `http://127.0.0.1:8000/api/users/token`,
            {
              headers: {
                Authorization: `Bearer ${tokenHeader.value.access_token}`,
              },
            }
          );
          user.value = response.data;
          getDownloadURL(fbref(storage, "users/" + response.data.avatar)).then(
            (download_url) => (src.value = download_url)
          );
        } catch (error) {
          if (error.response.status == 404) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
          }
          console.log(error);
        }
      }
    }

    async function getCart() {
      if (tokenHeader) {
        try {
          const response = await axios.get(`http://127.0.0.1:8000/api/carts`, {
            headers: {
              Authorization: `Bearer ${tokenHeader.value.access_token}`,
            },
          });
          cartItem.value = response.data.items.length;
        } catch (error) {
          console.error(error);
        }
      }
    }

    async function ChangePassword() {
      if (tokenHeader) {
        try {
          const response = await axios.post(
            `http://127.0.0.1:8000/api/users/changepassword`,
            password,
            {
              headers: {
                Authorization: `Bearer ${tokenHeader.value.access_token}`,
              },
            }
          );
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: response.data.message,
            showConfirmButton: false,
            timer: 1500,
          });
          ChangePasswordModal.value = false;
        } catch (error) {
          if (error.response.status == 422) {
            errors.value = error.response.data.errors;
          }
          else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
          }
        }
      }
    }

    watch(
      () => route.path,
      async () => {
        tokenHeader.value = JSON.parse(localStorage.getItem("token"));
      }
    );

    getCart();

    getUserFromTokenHeader();

    // const LogOut = () => {
    //   localStorage.removeItem("token")
    //   router.push({name: "login"})
    // }
    return {
      tokenHeader,
      LogOut,
      keyword,
      user,
      src,
      cartItem,
      showChangePasswordModal,
      ChangePassword,
      ChangePasswordModal,
      ...toRefs(password),
      errors,
    };
  },
});
</script>
