<template>
  <form @submit.prevent="createNewCategory">
    <a-card title="Thêm size sản phẩm mới" style="width: 100%">
        <div class="w-full">
        <div class="w-full pb-4">
          <label
            for=""
            :class="{
              'text-red': errors.size,
              'min-w-label inline-block': true,
            }"
            >Size:
          </label>
          <a-input
            placeholder="Nhập vào tên size..."
            allow-clear
            style="width: 40%"
            v-model:value="size"
            :class="{ 'border-1 border-rose-600': errors.size }"
          />
          <small class="text-red ml-2" v-if="errors.size">{{
            errors.size[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            for=""
            :class="{
              'text-red': errors.quantity,
              'min-w-label inline-block': true,
            }"
            >Số lượng:
          </label>
          <a-input
            placeholder="Nhập vào số lượng..."
            allow-clear
            style="width: 40%"
            v-model:value="quantity"
            :class="{ 'border-1 border-rose-600': errors.quantity }"
          />
          <small class="text-red ml-2" v-if="errors.quantity">{{
            errors.quantity[0]
          }}</small>
        </div>
      </div>
      <div class="flex items-center justify-end w-full">
        <cancel-button
          :to="{
            name: 'admin-products-sizes',
            params: {id: $route.params.id }
          }"
        ></cancel-button>
        <save-button></save-button>
      </div>
    </a-card>
  </form>
</template>

<script>
import { useMenu } from "../../../../stores/use-menu";
import SaveButton from "../../../../components/admin/buttons/SaveButton.vue";
import CancelButton from "../../../../components/admin/buttons/CancelButton.vue";
import { defineComponent, ref, reactive, toRefs } from "vue";
import { useRouter, useRoute } from "vue-router";
export default defineComponent({
  components: {
    SaveButton,
    CancelButton,
  },
  setup() {
    useMenu().onSelectedKeys(["admin-products"]);
    const sizes = reactive({
      size: "",
      quantity: ""
    });
    const token = JSON.parse(localStorage.getItem("token"));

    const errors = ref({});
    const router = useRouter();
    const route = useRoute();

    const createNewCategory = () => {
      axios
        .post("http://127.0.0.1:8000/api/products/sizes/"+route.params.id, sizes, {
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
            router.push({ name: "admin-products-sizes", params: {id: route.params.id } });
          }
        })
        .catch(function (error) {
          if(error.response.status==404){
            Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: error.response.data.message,
            });
            errors.value = "";
          }
          else{
            errors.value = error.response.data.errors;
          }
        });
    };

    async function getProductByID() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/products/get/${route.params.id}`
        );
      } catch (error) {
        if(error.response.status==404){
          router.push({ name: "admin-products" });
        }
      }
    }

    getProductByID();

    return {
      createNewCategory,
      ...toRefs(sizes),
      errors,
    };
  },
});
</script>
