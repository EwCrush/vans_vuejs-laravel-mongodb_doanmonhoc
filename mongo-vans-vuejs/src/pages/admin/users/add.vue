<template>
  <form @submit.prevent="createNewUser">
    <a-card title="Thêm người dùng mới" style="width: 100%">
      <div class="w-full">
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.fullname,
              'min-w-label inline-block': true,
            }"
            >Tên người dùng:
          </label>
          <a-input
            placeholder="Nhập vào tên người dùng..."
            allow-clear
            style="width: 40%"
            v-model:value="fullname"
            :class="{ 'border-1 border-rose-600': errors.fullname }"
          />
          <small class="text-red ml-2" v-if="errors.fullname">{{
            errors.fullname[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.username,
              'min-w-label inline-block': true,
            }"
            >Tên tài khoản:
          </label>
          <a-input
            placeholder="Nhập vào tên tài khoản..."
            allow-clear
            style="width: 40%"
            v-model:value="username"
            :class="{ 'border-1 border-rose-600': errors.username }"
          />
          <small class="text-red ml-2" v-if="errors.username">{{
            errors.username[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.email,
              'min-w-label inline-block': true,
            }"
            >Email:
          </label>
          <a-input
            placeholder="Nhập vào địa chỉ email..."
            allow-clear
            style="width: 40%"
            v-model:value="email"
            :class="{ 'border-1 border-rose-600': errors.email }"
          />
          <small class="text-red ml-2" v-if="errors.email">{{
            errors.email[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.phone,
              'min-w-label inline-block': true,
            }"
            >Số điện thoại:
          </label>
          <a-input
            placeholder="Nhập vào số điện thoại..."
            allow-clear
            style="width: 40%"
            v-model:value="phone"
            :class="{ 'border-1 border-rose-600': errors.phone }"
          />
          <small class="text-red ml-2" v-if="errors.phone">{{
            errors.phone[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label class="min-w-label inline-block">Tỉnh/Thành phố: </label>
          <a-select
            placeholder="Chọn tỉnh/thành phố, nhập tên để tìm kiếm..."
            style="width: 40%"
            show-search
            :options="provinces"
            :field-names="{
              label: 'name',
              value: 'code',
              options: 'provinces',
            }"
            :filter-option="filterOption"
            v-model:value="ProvinceCode"
            @change="getAllDistricts"
          />
        </div>
        <div class="w-full pb-4">
          <label class="min-w-label inline-block">Quận/Huyện: </label>
          <a-select
            placeholder="Chọn quận/huyện, nhập tên để tìm kiếm..."
            style="width: 40%"
            show-search
            :options="districts"
            :field-names="{
              label: 'name',
              value: 'code',
              options: 'districts',
            }"
            :filter-option="filterOption"
            v-model:value="DistrictCode"
            @change="getAllWards"
          />
        </div>
        <div class="w-full pb-4">
          <label class="min-w-label inline-block">Xã/Phường: </label>
          <a-select
            placeholder="Chọn xã/phường, nhập tên để tìm kiếm..."
            style="width: 40%"
            show-search
            :options="wards"
            :field-names="{ label: 'name', value: 'code', options: 'wards' }"
            :filter-option="filterOption"
            v-model:value="WardCode"
            @change="changeWardName"
          />
        </div>
        <div class="w-full pb-4">
          <label class="min-w-label inline-block">Vai trò: </label>
          <a-select
            placeholder="Chọn vai trò, nhập tên để tìm kiếm..."
            style="width: 40%"
            show-search
            :options="roles"
            :filter-option="RolefilterOption"
            v-model:value="role"
            allowClear
          />
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.password,
              'min-w-label inline-block': true,
            }"
            >Mật khẩu:
          </label>
          <a-input-password
            placeholder="Nhập vào mật khẩu..."
            allow-clear
            style="width: 40%"
            v-model:value="password"
            :class="{ 'border-1 border-rose-600': errors.password }"
          />
          <small class="text-red ml-2" v-if="errors.password">{{
            errors.password[0]
          }}</small>
        </div>
        <div class="w-full pb-4">
          <label
            :class="{
              'text-red': errors.password_confirmation,
              'min-w-label inline-block': true,
            }"
            >Xác nhận mật khẩu:
          </label>
          <a-input-password
            placeholder="Nhập lại mật khẩu để xác nhận..."
            allow-clear
            style="width: 40%"
            v-model:value="password_confirmation"
            :class="{ 'border-1 border-rose-600': errors.password_confirmation }"
          />
          <small class="text-red ml-2" v-if="errors.password_confirmation">{{
            errors.password_confirmation[0]
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
            name: 'admin-users',
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
    useMenu().onSelectedKeys(["admin-users"]);
    const users = reactive({
      fullname: "",
      address: "",
      username: "",
      email: "",
      phone: "",
      role: [],
      password: "",
      password_confirmation: "",
      filename: ""
    });
    const token = JSON.parse(localStorage.getItem("token"));
    const src = ref("https://static.thenounproject.com/png/2616533-200.png");
    const file = ref();

    const errors = ref({});
    const router = useRouter();

    const provinces = ref([]);
    const districts = ref([]);
    const wards = ref([]);

    const ProvinceCode = ref();
    const DistrictCode = ref();
    const WardCode = ref();

    const ProvinceName = ref("");
    const DistrictName = ref("");
    const WardName = ref("");

    const roles = ref([
      {
        value: 'admin',
        label: 'Admin',
      },
      {
        value: 'user',
        label: 'User',
      }
    ]);

    const createNewUser = () => {
      axios
        .post("http://127.0.0.1:8000/api/users", users, {
          headers: { Authorization: `Bearer ${token.access_token}` },
        })
        .then(function (response) {
          if (file.value) {
            const storageRef = fbref(storage, "users/" + users.filename);
            uploadBytes(storageRef, file.value);
          }
          Swal.fire({
            position: "center",
            icon: "success",
            title: "Dữ liệu đã được lưu!",
            showConfirmButton: false,
            timer: 2000,
          });
          router.push({ name: "admin-users" });
        })
        .catch(function (error) {
          errors.value = error.response.data.errors;
        });
    };

    async function getAllProvinces() {
      try {
        const response = await axios.get(
          "https://provinces.open-api.vn/api/?depth=1"
        );
        provinces.value = response.data;
      } catch (error) {
        console.error(error);
      }
    }

    async function getAllDistricts() {
      if (ProvinceCode.value) {
        try {
          const response = await axios.get(
            `https://provinces.open-api.vn/api/p/${ProvinceCode.value}?depth=2`
          );
          ProvinceName.value = response.data.name
          districts.value = response.data.districts;
          users.address = ProvinceName.value
          DistrictCode.value = []
          WardCode.value = []
        } catch (error) {
          console.error(error);
        }
      }
    }

    async function getAllWards() {
      if (DistrictCode.value) {
        try {
          const response = await axios.get(
            `https://provinces.open-api.vn/api/d/${DistrictCode.value}?depth=2`
          );
          DistrictName.value = response.data.name
          wards.value = response.data.wards;
          users.address =  DistrictName.value+", "+ProvinceName.value
          WardCode.value = []
        } catch (error) {
          console.error(error);
        }
      }
    }

    async function changeWardName() {
        try {
          const response = await axios.get(
            `https://provinces.open-api.vn/api/w/${WardCode.value}?depth=2`
          );
          WardName.value = response.data.name
          users.address =  WardName.value+", "+DistrictName.value+", "+ProvinceName.value
        } catch (error) {
          console.error(error);
        }
    }

    const filterOption = (input, option) => {
      return option.name.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    const RolefilterOption = (input, option) => {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    function imgChange(e) {
      const name = e.target.files[0].name;
      const d = new Date();
      const time = d.getTime();
      file.value = e.target.files[0];
      users.filename = time + "_" + name;
      src.value = window.URL.createObjectURL(e.target.files[0]);
    }

    getAllProvinces();

    return {
      createNewUser,
      ...toRefs(users),
      errors,
      provinces,
      districts,
      wards,
      src,
      imgChange,
      filterOption,
      RolefilterOption,
      getAllDistricts,
      getAllWards,
      changeWardName,
      ProvinceCode,
      DistrictCode,
      WardCode,
      ProvinceName,
      DistrictName,
      WardName,
      roles
    };
  },
});
</script>
