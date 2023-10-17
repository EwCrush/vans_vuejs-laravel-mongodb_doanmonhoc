<template>
  <div class="bg-gray-100 pt-10">
    <div
      class="mx-auto max-w-6xl justify-center px-6 md:flex md:space-x-6 xl:px-0"
    >
      <div class="rounded-lg md:w-2/3">
        <div
          v-for="item in data.items"
          v-bind:key="item.productid+item.size"
          class="justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start"
        >
          <cart-img :path="'products/' + item.image"></cart-img>
          <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
            <div class="mt-5 sm:mt-0">
              <h2 class="text-lg font-bold text-gray-900">
                {{ item.productname }}
              </h2>
              <div class="items-center w-2/3">
                <p class="mt-1 text-xs text-gray-700">Size: {{ item.size }}</p>
                <p class="mt-1 text-xs text-gray-700">
                  Giá: {{ item.price.toLocaleString() }}đ
                </p>
              </div>
            </div>
            <div
              class="mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6"
            >
              <div class="flex items-center border-gray-100">
                <span
                @click="handleMinus(item.productid, item.size, item.quantity)"
                  class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-blue-500 hover:text-blue-50"
                >
                  -
                </span>
                <input
                  class="no-spinner h-8 w-8 border bg-white text-center text-xs outline-none"
                  type="number"
                  :value="item.quantity"
                  disabled
                  min="1"
                />
                <span @click="handlePlus(item.productid, item.size, item.quantity)"
                  class="cursor-pointer rounded-r bg-gray-100 py-1 px-3 duration-100 hover:bg-blue-500 hover:text-blue-50"
                >
                  +
                </span>
              </div>
              <div class="flex items-center space-x-4">
                <p class="text-sm text-primary">
                  {{ item.amount.toLocaleString() }}đ
                </p>
                <svg
                  @click="deleteFromCart(item.productid, item.size)"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="h-5 w-5 cursor-pointer duration-150 hover:text-red-500"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Sub total -->
      <div
        class="mt-6 h-full rounded-lg border bg-white p-6 shadow-md md:mt-0 md:w-1/3"
      >
        <div class="mb-2 flex justify-between">
          <p class="text-gray-700">Giao đến</p>
          <p
            @click="showEditConsigneeModal"
            class="text-primary text-base hover:cursor-pointer"
          >
            Thay đổi
          </p>
        </div>
        <div class="flex justify-between mb-1">
          <p class="text-black font-semibold">{{ data.fullname_consignee }}</p>
          <p class="text-black font-semibold">
            {{ data.numberphone_consignee }}
          </p>
        </div>
        <p class="text-gray-400 text-sm">{{ data.address_consignee }}</p>
        <hr class="my-4" />
        <div class="mb-2 flex justify-between">
          <p class="text-gray-700">Tạm tính</p>
          <p class="text-gray-700">
            {{ Number(data.total - data.shippingcost).toLocaleString() }}đ
          </p>
        </div>
        <div class="flex justify-between">
          <p class="text-gray-700">Phí ship</p>
          <p class="text-gray-700">
            {{ Number(data.shippingcost).toLocaleString() }}đ
          </p>
        </div>
        <hr class="my-4" />
        <div class="flex justify-between">
          <p class="text-lg font-bold">Tổng cộng</p>
          <div class="">
            <p class="mb-1 text-lg font-bold">
              {{ Number(data.total).toLocaleString() }}đ
            </p>
          </div>
        </div>
        <button
          class="mt-6 w-full rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600"
        >
          Đặt hàng
        </button>
      </div>
    </div>
  </div>
  <a-modal
    v-model:visible="editConsigneeModal"
    title="Cập nhật thông tin"
    cancelText="Hủy"
    :okButtonProps="{ style: { backgroundColor: '#3060FF' } }"
    @ok="editConsignee"
  >
    <a-tabs v-model:activeKey="activeEditConsigneeKey">
      <a-tab-pane key="1" tab="Thông tin cơ bản">
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.fullnameConsignee,
              'min-w-label inline-block': true,
            }"
            >Tên người nhận:
          </label>
          <a-input
            placeholder="Nhập vào tên người nhận..."
            allow-clear
            style="width: 60%"
            v-model:value="fullnameConsignee"
            :class="{ 'border-1 border-rose-600': errors.fullnameConsignee }"
          />
          <small
            class="text-red ml-fromLabel block"
            v-if="errors.fullnameConsignee"
            >{{ errors.fullnameConsignee[0] }}</small
          >
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.phoneConsignee,
              'min-w-label inline-block': true,
            }"
            >Số điện thoại:
          </label>
          <a-input
            placeholder="Nhập vào số điện thoại người nhận..."
            allow-clear
            style="width: 60%"
            v-model:value="phoneConsignee"
            :class="{ 'border-1 border-rose-600': errors.phoneConsignee }"
          />
          <small
            class="text-red ml-fromLabel block"
            v-if="errors.phoneConsignee"
            >{{ errors.phoneConsignee[0] }}</small
          >
        </div>
      </a-tab-pane>
      <a-tab-pane key="2" tab="Địa chỉ">
        <change-address
          :address="addressConsignee"
          @address-changed="handleAddressConsigneeChange"
        ></change-address>
      </a-tab-pane>
    </a-tabs>
  </a-modal>
