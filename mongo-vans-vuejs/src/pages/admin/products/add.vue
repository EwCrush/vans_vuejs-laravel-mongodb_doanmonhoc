<template>
  <form @submit.prevent="createNewProduct">
    <a-card title="Thêm sản phẩm mới" style="width: 100%">
      <div class="w-full">
        <div class="w-full pb-4">
          <label
            for=""
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
            for=""
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
            for=""
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
            for=""
            :class="{
              'text-red': errors.sellingprice,
              'min-w-label inline-block': true,
            }"
            >Loại sản phẩm chính:
          </label>
          <a-select
            placeholder="Chọn loại sản phẩm, nhập ID để tìm..."
            style="width: 40%"
            show-search
            :options="category"
            :filter-option="null"
            :class="{ 'border-1 border-rose-600': errors.sellingprice }"
          />
          <small class="text-red ml-2" v-if="errors.sellingprice">{{
            errors.sellingprice[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            for=""
            :class="{
              'text-red': errors.sellingprice,
              'min-w-label inline-block': true,
            }"
            >Loại sản phẩm phụ:
          </label>
          <a-select
            placeholder="Chọn loại sản phẩm, nhập ID để tìm..."
            style="width: 40%"
            show-search
            :options="subcategory"
            :filter-option="null"
            :class="{ 'border-1 border-rose-600': errors.sellingprice }"
          />
          <small class="text-red ml-2" v-if="errors.sellingprice">{{
            errors.sellingprice[0]
          }}</small>
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
    });
    const category = ref([])
    const subcategory = ref([])
    const token = JSON.parse(localStorage.getItem("token"));

    const errors = ref({});
    const router = useRouter();

    const createNewProduct = () => {
      axios
        .post("http://127.0.0.1:8000/api/products", products, {
          headers: { Authorization: `Bearer ${token.access_token}` },
        })
        .then(function (response) {
          if (response) {
            Swal.fire({
              position: "center",
              icon: "success",
              title: "Dữ liệu đã được lưu!",
              showConfirmButton: false,
              timer: 2000,
            });
            router.push({ name: "admin-products" });
          }
        })
        .catch(function (error) {
          errors.value = error.response.data.errors;
          console.log(error);
        });
    };

    async function getAllCategories() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/categories/options');
        category.value = response.data
        subcategory.value = response.data
      } catch (error) {
        console.error(error);
      }
    }

    getAllCategories();

    return {
      createNewProduct,
      ...toRefs(products),
      errors,
      getAllCategories,
      category,
      subcategory
    };
  },
});
</script>
