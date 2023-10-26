<template>
    <img
      class="h-16 w-16 rounded-full"
      :src="url"
    />
  </template>
  <script>
  import { storage } from "../../firebase";
  import { getDownloadURL, ref } from "firebase/storage";
  export default {
    props: {
      path: String,
    },
    data: () => {
      return { url: "https://static.thenounproject.com/png/2616533-200.png" };
    },
    mounted() {
      getDownloadURL(ref(storage, this.path)).then(
        (download_url) => (this.url = download_url)
      );
    },
  };
  </script>