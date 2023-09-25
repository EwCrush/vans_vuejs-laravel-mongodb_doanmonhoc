<template>
  <form @submit.prevent="createNewImage">
    <a-card title="Thêm hình ảnh sản phẩm mới" style="width: 100%">
      <div class="w-full">
        <div class="w-full pb-4 flex items-center">
          <label
            for=""
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
            />
          </div>
          <small class="text-red ml-2" v-if="errors.filename">{{
            errors.filename[0]
          }}</small>
        </div>
        <div class="ml-fromLabel">
          <a-image
          :width="200"
          v-bind:src="src"
        />
        </div>
      </div>
      <div class="flex items-center justify-end w-full">
        <cancel-button
          :to="{
            name: 'admin-products-images',
            params: { id: $route.params.id },
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
import { defineComponent, ref, reactive } from "vue";
import { useRouter, useRoute } from "vue-router";
import { storage } from "../../../../firebase";
import { uploadBytes, ref as fbref } from "firebase/storage";
export default defineComponent({
  components: {
    SaveButton,
    CancelButton,
  },
  setup() {
    useMenu().onSelectedKeys(["admin-products"]);

    const errors = ref({});
    const router = useRouter();
    const route = useRoute();
    const file = ref();
    const token = JSON.parse(localStorage.getItem("token"));
    const src = ref("https://static.thenounproject.com/png/2616533-200.png");
    const images = reactive({
      filename: "",
    });

    const createNewImage = () => {
      axios
        .post(
          "http://127.0.0.1:8000/api/products/images/" + route.params.id,
          images,
          {
            headers: { Authorization: `Bearer ${token.access_token}` },
          }
        )
        .then(function (response) {
          if (response) {
            const storageRef = fbref(storage, "products/" + images.filename);
            uploadBytes(storageRef, file.value).then(function () {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Dữ liệu đã được lưu!",
                showConfirmButton: false,
                timer: 2000,
              });
              router.push({
                name: "admin-products-images",
                params: { id: route.params.id },
              });
            });
          }
        })
        .catch(function (error) {
          if (error.response.status == 404) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
            errors.value = "";
          } else {
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
        if (error.response.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
          router.push({ name: "admin-products" });
        }
      }
    }

    function imgChange(e) {
      const name = e.target.files[0].name;
      const d = new Date();
      const time = d.getTime();
      file.value = e.target.files[0];
      images.filename = time + "_" + name;
      src.value = window.URL.createObjectURL(e.target.files[0])
    }

    getProductByID();

    return {
      createNewImage,
      errors,
      imgChange,
      src
    };
  },
});
</script>
