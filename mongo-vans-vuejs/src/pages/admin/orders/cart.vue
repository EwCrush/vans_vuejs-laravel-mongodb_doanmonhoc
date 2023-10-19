<template>
  <a-card title="Danh sách sản phẩm của đơn đặt hàng" style="width: 100%">
    <a-table
      :columns="columns"
      :data-source="items"
      :pagination="false"
      :scroll="{ x: 1500 }"
    >
      <template #bodyCell="{ column, record, index }">
        <template v-if="column.key == 'order'">
          <span>{{ index + 1 }}</span>
        </template>
        <template v-if="column.key == 'price'">
          <span>{{ record.price.toLocaleString() }}đ</span>
        </template>
        <template v-if="column.key == 'amount'">
          <span>{{ record.amount.toLocaleString() }}đ</span>
        </template>
        <template v-if="column.key == 'image'">
            <cloud-image :path="'products/' + record.image"></cloud-image>
        </template>
      </template>
    </a-table>
  </a-card>
</template>

<script>
import { useMenu } from "../../../stores/use-menu";
import { defineComponent, ref } from "vue";
import { useRoute } from "vue-router";
import CloudImage from "../../../components/admin/CloudImage.vue";

export default defineComponent({
  components: { CloudImage },
  setup() {
    useMenu().onSelectedKeys(["admin-orders"]);

    const items = ref([]);
    const token = JSON.parse(localStorage.getItem("token"));
    const route = useRoute();

    const columns = [
      {
        title: "#",
        dataIndex: "",
        key: "order",
        width: 20,
      },
      {
        title: "Tên sản phẩm",
        dataIndex: "productname",
        key: "productname",
        width: 100,
      },
      {
        title: "Hình ảnh",
        dataIndex: "image",
        key: "image",
        width: 50,
      },
      {
        title: "Size",
        dataIndex: "size",
        key: "size",
        width: 50,
      },
      {
        title: "Đơn giá",
        dataIndex: "price",
        key: "price",
        width: 50,
      },
      {
        title: "Số lượng",
        dataIndex: "quantity",
        key: "quantity",
        width: 25,
      },
      {
        title: "Thành tiền",
        dataIndex: "amount",
        key: "amount",
        width: 50,
        fixed:'right'
      },
    ];

    async function getAllOrders() {
      try {
        const response = await axios.get(
          "http://127.0.0.1:8000/api/orders/get/" + route.params.id,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        );
        items.value = response.data;
      } catch (error) {
        console.error(error);
      }
    }

    getAllOrders();

    return {
      items,
      columns,
    };
  },
});
</script>
