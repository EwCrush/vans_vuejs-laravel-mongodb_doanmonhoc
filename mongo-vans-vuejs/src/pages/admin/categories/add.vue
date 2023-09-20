<template>
  <form @submit.prevent="createNewCategory">
    <a-card title="Thêm loại sản phẩm mới" style="width: 100%">
      <div class="w-full">
        <div class="">
          <label
            for=""
            :class="{
              'text-red': errors.categoryname,
              'min-w-label inline-block': true,
            }"
            >Tên loại sản phẩm:
          </label>
          <a-input
            placeholder="Nhập vào tên loại sản phẩm..."
            allow-clear
            style="width: 40%"
            v-model:value="categoryname"
            :class="{ 'border-1 border-rose-600': errors.categoryname }"
          />
          <small class="text-red ml-2" v-if="errors.categoryname">{{
            errors.categoryname[0]
          }}</small>
        </div>
      </div>
      <div class="flex items-center justify-end w-full">
        <cancel-button
          :to="{
            name: 'admin-categories',
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
    useMenu().onSelectedKeys(["admin-categories"]);
    const categories = reactive({
      categoryname: "",
    });
    const token = JSON.parse(localStorage.getItem("token"));

    const errors = ref({});
    const router = useRouter();

    const createNewCategory = () => {
      axios
        .post("http://127.0.0.1:8000/api/categories", categories, {
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
            router.push({ name: "admin-categories" });
          }
        })
        .catch(function (error) {
          errors.value = error.response.data.errors;
          console.log(error);
        });
    };
    return {
      createNewCategory,
      ...toRefs(categories),
      errors,
    };
  },
});
</script>
