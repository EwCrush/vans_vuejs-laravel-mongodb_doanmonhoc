<template>
  <a-card title="Đơn đặt hàng" style="width: 100%">
    <div class="flex flex-row-reverse mb-4">
      <a-dropdown>
        <template #overlay>
          <a-menu>
            <a-menu-item key="1" @click="filterOrders('')">
              Mặc định
            </a-menu-item>
            <a-menu-item key="2" @click="filterOrders('confirmationpending')">
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
      <template #bodyCell="{ column, record }">
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
            @click="handleConfirmationPending(record._id)"
            v-if="record.status == 'confirmation pending'"
            class="text-primary mr-4 cursor-pointer"
            title="Xác nhận thành công"
          >
            <i class="fa-solid fa-user-check"></i>
          </span>
          <span
            v-if="record.status != 'confirmation pending'"
            @click="showEditOrderStatusModal(record._id, record.status)"
            class="text-yellow mr-4 cursor-pointer"
            title="Chỉnh sửa"
          >
            <i class="fa-solid fa-pen-to-square"></i>
          </span>
          <router-link
            class="text-green mr-4 cursor-pointer"
            title="Xem giỏ hàng"
            :to="{
              name: 'admin-orders-cart',
              params: { id: record._id },
            }"
          >
            <i class="fa-solid fa-cart-shopping"></i>
          </router-link>
        </template>
      </template>
    </a-table>
    <div
      class="flex flex-row-reverse justify-between mt-4 text-black font-[600]"
    >
      <div class="flex justify-between items-center">
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
          @click="goToNextPage(!nextPage_url ? firstPage_url : nextPage_url)"
        >
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>
    </div>
    <a-modal
      v-model:visible="editOrderStatus"
      title="Cập nhật trạng thái đơn hàng"
      cancelText="Hủy"
      :okButtonProps="{ style: { backgroundColor: '#3060FF' } }"
      @ok="handleEditOrderStatus"
    >
      <div class="w-full py-4">
        <a-select
          allowClear
          placeholder="Chọn trạng thái đơn hàng, nhập tên để tìm kiếm..."
          style="width: 100%"
          show-search
          :options="options"
          :filter-option="filterOption"
          v-model:value="OrderStatus"
          :status="errors.OrderStatus ? 'error' : ''"
        />
        <small class="text-red ml-2" v-if="errors.OrderStatus">{{
          errors.OrderStatus
        }}</small>
      </div>
    </a-modal>
  </a-card>
</template>

<script>
import { useMenu } from "../../../stores/use-menu";
import { defineComponent, ref } from "vue";

export default defineComponent({
  setup() {
    useMenu().onSelectedKeys(["admin-orders"]);

    const orders = ref([]);
    const nextPage_url = ref("");
    const prevPage_url = ref("");
    const firstPage_url = ref("");
    const lastPage_url = ref("");
    const lastPage = ref("");
    const currentPage = ref("");
    const status = ref("");
    const token = JSON.parse(localStorage.getItem("token"));
    const editOrderStatus = ref(false);
    const OrderStatus = ref([]);
    const errors = ref({});
    const orderID = ref("");

    const options = ref([
      {
        value: "confirmation pending",
        label: "confirmation pending",
      },
      {
        value: "processing",
        label: "processing",
      },
      {
        value: "in transit",
        label: "in transit",
      },
      {
        value: "completed",
        label: "completed",
      },
      {
        value: "failed",
        label: "failed",
      },
    ]);

    const columns = [
      {
        title: "Mã đơn hàng",
        dataIndex: "_id",
        key: "id",
        width: 250,
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

    async function getAllOrders() {
      try {
        const response = await axios.get(
          "http://127.0.0.1:8000/api/orders?status=" + status.value,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        orders.value = response.data.data;
        firstPage_url.value = response.data.first_page_url;
        lastPage_url.value = response.data.last_page_url;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        lastPage.value = response.data.last_page;
        currentPage.value = response.data.current_page;
      } catch (error) {
        console.error(error);
      }
    }

    async function handleConfirmationPending(id) {
      try {
        const response = await axios.put(
          "http://127.0.0.1:8000/api/orders/" + id,
          undefined,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: response.data.message,
          showConfirmButton: false,
          timer: 1500,
        });
        getAllOrders();
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.response.data.message,
        });
      }
    }

    const goToNextPage = async (url) => {
      try {
        const response = await axios.get(url, {
          headers: { Authorization: `Bearer ${token.access_token}` },
        });
        orders.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
      } catch (error) {
        console.log(error);
      }
    };

    const goToPrevPage = async (url) => {
      try {
        const response = await axios.get(url, {
          headers: { Authorization: `Bearer ${token.access_token}` },
        });
        orders.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
      } catch (error) {
        console.log(error);
      }
    };

    const goToPage = async (number) => {
      try {
        const response = await axios.get(
          "http://127.0.0.1:8000/api/orders?status=" + status.value+"&page="+number,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        orders.value = response.data.data;
        firstPage_url.value = response.data.first_page_url;
        lastPage_url.value = response.data.last_page_url;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        lastPage.value = response.data.last_page;
        currentPage.value = response.data.current_page;
      } catch (error) {
        console.log(error);
      }
    };

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

    const filterOption = (input, option) => {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const showEditOrderStatusModal = (id, status) => {
      editOrderStatus.value = true;
      orderID.value = id;
      OrderStatus.value = status;
    };

    async function handleEditOrderStatus() {
      try {
        const response = await axios.put(
          "http://127.0.0.1:8000/api/orders/" + orderID.value,
          {status: OrderStatus.value},
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: response.data.message,
          showConfirmButton: false,
          timer: 1500,
        });
        getAllOrders();
        editOrderStatus.value = false
      } catch (error) {
        if(error.response.status==422){
          errors.value = error.response.data.errors;
        }
        else{
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
        }
      }
    }

    getAllOrders();

    return {
      orders,
      columns,
      nextPage_url,
      prevPage_url,
      firstPage_url,
      lastPage_url,
      lastPage,
      currentPage,
      goToNextPage,
      goToPrevPage,
      goToPage,
      filterOrders,
      formatDate,
      handleConfirmationPending,
      editOrderStatus,
      OrderStatus,
      filterOption,
      options,
      errors,
      showEditOrderStatusModal,
      handleEditOrderStatus,
    };
  },
});
</script>
