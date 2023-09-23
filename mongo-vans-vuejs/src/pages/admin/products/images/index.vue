<template>
  <a-card title="Thư viện ảnh sản phẩm" style="width: 100%">
    <a-table :columns="columns" :data-source="images" :pagination="false">
      <template #bodyCell="{ column, record, index }">
        <template v-if="column.key == 'order'">
          <span>{{ index + 1 }}</span>
        </template>
        <template v-if="column.key == 'images'">
          <cloud-image :path="'products/' + record.filename"></cloud-image>
        </template>
        <template v-if="column.key == 'action'">
          <delete-button @click="deleteData(record._id)"></delete-button>
        </template>
      </template>
    </a-table>

    <div class="flex justify-between mt-4 text-black font-[600]">
      <add-button
        :to="{
          name: 'admin-products-images-add',
        }"
      ></add-button>
    </div>
  </a-card>
</template>

<script>
import { useMenu } from "../../../../stores/use-menu";
import { defineComponent, ref } from "vue";
import DeleteButton from "../../../../components/admin/buttons/DeleteButton.vue";
import AddButton from "../../../../components/admin/buttons/AddButton.vue";
import { storage } from "../../../../firebase";
import { uploadBytes, ref as fbref } from "firebase/storage";
import CloudImage from "../../../../components/admin/CloudImage.vue";
import { useRoute, useRouter } from "vue-router";

export default defineComponent({
  components: { DeleteButton, AddButton, CloudImage },
  setup() {
    useMenu().onSelectedKeys(["admin-products"]);

    const images = ref([]);
    const token = JSON.parse(localStorage.getItem("token"));
    const route = useRoute();
    const router = useRouter();

    const columns = [
      {
        title: "#",
        dataIndex: "",
        key: "order",
        width: 50,
      },
      {
        title: "Tên file",
        dataIndex: "filename",
        key: "filename",
        width: 150,
      },
      {
        title: "Hình ảnh",
        dataIndex: "filename",
        key: "images",
        width: 100,
      },
      {
        title: "Thao tác",
        key: "action",
        width: 100,
      },
    ];

    async function getAllProductImages() {
      try {
        const url =
          "http://127.0.0.1:8000/api/products/images/" + route.params.id;
        const response = await axios.get(url);
        images.value = response.data;
      } catch (error) {
        if (error.response.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
          router.push({
            name: "admin-products"
          });
        }
      }
    }

    function deleteData(id) {
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
            .delete(`http://127.0.0.1:8000/api/products/${id}`, {
              headers: { Authorization: `Bearer ${token.access_token}` },
            })
            .then((response) => {
              if (response.data.status == 200) {
                Swal.fire("Xóa thành công!", response.data.message, "success");
                getAllProducts(keyword);
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
                text: error.message,
              });
            });
        }
      });
    }

    getAllProductImages();

    return {
      images,
      columns,
      deleteData,
    };
  },
});
</script>
