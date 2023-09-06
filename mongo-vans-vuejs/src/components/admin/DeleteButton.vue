<template>
  <span @click="handleClick()" class="text-red cursor-pointer" title="Xóa">
    <i class="fa-solid fa-trash"></i>
  </span>
</template>

<script>
export default {
  methods: {
    handleClick(id) {
      this.$emit("click", id);
    },
  },
};
</script>
