<template>
  <a-card title="Đơn đặt hàng" style="width: 100%">
    <div class="flex flex-row-reverse mb-4">
        <a-dropdown>
      <template #overlay>
        <a-menu>
          <a-menu-item key="1" @click="filterOrders('')"> Mặc định </a-menu-item>
          <a-menu-item key="2" @click="filterOrders('confirmationpending')"> Đang chờ xác nhận </a-menu-item>
          <a-menu-item key="3" @click="filterOrders('intransit')"> Đang vận chuyển </a-menu-item>
          <a-menu-item key="4" @click="filterOrders('failed')"> Giao hàng thất bại </a-menu-item>
          <a-menu-item key="5" @click="filterOrders('completed')"> Giao hàng thành công </a-menu-item>
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
        <template v-if="column.key == 'action'">
          <span
            v-if="record.status == 'confirmation pending'"
            class="text-primary mr-4 cursor-pointer"
            title="Xác nhận thành công"
          >
            <i class="fa-solid fa-user-check"></i>
          </span>
          <edit-button
            v-if="record.status != 'confirmation pending'"
            :to="{
              name: 'admin-categories-edit',
              params: { id: record._id },
            }"
          ></edit-button>
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
  </a-card>
</template>

<script>
import { useMenu } from "../../../stores/use-menu";
import { defineComponent, ref } from "vue";
import EditButton from "../../../components/admin/buttons/EditButton.vue";

export default defineComponent({
  components: { EditButton },
  setup() {
    useMenu().onSelectedKeys(["admin-orders"]);

    const orders = ref([]);
    const nextPage_url = ref("");
    const prevPage_url = ref("");
    const firstPage_url = ref("");
    const lastPage_url = ref("");
    const lastPage = ref("");
    const currentPage = ref("");
    const status = ref('');
    const token = JSON.parse(localStorage.getItem("token"));

    const columns = [
      {
        title: "Mã đơn hàng",
        dataIndex: "_id",
        key: "id",
        fixed: "left",
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
        title: "Thao tác",
        key: "action",
        width: 100,
        fixed: "right",
      },
    ];

    async function getAllOrders() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/orders?status="+status.value, {
          headers: { Authorization: `Bearer ${token.access_token}` },
        });
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
          "http://127.0.0.1:8000/api/orders?status="+status.value+"?page=" + number,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        orders.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
      } catch (error) {
        console.log(error);
      }
    };

    function filterOrders(statusFilter){
        status.value = statusFilter
        getAllOrders()
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
      filterOrders
    };
  },
});
</script>
