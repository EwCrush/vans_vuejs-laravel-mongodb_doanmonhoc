<template>
  <img class="w-14" :src="url" />
</template>

<script>
import { storage } from "../../firebase";
import { getDownloadURL, ref } from "firebase/storage";
export default {
  props: {
    path: String,
  },
  data: () => {
    return { url: "../../src/assets/imgs/logo.webp" };
  },
  mounted(){
    getDownloadURL(ref(storage, this.path)).then(
        (download_url) => (this.url = download_url)
    )
  }
};
</script>