</template>

<script>
import { defineComponent, reactive, ref, toRefs } from "vue";
import CartImg from "../../components/user/CartImg.vue";
import ChangeAddress from "../../components/user/ChangeAddress.vue";

export default defineComponent({
  components: { CartImg, ChangeAddress },
  setup() {
    const data = ref([]);
    const token = JSON.parse(localStorage.getItem("token"));

    const consignee = reactive({
      fullnameConsignee: "",
      phoneConsignee: "",
      addressConsignee: "",
    });

    const errors = ref({})

    const editConsigneeModal = ref(false);
    const activeEditConsigneeKey = ref("1");

    async function getCart() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/carts`, {
          headers: { Authorization: `Bearer ${token.access_token}` },
        });
        data.value = response.data;
        (consignee.fullnameConsignee = response.data.fullname_consignee),
          (consignee.phoneConsignee =
            response.data.numberphone_consignee),
          (consignee.addressConsignee = response.data.address_consignee);
        //console.log(response);
      } catch (error) {
        console.error(error);
      }
    }

    async function editConsignee() {
      try {
        const response = await axios.put(`http://127.0.0.1:8000/api/carts/consignee`, consignee, {
          headers: { Authorization: `Bearer ${token.access_token}` },
        });
        getCart()
        editConsigneeModal.value = false
      } catch (error) {
        if (error.response.status == 422) {
            errors.value = error.response.data.errors;
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
          }
          activeEditConsigneeKey.value="1"
      }
    }

    function showEditConsigneeModal() {
      editConsigneeModal.value = true;
    }

    async function deleteFromCart(product, size) {
      try {
        const response = await axios.delete(
          `http://127.0.0.1:8000/api/carts?product=${product}&size=${size}`,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        getCart();
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.response.data.message,
        });
      }
    }

    const handleAddressConsigneeChange = (newAddress) => {
      consignee.addressConsignee = newAddress;
    };

    function handlePlus(product, size, quantity) {
      editQuantity(product, size, quantity+1)
    }

    function handleMinus(product, size, quantity) {
      if(quantity!=1){
        editQuantity(product, size, quantity-1)
      }
    }

    async function editQuantity(product, size, quantity) {
      try {
        const response = await axios.put(
          `http://127.0.0.1:8000/api/carts`,
          {
            product: product,
            size: size,
            quantity: quantity
          },
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        getCart();
        //console.log(response);
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.response.data.message,
        });
      }
    }

    getCart();
    return {
      data,
      ...toRefs(consignee),
      deleteFromCart,
      handleAddressConsigneeChange,
      editConsigneeModal,
      showEditConsigneeModal,
      editConsignee,
      activeEditConsigneeKey,
      errors,
      handleMinus,
      handlePlus
    };
  },
});
</script>
