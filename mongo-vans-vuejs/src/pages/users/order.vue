<template>
  <div class="bg-gray-100 pt-10">
    <div
      class="mx-auto max-w-6xl justify-center px-6 md:flex md:space-x-6 xl:px-0"
    >
      <div class="rounded-lg md:w-2/3">
        <div class="flex flex-row-reverse mb-4">
          <a-dropdown>
            <template #overlay>
              <a-menu>
                <a-menu-item key="1" @click="filterOrders('')">
                  Mặc định
                </a-menu-item>
                <a-menu-item
                  key="2"
                  @click="filterOrders('confirmationpending')"
                >
                  Đang chờ xác nhận
                </a-menu-item>
                <a-menu-item key="3" @click="filterOrders('processing')">
                  Đang xử lý
                </a-menu-item>
                <a-menu-item key="4" @click="filterOrders('intransit')">
                  Đang vận chuyển
                </a-menu-item>
                <a-menu-item key="5" @click="filterOrders('failed')">
                  Giao hàng thất bại
                </a-menu-item>
                <a-menu-item key="6" @click="filterOrders('completed')">
                  Giao hàng thành công
                </a-menu-item>
              </a-menu>
            </template>
            <a-button>
              Lọc đơn hàng theo<i class="ml-2 fa-solid fa-chevron-down"></i>
            </a-button>
          </a-dropdown>
        </div>
        <a-table
          :columns="columns"
          :data-source="orders"
          :pagination="false"
          :scroll="{ x: 1500 }"
        >
          <template #bodyCell="{ column, record, index }">
            <template v-if="column.key == 'order'">
              <span>{{ index + 1 }}</span>
            </template>
            <template v-if="column.key == 'shippingcost'">
              <span>{{ record.shippingcost.toLocaleString() }}đ</span>
            </template>
            <template v-if="column.key == 'total'">
              <span>{{ record.total.toLocaleString() }}đ</span>
            </template>
            <template v-if="column.key == 'updated_at'">
              <span>{{ formatDate(record.updated_at) }}</span>
            </template>
            <template v-if="column.key == 'action'">
              <span
                class="text-green mr-4 cursor-pointer"
                title="Xem giỏ hàng"
                @click="showListItemModal(record.items)"
              >
                <i class="fa-solid fa-cart-shopping"></i>
              </span>
              <span
                v-if="record.status == 'confirmation pending'"
                class="text-red hover:cursor-pointer"
                title="Hủy đặt hàng"
                @click="cancelOrder(record._id)"
                ><i class="fa-solid fa-x"></i
              ></span>
            </template>
          </template>
        </a-table>
      </div>
      <!-- Sub total -->
      <div
        class="mt-6 h-full rounded-lg border bg-white p-6 shadow-md md:mt-0 md:w-1/3"
      >
        <div class="mb-2 flex justify-between">
          <p class="text-gray-700">Đơn hàng giao <span class="text-primary">thành công</span></p>
          <p class="text-gray-700">
            {{ chart.completed }}
          </p>
        </div>
        <div class="flex justify-between">
          <p class="text-gray-700">Đơn hàng giao <span class="text-red">thất bại</span></p>
          <p class="text-gray-700">{{ chart.failed }}</p>
        </div>
        <hr class="my-4" />
        <div class="flex justify-between">
          <p class="text-lg font-bold">Tổng tiền đã chi</p>
          <div class="">
            <p class="mb-1 text-lg font-bold">{{ Number(chart.total_amount_completed).toLocaleString() }}đ</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <a-modal
    v-model:visible="listItemModal"
    title="Danh sách sản phẩm"
    okText="OK"
    :okButtonProps="{ disabled: true }"
  >
    <div class="max-h-96 overflow-y-auto">
      <div
        v-for="item in orderItems"
        v-bind:key="item.productid + item.size"
        class="justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start"
      >
        <cart-img :path="'products/' + item.image"></cart-img>
        <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
          <div class="mt-5 sm:mt-0">
            <h2 class="text-lg font-bold text-gray-900">
              {{ item.productname }}
            </h2>
            <div class="items-center w-2/3">
              <p class="mt-1 text-xs text-gray-700">
                Size: {{ item.size }}
                <span class="text-primary">x {{ item.quantity }}</span>
              </p>
              <p class="mt-1 text-xs text-gray-700">
                Giá: {{ item.price.toLocaleString() }}đ
              </p>
            </div>
          </div>
          <div
            class="mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6"
          >
            <div class="flex items-center border-gray-100"></div>
            <div class="flex items-center space-x-4">
              <p class="text-sm text-primary">
                {{ item.amount.toLocaleString() }}đ
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </a-modal>
</template>

