<template>
  <a-card title="Size sản phẩm" style="width: 100%">
    <a-table :columns="columns" :data-source="sizes" :pagination="false">
      <template #bodyCell="{ column, record, index }">
        <template v-if="column.key == 'order'">
          <span>{{ index + 1 }}</span>
        </template>
        <template v-if="column.key == 'action'">
          <edit-button
            :to="{
              name: 'admin-products-edit',
              params: { id: record._id },
            }"
          ></edit-button>
          <delete-button @click="deleteData($route.params.id, record.size)"></delete-button>
        </template>
      </template>
    </a-table>

    <div class="flex justify-between mt-4 text-black font-[600]">
      <add-button
        :to="{
          name: 'admin-products-sizes-add',
        }"
      ></add-button>
    </div>
  </a-card>
</template>

<script>
import { useMenu } from "../../../../stores/use-menu";
import { defineComponent, ref } from "vue";
import DeleteButton from "../../../../components/admin/buttons/DeleteButton.vue";
import EditButton from "../../../../components/admin/buttons/EditButton.vue";
import AddButton from "../../../../components/admin/buttons/AddButton.vue";
import { useRoute } from "vue-router";

export default defineComponent({
  components: { DeleteButton, EditButton, AddButton },
  setup() {
    useMenu().onSelectedKeys(["admin-products"]);

    const sizes = ref([]);
    const token = JSON.parse(localStorage.getItem("token"));
    const route = useRoute();

    const columns = [
      {
        title: "#",
        dataIndex: "",
        key: "order",
        width: 50,
      },
      {
        title: "Size",
        dataIndex: "size",
        key: "size",
        width: 150,
      },
      {
        title: "Số lượng",
        dataIndex: "quantity",
        key: "quantity",
        width: 150,
      },
      {
        title: "Thao tác",
        key: "action",
        width: 100,
      },
    ];

    async function getAllProductSizes() {
      try {
        const url =
          "http://127.0.0.1:8000/api/products/sizes/" + route.params.id;
        const response = await axios.get(url);
        sizes.value = response.data;
      } catch (error) {
        console.error(error);
      }
    }

    function deleteData(id, size) {
      Swal.fire({
        title: "Bạn chắc chứ?",
        text: "Dữ liệu sẽ mất nếu bạn xóa nó!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Vâng, đồng ý!",
        cancelButtonText: "Hủy",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`http://127.0.0.1:8000/api/products/sizes/${id}?size=${size}`, {
              headers: { Authorization: `Bearer ${token.access_token}` },
            })
            .then((response) => {
              if (response.data.status == 200) {
                Swal.fire("Xóa thành công!", response.data.message, "success");
                getAllProductSizes();
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: response.data.message,
                });
              }
            })
            .catch((error) => {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error.response.data.message,
              });
            });
        }
      });
    }

    getAllProductSizes();

    return {
      sizes,
      columns,
      deleteData,
    };
  },
});
</script>
