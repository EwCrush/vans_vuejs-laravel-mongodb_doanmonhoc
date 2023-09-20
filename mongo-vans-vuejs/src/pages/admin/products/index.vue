<template>
  <a-card title="Sản phẩm" style="width: 100%">
    <admin-search
      @click="searchData()"
      title="Nhập vào tên sản phẩm"
    ></admin-search>
    <a-table
      :columns="columns"
      :data-source="products"
      :pagination="false"
      :scroll="{ x: 1500 }"
    >
      <template #bodyCell="{ column, record }">
        <!-- <template v-if="column.key=='test'">
              <span>{{ categories[index].testobject == null ? "" : "123" }}</span>
          </template> -->
        <template v-if="column.key == 'originalprice'">
            <span>{{ (record.originalprice).toLocaleString() }}đ</span>
        </template>
        <template v-if="column.key == 'thumbnail'">
            <cloud-image :path="'products/'+record.thumbnail" :key="componentKey"></cloud-image>
            <!-- <img class="w-14" v-bind:src="'../../src/assets/imgs/products/'+ record.thumbnail" /> -->
        </template>
        <template v-if="column.key == 'sellingprice'">
            <span>{{ (record.sellingprice).toLocaleString() }}đ</span>
        </template>
        <template v-if="column.key == 'action'">
          <edit-button
            :to="{
              name: 'admin-products-edit',
              params: { id: record._id },
            }"
          ></edit-button>
          <delete-button @click="deleteData(record._id)"></delete-button>
        </template>
      </template>
    </a-table>

    <div class="flex justify-between mt-4 text-black font-[600]">
      <add-button
        :to="{
          name: 'admin-products-add',
        }"
      ></add-button>
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
import AdminSearch from "../../../components/admin/AdminSearch.vue";
import DeleteButton from "../../../components/admin/buttons/DeleteButton.vue";
import EditButton from "../../../components/admin/buttons/EditButton.vue";
import AddButton from "../../../components/admin/buttons/AddButton.vue";
import { storage } from "../../../firebase";
import { uploadBytes, ref as fbref } from "firebase/storage";
import CloudImage from "../../../components/admin/CloudImage.vue";

export default defineComponent({
  components: { AdminSearch, DeleteButton, EditButton, AddButton, CloudImage },
  setup() {
    useMenu().onSelectedKeys(["admin-products"]);

    const products = ref([]);
    const nextPage_url = ref("");
    const prevPage_url = ref("");
    const firstPage_url = ref("");
    const lastPage_url = ref("");
    const lastPage = ref("");
    const currentPage = ref("");
    const params = new URLSearchParams(window.location.search);
    const keyword = params.get("keyword");
    const token = JSON.parse(localStorage.getItem("token"));
    // const keywordFromInput = ref("");
    const componentKey = ref(0);

    const columns = [
      {
        title: "Mã sản phẩm",
        dataIndex: "_id",
        key: "id",
        fixed: "left",
        width: 120,
      },
      {
        title: "Tên sản phẩm",
        dataIndex: "productname",
        key: "productname",
        width: 250
      },
      {
        title: "Giá bán",
        dataIndex: "originalprice",
        key: "originalprice",
        width: 150,
      },
      {
        title: "Giá sale",
        dataIndex: "sellingprice",
        key: "sellingprice",
        width: 150,
      },
      {
        title: "Ảnh bìa",
        dataIndex: "thumbnail",
        key: "thumbnail",
        width: 100,
      },
      {
        title: "Loại sản phẩm chính",
        dataIndex: "category",
        key: "category",
        width: 180,
      },
      {
        title: "Loại sản phẩm phụ",
        dataIndex: "subcategory",
        key: "subcategory",
        width: 180,
      },
      {
        title: "Thao tác",
        key: "action",
        width: 150,
        fixed: "right",
      },
    ];

    async function getAllProducts(keyword) {
      try {
        const url =
          keyword === null || keyword === ""
            ? "http://127.0.0.1:8000/api/products"
            : "http://127.0.0.1:8000/api/products/search/" + keyword;
        const response = await axios.get(url);
        products.value = response.data.data;
        firstPage_url.value = response.data.first_page_url;
        lastPage_url.value = response.data.last_page_url;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        lastPage.value = response.data.last_page;
        currentPage.value = response.data.current_page;
        componentKey.value +=1;
      } catch (error) {
        console.error(error);
      }
    }

    const goToNextPage = async (url) => {
      try {
        const response = await axios.get(url);
        products.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
        componentKey.value +=1;
      } catch (error) {
        console.log(error);
      }
    };

    const goToPrevPage = async (url) => {
      try {
        const response = await axios.get(url);
        products.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
        componentKey.value +=1;
      } catch (error) {
        console.log(error);
      }
    };

    const goToPage = async (number) => {
      try {
        const url =
          keyword === null || keyword === ""
            ? "http://127.0.0.1:8000/api/products?page=" + number
            : "http://127.0.0.1:8000/api/products/search/" +
              keyword +
              "?page=" +
              number;
        const response = await axios.get(url);
        products.value = response.data.data;
        nextPage_url.value = response.data.next_page_url;
        prevPage_url.value = response.data.prev_page_url;
        currentPage.value = response.data.current_page;
        componentKey.value +=1;
      } catch (error) {
        console.log(error);
      }
    };

    function searchData() {
      const keyword_text = document
        .querySelector("#default-search")
        .value.trim();
      getAllProducts(keyword_text);
      var newurl =
        window.location.protocol +
        "//" +
        window.location.host +
        window.location.pathname +
        "?keyword=" +
        keyword_text;
      window.history.pushState({ path: newurl }, "", newurl);
      //keywordFromInput.value = keyword_text;
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

    getAllProducts(keyword);

    return {
      products,
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
      searchData,
      deleteData,
      componentKey
    };
  },
});
</script>