<script>
import { defineComponent, ref } from "vue";
import CartImg from "../../components/user/CartImg.vue";

export default defineComponent({
  components: { CartImg },
  setup() {
    const token = JSON.parse(localStorage.getItem("token"));
    const status = ref("");
    const orderItems = ref([]);
    const orders = ref([]);
    const chart = ref([]);

    const columns = [
      {
        title: "#",
        dataIndex: "order",
        key: "order",
        width: 25,
      },
      {
        title: "Tên người nhận",
        dataIndex: "fullname_consignee",
        key: "fullname_consignee",
        width: 250,
      },
      {
        title: "SĐT nhận hàng",
        dataIndex: "numberphone_consignee",
        key: "numberphone_consignee",
        width: 150,
      },
      {
        title: "Giao đến",
        dataIndex: "address_consignee",
        key: "address_consignee",
        width: 500,
      },
      {
        title: "Phí ship",
        dataIndex: "shippingcost",
        key: "shippingcost",
        width: 150,
      },
      {
        title: "Tổng tiền",
        dataIndex: "total",
        key: "total",
        width: 150,
      },
      {
        title: "Trạng thái",
        dataIndex: "status",
        key: "status",
        width: 200,
      },
      {
        title: "Ngày cập nhật",
        dataIndex: "updated_at",
        key: "updated_at",
        width: 200,
      },
      {
        title: "Thao tác",
        key: "action",
        width: 100,
        fixed: "right",
      },
    ];

    const listItemModal = ref(false);

    async function getAllOrders() {
      try {
        const response = await axios.get(
          "http://127.0.0.1:8000/api/orders/user?status=" + status.value,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        orders.value = response.data;
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.response.data.message,
        });
      }
    }

    async function getDataFromAllOrder() {
      try {
        const response = await axios.get(
          "http://127.0.0.1:8000/api/orders/user/chart",
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        chart.value = response.data
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.response.data.message,
        });
      }
    }

    function filterOrders(statusFilter) {
      status.value = statusFilter;
      getAllOrders();
    }

    function formatDate(dateObject) {
      const date = new Date(dateObject);
      const day = date.getDate();
      const month = date.getMonth() + 1;
      const year = date.getFullYear();
      const hours = date.getHours();
      const minutes = date.getMinutes();
      const seconds = date.getSeconds();
      return `${hours}:${minutes}:${seconds} ${day}/${month}/${year}`;
    }

    function showListItemModal(items) {
      listItemModal.value = true;
      orderItems.value = items;
    }

    function cancelOrder(id) {
      Swal.fire({
        title: "Bạn chắc chứ?",
        text: "Bạn có muốn hủy đơn hàng này chứ!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Vâng, đồng ý!",
        cancelButtonText: "Hủy",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`http://127.0.0.1:8000/api/orders/user/${id}`, {
              headers: { Authorization: `Bearer ${token.access_token}` },
            })
            .then((response) => {
              Swal.fire("Hủy đặt hàng thành công!", "", "success");
              getAllOrders();
            })
            .catch((error) => {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error.message,
              });
            });
        }
      });
    }

    getAllOrders();
    getDataFromAllOrder();

    return {
      orders,
      columns,
      formatDate,
      filterOrders,
      listItemModal,
      showListItemModal,
      orderItems,
      cancelOrder,
      chart
    };
  },
});
</script>
