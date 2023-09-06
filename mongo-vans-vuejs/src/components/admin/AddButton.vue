<template>
    <div class="text-primary cursor-pointer" title="Thêm dữ liệu mới">
        <router-link v-bind:to="to">
          <i class="fa-solid fa-circle-plus p-4 text-lg"></i>
        </router-link>
      </div>
</template>

<script>
export default{
    props:{
        to: {
            type: Object,
            required: true
        }
    }
}
</script>