<template>
  <div class="w-full pb-4">
    <label class="min-w-label inline-block">Tỉnh/Thành phố: </label>
    <a-select
      placeholder="Chọn tỉnh/thành phố, nhập tên để tìm kiếm..."
      style="width: 60%"
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
      style="width: 60%"
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
      style="width: 60%"
      show-search
      :options="wards"
      :field-names="{ label: 'name', value: 'code', options: 'wards' }"
      :filter-option="filterOption"
      v-model:value="WardCode"
      @change="changeWardName"
    />
  </div>
  <div class="w-full pb-4">
    <label class="min-w-label inline-block">Địa chỉ cụ thể: </label>
    <a-input
      v-model:value="DetailedAddress"
      placeholder="Nhập vào địa chỉ cụ thể như số nhà,..."
      allow-clear
      style="width: 60%"
      :disabled="!WardName"
      @change="ChangeDetailedAddress"
    />
  </div>
  <div class="w-full pb-4">
    <label class="min-w-label inline-block">Xem trước thay đổi: </label>
    <a-textarea
      disabled
      :auto-size="{ minRows: 1, maxRows: 5 }"
      v-model:value="address"
      style="width: 60%"
    />
  </div>
</template>
<script>
import { defineComponent, ref, watch } from "vue";

export default defineComponent({
    props: {
    address: String,
    default: ""
  },
  emits: ['address-changed'],
  setup(props, {emit}) {
    const provinces = ref([]);
    const districts = ref([]);
    const wards = ref([]);

    const ProvinceCode = ref();
    const DistrictCode = ref();
    const WardCode = ref();

    const ProvinceName = ref("");
    const DistrictName = ref("");
    const WardName = ref("");
    const DetailedAddress = ref("");

    const address = ref(props.address);

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
          ProvinceName.value = response.data.name;
          districts.value = response.data.districts;
          address.value = ProvinceName.value;
          DistrictCode.value = [];
          WardCode.value = [];
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
          DistrictName.value = response.data.name;
          wards.value = response.data.wards;
          address.value = DistrictName.value + ", " + ProvinceName.value;
          WardCode.value = [];
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
        WardName.value = response.data.name;
        address.value =
          WardName.value +
          ", " +
          DistrictName.value +
          ", " +
          ProvinceName.value;
      } catch (error) {
        console.error(error);
      }
    }

    async function ChangeDetailedAddress() {
        address.value =
        DetailedAddress.value +
        ", " +
        WardName.value +
        ", " +
        DistrictName.value +
        ", " +
        ProvinceName.value;
    }

    const filterOption = (input, option) => {
      return option.name.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    };

    watch(
      () => address.value,
      (newVal, oldVal) => {
        if (newVal !== oldVal) {
          address.value = newVal;
          emit('address-changed', newVal); // Emit the custom event
        }
      }
    );

    getAllProvinces();

    return{
        getAllProvinces,
      getAllDistricts,
      getAllWards,
      changeWardName,
      filterOption,
      ProvinceCode,
      DistrictCode,
      WardCode,
      ProvinceName,
      DistrictName,
      WardName,
      provinces,
      districts,
      wards,
      ChangeDetailedAddress,
      DetailedAddress,
      address
    }
  },
});
</script>
