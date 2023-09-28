<template>
  <form @submit.prevent="createNewProduct">
    <a-card title="Thêm sản phẩm mới" style="width: 100%">
      <div class="w-full">
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.productname,
              'min-w-label inline-block': true,
            }"
            >Tên sản phẩm:
          </label>
          <a-input
            placeholder="Nhập vào tên sản phẩm..."
            allow-clear
            style="width: 40%"
            v-model:value="productname"
            :class="{ 'border-1 border-rose-600': errors.productname }"
          />
          <small class="text-red ml-2" v-if="errors.productname">{{
            errors.productname[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.originalprice,
              'min-w-label inline-block': true,
            }"
            >Giá bán:
          </label>
          <a-input
            placeholder="Nhập vào giá bán..."
            allow-clear
            style="width: 40%"
            v-model:value="originalprice"
            :class="{ 'border-1 border-rose-600': errors.originalprice }"
          />
          <small class="text-red ml-2" v-if="errors.originalprice">{{
            errors.originalprice[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.sellingprice,
              'min-w-label inline-block': true,
            }"
            >Giá sale:
          </label>
          <a-input
            placeholder="Nhập vào giá sale..."
            allow-clear
            style="width: 40%"
            v-model:value="sellingprice"
            :class="{ 'border-1 border-rose-600': errors.sellingprice }"
          />
          <small class="text-red ml-2" v-if="errors.sellingprice">{{
            errors.sellingprice[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.category_id,
              'min-w-label inline-block': true,
            }"
            >Loại sản phẩm chính:
          </label>
          <a-select
            allowClear
            placeholder="Chọn loại sản phẩm, nhập tên để tìm kiếm..."
            style="width: 40%"
            show-search
            :options="category"
            :filter-option="filterOption"
            v-model:value="category_id"
            :status="errors.category_id ? 'error' : ''"
          />
          <small class="text-red ml-2" v-if="errors.category_id">{{
            errors.category_id[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.subcategory_id,
              'min-w-label inline-block': true,
            }"
            >Loại sản phẩm phụ:
          </label>
          <a-select
            allowClear
            placeholder="Chọn loại sản phẩm, nhập tên để tìm kiếm..."
            style="width: 40%"
            show-search
            :options="subcategory"
            :filter-option="filterOption"
            v-model:value="subcategory_id"
            :status="errors.subcategory_id ? 'error' : ''"
          />
          <small class="text-red ml-2" v-if="errors.subcategory_id">{{
            errors.subcategory_id[0]
          }}</small>
        </div>
        <div class="w-full pb-4 flex items-center">
          <label
            :class="{
              'text-red': errors.filename,
              'min-w-label inline-block': true,
            }"
            >Hình ảnh:
          </label>
          <!-- :class="{ 'border-1 border-rose-600': errors.filename }" -->
          <div class="w-2/5">
            <input
              class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary"
              type="file"
              id="formFile"
              @change="imgChange"
              :class="{ 'border-1 border-rose-600': errors.filename }"
            />
          </div>
          <small class="text-red ml-2" v-if="errors.filename">{{
            errors.filename[0]
          }}</small>
        </div>
        <div class="ml-fromLabel">
          <a-image :width="200" v-bind:src="src" />
        </div>
      </div>
      <div class="flex items-center justify-end w-full">
        <cancel-button
          :to="{
            name: 'admin-products',
          }"
        ></cancel-button>
        <save-button></save-button>
      </div>
    </a-card>
  </form>
</template>

<script>
import { useMenu } from "../../../stores/use-menu";
import SaveButton from "../../../components/admin/buttons/SaveButton.vue";
import CancelButton from "../../../components/admin/buttons/CancelButton.vue";
import { defineComponent, ref, reactive, toRefs } from "vue";
import { useRouter } from "vue-router";
import { storage } from "../../../firebase";
import { uploadBytes, ref as fbref } from "firebase/storage";
export default defineComponent({
  components: {
    SaveButton,
    CancelButton,
  },
  setup() {
    useMenu().onSelectedKeys(["admin-products"]);
    const products = reactive({
      productname: "",
      originalprice: "",
      sellingprice: "",
      filename: "",
      category_id: [],
      subcategory_id: [],
    });
    const category = ref([]);
    const subcategory = ref([]);
    const token = JSON.parse(localStorage.getItem("token"));
    const src = ref("https://static.thenounproject.com/png/2616533-200.png");
    const file = ref();

    const errors = ref({});
    const router = useRouter();

    const createNewProduct = () => {
      axios
        .post("http://127.0.0.1:8000/api/products", products, {
          headers: { Authorization: `Bearer ${token.access_token}` },
        })
        .then(function (response) {
          if (file.value) {
            const storageRef = fbref(storage, "products/" + products.filename);
            uploadBytes(storageRef, file.value);
          }
          Swal.fire({
            position: "center",
            icon: "success",
            title: "Dữ liệu đã được lưu!",
            showConfirmButton: false,
            timer: 2000,
          });
          router.push({ name: "admin-products" });
        })
        .catch(function (error) {
          errors.value = error.response.data.errors;
        });
    };

    async function getAllCategories() {
      try {
        const response = await axios.get(
          "http://127.0.0.1:8000/api/categories/options"
        );
        category.value = response.data;
        subcategory.value = response.data;
      } catch (error) {
        console.error(error);
      }
    }

    const filterOption = (input, option) => {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    function imgChange(e) {
      const name = e.target.files[0].name;
      const d = new Date();
      const time = d.getTime();
      file.value = e.target.files[0];
      products.filename = time + "_" + name;
      src.value = window.URL.createObjectURL(e.target.files[0]);
    }

    getAllCategories();

    return {
      createNewProduct,
      ...toRefs(products),
      errors,
      getAllCategories,
      category,
      subcategory,
      src,
      imgChange,
      filterOption,
    };
  },
});
</script>
