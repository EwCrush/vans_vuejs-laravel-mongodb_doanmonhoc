<template>
  <form @submit.prevent="UpdateCategory">
    <a-card title="Cập nhật loại sản phẩm" style="width: 100%">
      <div class="w-full">
        <label for="" :class="{ 'text-red': errors.categoryname, 'mr-2': true }"
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
import { useRoute } from "vue-router";
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

    const errors = ref({});
    const router = useRouter();
    const route = useRoute();
    const token = JSON.parse(localStorage.getItem("token"));

    const UpdateCategory = () => {
      axios
        .put(`http://127.0.0.1:8000/api/categories/${route.params.id}`, categories, {headers: { Authorization: `Bearer ${token.access_token}` }})
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
            //console.log(response);
          }
        })
        .catch(function (error) {
          errors.value = error.response.data.errors;
          console.log(error)
        });
    };

    async function getCategoryByID() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/categories/get/${route.params.id}`
        );
        categories.categoryname = response.data.categoryname;
      } catch (error) {
        if(error.response.status==404){
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
          router.push({ name: "admin-categories" });
        }
      }
    }

    getCategoryByID();

    return {
        UpdateCategory,
      ...toRefs(categories),
      errors,
    };
  },
});
</script>
