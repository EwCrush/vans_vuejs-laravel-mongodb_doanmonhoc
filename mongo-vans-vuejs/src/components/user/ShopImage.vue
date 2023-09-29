<template>
    <img class="object-contain" :src="url" />
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
    mounted(){
      getDownloadURL(ref(storage, this.path)).then(
          (download_url) => (this.url = download_url)
      )
    }
  };
  </script>