<template>
  <span class="text-yellow mr-4 cursor-pointer" title="Chỉnh sửa">
    <router-link v-bind:to="to">
      <i class="fa-solid fa-pen-to-square"></i>
    </router-link>
  </span>
</template>
<script>
export default {
  props: {
    to: {
      type: Object,
      required: true,
    },
  },
};
</script>
