<template>
  <section class="overflow-hidden bg-white py-2 font-poppins dark:bg-gray-800">
    <div class="max-w-6xl px-4 py-2 mx-auto lg:py-2 md:px-6">
      <div class="flex flex-wrap -mx-4">
        <div class="w-full px-4 md:w-1/2">
          <div class="sticky top-0 z-50 overflow-hidden">
            <div class="relative flex mb-6 lg:mb-2 lg:h-2/4">
              <div class="flex-wrap flex-col mr-2 hidden md:flex">
                <div
                  class="border-slate-200 border-2 mb-2 hover:cursor-pointer"
                >
                  <img
                    class="w-40"
                    :src="subsrc"
                    @click="changeImg(thumbnail)"
                  />
                </div>
                <div v-for="(item, index) in images" v-bind:key="index">
                  <div
                    class="border-slate-200 border-2 mb-2 hover:cursor-pointer"
                  >
                    <detail-img
                      :path="'products/' + item.filename"
                      @click="changeImg(item.filename)"
                    ></detail-img>
                  </div>
                </div>
              </div>
              <a-image :width="600" v-bind:src="src" />
            </div>
          </div>
        </div>
        <div class="w-full px-4 md:w-1/2">
          <div class="lg:pl-20">
            <div class="mb-8">
              <h2
                class="max-w-xl mt-2 mb-6 text-xl font-bold dark:text-gray-400 md:text-4xl"
              >
                {{ productname }}
              </h2>
              <p
                class="inline-block mb-8 text-4xl font-bold text-gray-700 dark:text-gray-400"
              >
                <span class="text-primary"
                  >{{ sellingprice.toLocaleString() }}đ</span
                >
                <span
                  v-if="originalprice != sellingprice"
                  class="text-base font-normal text-gray-500 line-through dark:text-gray-400"
                  >{{ originalprice.toLocaleString() }}đ</span
                >
              </p>
            </div>
            <div class="flex items-center mb-8">
              <h2 class="w-16 text-xl font-bold dark:text-gray-400">Size:</h2>
              <div class="flex flex-wrap -mx-2 -mb-2">
                <div v-for="(item, index) in sizes" v-bind:key="index">
                  <div v-if="item.quantity > 0" class="">
                    <button
                      @click="handleChangeSize(item.size, item.quantity)"
                      :class="{
                        'py-1 mb-2 mr-1 border w-11 text-white dark:border-gray-400 dark:hover:border-gray-300 dark:text-gray-400 bg-primary hover:text-white':
                          item.size === sizeSelected,
                        'py-1 mb-2 mr-1 border w-11 hover:border-blue-400 dark:border-gray-400 hover:text-blue-600 dark:hover:border-gray-300 dark:text-gray-400': true,
                      }"
                    >
                      {{ item.size }}
                    </button>
                  </div>
                  <div v-if="item.quantity == 0" class="">
                    <button
                      class="py-1 mb-2 mr-1 border w-11 border-gray-400 text-gray-400 bg-gray-200 cursor-default"
                    >
                      {{ item.size }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="w-32 mb-8">
              <label
                for=""
                class="w-full text-xl font-semibold text-gray-700 dark:text-gray-400"
                >Số lượng</label
              >
              <div
                class="relative flex flex-row w-full h-10 mt-4 bg-transparent rounded-lg"
              >
                <button
                  @click="handleMinusQuantity"
                  class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400"
                >
                  <span class="m-auto text-2xl font-thin">-</span>
                </button>
                <input
                  type="number"
                  class="no-spinner flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-400 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md hover:text-black"
                  v-model="quantity"
                  @change="handleChangeSizeWithInput"
                />
                <button
                  @click="handlePlusQuantity"
                  class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400"
                >
                  <span class="m-auto text-2xl font-thin">+</span>
                </button>
              </div>
            </div>

            <div class="w-full mb-4 lg:w-full lg:mb-0">
              <button
                @click="handleAddToCart"
                class="flex items-center justify-center w-full p-4 text-white border border-primary bg-primary rounded-md dark:text-gray-200 dark:border-primary hover:bg-blue-700 hover:border-primary-dark hover:text-white-dark"
              >
                Thêm vào giỏ hàng <i class="ml-2 fa-solid fa-cart-plus"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container w-full">
    <section class="bg-white dark:bg-gray-900 antialiased flex">
      <div class="w-1/2 px-4">
        <div class="flex justify-between items-center mb-6">
          <h2
            class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white"
          >
            Bình luận ({{ comments.length }})
          </h2>
        </div>
        <div class="mb-6">
          <div
            class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700"
          >
            <textarea
              v-model="commentText"
              rows="3"
              class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
              placeholder="Viết bình luận..."
            ></textarea>
          </div>
          <div class="flex items-center justify-between">
            <button
              @click="handlePostComment"
              class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800"
            >
              Thêm bình luận
            </button>
            <a-dropdown>
              <template #overlay>
                <a-menu>
                  <a-menu-item key="1" @click="sortConmments('')">
                    Mặc định
                  </a-menu-item>
                  <a-menu-item key="2" @click="sortConmments('like')">
                    Nhiều like nhất
                  </a-menu-item>
                  <a-menu-item key="3" @click="sortConmments('date')">
                    Gần đây nhất
                  </a-menu-item>
                </a-menu>
              </template>
              <a-button>
                Sắp xếp theo<i class="ml-2 fa-solid fa-chevron-down"></i>
              </a-button>
            </a-dropdown>
          </div>
        </div>
        <div v-for="comment in comments" v-bind:key="comment._id">
          <article
            v-if="!comment.reply"
            class="p-6 mb-3 text-base bg-white border-t border-gray-200 dark:border-gray-700 dark:bg-gray-900"
          >
            <footer class="flex justify-between items-center mb-2">
              <div class="flex items-center">
                <p
                  class="inline-flex items-center text-sm mr-3 text-gray-900 dark:text-white font-semibold"
                >
                  <avatar-user :path="'users/' + comment.avatar"></avatar-user
                  >{{ comment.userfullname }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  <span>{{ formatTimeDifference(comment.date) }}</span>
                </p>
              </div>
            </footer>
            <p class="text-gray-500 dark:text-gray-400">
              {{ comment.text }}
            </p>
            <div class="flex items-center mt-4 space-x-4">
              <button
                type="button"
                class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium"
              >
                <span
                  @click="handleLike(comment._id)"
                  :class="{
                    'text-red': comment.likes.some(
                      (item) => item.user === userid
                    ),
                    'mr-1': true,
                  }"
                  ><i class="fa-solid fa-heart hover:no-underline"></i
                ></span>
                {{ comment.likecount }} lượt thích
              </button>
              <button
                @click="handleReply(comment._id)"
                type="button"
                class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium"
              >
                <span class="mr-1"
                  ><i class="fa-solid fa-comment-dots"></i
                ></span>
                Trả lời
              </button>
              <button
                v-if="comment.user == userid"
                @click="showModal(comment.text, comment._id)"
                type="button"
                class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium"
              >
                <span class="mr-1"><i class="fa-solid fa-pen"></i></span>
                Sửa
              </button>
              <button
                @click="handleDeleteComment(comment._id)"
                v-if="comment.user == userid"
                type="button"
                class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium"
              >
                <span class="mr-1"><i class="fa-solid fa-trash"></i></span>
                Xóa
              </button>
            </div>
            <div v-for="reply in comments" v-bind:key="reply._id">
              <article
                v-if="reply.reply && reply.reply == comment._id"
                class="px-6 pt-4 mt-2 ml-6 lg:ml-12 text-base bg-white rounded-lg dark:bg-gray-900"
              >
                <footer class="flex justify-between items-center mb-2">
                  <div class="flex items-center">
                    <p
                      class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold"
                    >
                      <avatar-user :path="'users/' + reply.avatar"></avatar-user
                      >{{ reply.userfullname }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      <span>{{ formatTimeDifference(reply.date) }}</span>
                    </p>
                  </div>
                </footer>
                <p class="text-gray-500 dark:text-gray-400">
                  {{ reply.text }}
                </p>
                <div class="flex items-center mt-4 space-x-4">
                  <button
                    type="button"
                    class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium"
                  >
                    <span
                      @click="handleLike(reply._id)"
                      :class="{
                        'text-red': reply.likes.some(
                          (item) => item.user === userid
                        ),
                        'mr-1': true,
                      }"
                      ><i class="fa-solid fa-heart hover:no-underline"></i
                    ></span>
                    {{ reply.likecount }} lượt thích
                  </button>
                  <button
                    @click="handleReply(comment._id)"
                    type="button"
                    class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium"
                  >
                    <span class="mr-1"
                      ><i class="fa-solid fa-comment-dots"></i
                    ></span>
                    Trả lời
                  </button>
                  <button
                    @click="showModal(reply.text, reply._id)"
                    v-if="reply.user == userid"
                    type="button"
                    class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium"
                  >
                    <span class="mr-1"><i class="fa-solid fa-pen"></i></span>
                    Sửa
                  </button>

                  <button
                    @click="handleDeleteComment(reply._id)"
                    v-if="reply.user == userid"
                    type="button"
                    class="flex items-center text-sm text-gray-500 dark:text-gray-400 font-medium"
                  >
                    <span class="mr-1"><i class="fa-solid fa-trash"></i></span>
                    Xóa
                  </button>
                </div>
              </article>
            </div>
            <div v-if="replyInput == comment._id" class="flex items-center">
              <div
                class="w-4/5 py-2 mt-2 px-4 ml-6 lg:ml-12 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700"
              >
                <textarea
                  v-model="replyText"
                  rows="1"
                  class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                  placeholder="Viết bình luận..."
                ></textarea>
              </div>
              <a-button
                @click="handlePostReply(comment._id)"
                class="mt-2 ml-2 bg-primary text-white"
                >Đăng</a-button
              >
            </div>
          </article>
        </div>
      </div>
      <a-tabs class="w-1/2 px-4" v-model:activeKey="activeKey">
        <a-tab-pane key="1" tab="Bảng size"
          ><a-image :width="600" src="../../src/assets/imgs/bangsize.webp"
        /></a-tab-pane>
        <a-tab-pane key="2" tab="Chính sách bán hàng"
          ><ban-hang></ban-hang
        ></a-tab-pane>
        <a-tab-pane key="3" tab="Thông tin bảo quản"
          ><bao-quan></bao-quan
        ></a-tab-pane>
        <a-tab-pane key="4" tab="Chính sách vận chuyển"
          ><van-chuyen></van-chuyen
        ></a-tab-pane>
      </a-tabs>
      <a-modal
        cancelText="Hủy"
        :okButtonProps="{ style: { backgroundColor: '#3060FF' } }"
        v-model:visible="visible"
        title="Sửa bình luận"
        @ok="handleEditComment()"
      >
        <div
          class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700"
        >
          <textarea
            v-model="editText"
            rows="3"
            class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
            placeholder="Viết bình luận..."
          ></textarea>
        </div>
      </a-modal>
    </section>
  </div>
  <de-xuat></de-xuat>
</template>

<script>
import { defineComponent, ref, reactive, toRefs } from "vue";
import { storage } from "../firebase";
import {
  ref as fbref,
  getDownloadURL,
} from "firebase/storage";
import { useRouter, useRoute } from "vue-router";
import DetailImg from "../components/user/DetailImg.vue";
import AvatarUser from "../components/user/AvatarUser.vue";
import BanHang from "../components/user/ChinhSachBanHang.vue";
import VanChuyen from "../components/user/ChinhSachVanChuyen.vue";
import BaoQuan from "../components/user/BaoQuan.vue";
import DeXuat from "../components/user/DeXuat.vue";
export default defineComponent({
  components: {
    DetailImg,
    AvatarUser,
    BanHang,
    BaoQuan,
    VanChuyen,
    DeXuat,
  },
  setup() {
    const src = ref("https://static.thenounproject.com/png/2616533-200.png");
    const subsrc = ref("https://static.thenounproject.com/png/2616533-200.png");
    // const productname = ref("")
    // const originalprice = ref("")
    // const sellingprice = ref("")
    const product = reactive({
      productname: "",
      originalprice: "",
      sellingprice: "",
      thumbnail: "",
      images: [],
      sizes: [],
    });

    const route = useRoute();
    const router = useRouter();

    const quantity = ref(0);
    const maxQuantity = ref(0);
    const sizeSelected = ref("");

    const activeKey = ref("1");

    const comments = ref([]);
    const orderComments = ref("");
    const commentText = ref("");
    const replyText = ref("");
    const replyInput = ref("");
    const editText = ref("");
    const editId = ref("");

    const visible = ref(false);

    const userid = ref("");
    const token = JSON.parse(localStorage.getItem("token"));

    const showModal = (text, id) => {
      visible.value = true;
      editText.value = text;
      editId.value = id;
    };

    async function getProductByID() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/products/get/${route.params.id}`
        );
        product.productname = response.data.productname;
        product.thumbnail = response.data.thumbnail;
        product.images = response.data.images;
        product.sellingprice = response.data.sellingprice;
        product.originalprice = response.data.originalprice;
        product.sizes = response.data.sizes;
        getDownloadURL(
          fbref(storage, "products/" + response.data.thumbnail)
        ).then(
          (download_url) => (
            (src.value = download_url), (subsrc.value = download_url)
          )
        );
      } catch (error) {
        if (error.response.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
          router.push({ name: "shop" });
        }
        console.log(error);
      }
    }

    async function getCommentsByProductID() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/comments/${route.params.id}?order=${orderComments.value}`
        );
        comments.value = response.data;
      } catch (error) {
        if (error.response.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
        }
      }
    }

    const sortConmments = (value) => {
      orderComments.value = value;
      getCommentsByProductID();
    };

    async function handlePostComment() {
      if (token) {
        try {
          const response = await axios.post(
            `http://127.0.0.1:8000/api/comments/${route.params.id}`,
            { text: commentText.value },
            {
              headers: { Authorization: `Bearer ${token.access_token}` },
            }
          );
          commentText.value = "";
          orderComments.value = "date";
          getCommentsByProductID();
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
        }
      } else {
        Swal.fire({
          title: "Chưa đăng nhập?",
          text: "Vui lòng đăng nhập để thực hiện",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Đăng nhập ngay!",
          cancelButtonText: "Đóng",
        }).then((result) => {
          if (result.isConfirmed) {
            router.push({ name: "login" });
          }
        });
      }
    }

    async function handleEditComment() {
      if (token) {
        try {
          const response = await axios.put(
            `http://127.0.0.1:8000/api/comments/${editId.value}`,
            { text: editText.value },
            {
              headers: { Authorization: `Bearer ${token.access_token}` },
            }
          );
          editText.value = "";
          replyInput.value = "";
          visible.value = false;
          getCommentsByProductID();
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
        }
      } else {
        Swal.fire({
          title: "Chưa đăng nhập?",
          text: "Vui lòng đăng nhập để thực hiện",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Đăng nhập ngay!",
          cancelButtonText: "Đóng",
        }).then((result) => {
          if (result.isConfirmed) {
            router.push({ name: "login" });
          }
        });
      }
    }

    async function handlePostReply(id) {
      if (token) {
        try {
          const response = await axios.post(
            `http://127.0.0.1:8000/api/comments/${route.params.id}`,
            { text: replyText.value, reply: id },
            {
              headers: { Authorization: `Bearer ${token.access_token}` },
            }
          );
          replyText.value = "";
          orderComments.value = "date";
          getCommentsByProductID();
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
        }
      } else {
        Swal.fire({
          title: "Chưa đăng nhập?",
          text: "Vui lòng đăng nhập để thực hiện",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Đăng nhập ngay!",
          cancelButtonText: "Đóng",
        }).then((result) => {
          if (result.isConfirmed) {
            router.push({ name: "login" });
          }
        });
      }
    }

    async function handleLike(id) {
      if (token) {
        try {
          const response = await axios.put(
            `http://127.0.0.1:8000/api/comments/likes/${id}`,
            undefined,
            {
              headers: { Authorization: `Bearer ${token.access_token}` },
            }
          );
          getCommentsByProductID();
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.response.data.message,
          });
        }
      } else {
        Swal.fire({
          title: "Chưa đăng nhập?",
          text: "Vui lòng đăng nhập để thực hiện",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Đăng nhập ngay!",
          cancelButtonText: "Đóng",
        }).then((result) => {
          if (result.isConfirmed) {
            router.push({ name: "login" });
          }
        });
      }
    }

    async function handleReply(id) {
      if (token) {
        replyInput.value = id;
      } else {
        Swal.fire({
          title: "Chưa đăng nhập?",
          text: "Vui lòng đăng nhập để thực hiện",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Đăng nhập ngay!",
          cancelButtonText: "Đóng",
        }).then((result) => {
          if (result.isConfirmed) {
            router.push({ name: "login" });
          }
        });
      }
    }

    function handleDeleteComment(id) {
      Swal.fire({
        icon: "question",
        title: "Bạn chắc chứ?",
        text: "Dữ liệu sẽ mất và không thể khôi phục nếu bạn xóa nó!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Vâng, đồng ý!",
        cancelButtonText: "Hủy",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`http://127.0.0.1:8000/api/comments/${id}`, {
              headers: { Authorization: `Bearer ${token.access_token}` },
            })
            .then((response) => {
              if (response.data.status == 200) {
                getCommentsByProductID();
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: response.data.message,
                });
              }
            })
            .catch((error) => {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error.response.data.message,
              });
            });
        }
      });
    }

    function formatTimeDifference(date) {
      const ngayNhapVao = new Date(date);
      const ngayHienTai = new Date();
      const khoangCach = ngayHienTai - ngayNhapVao;
      const khoangCachGiay = Math.floor(khoangCach / 1000);

      if (khoangCachGiay < 60) {
        return khoangCachGiay + " giây trước";
      } else if (khoangCachGiay < 3600) {
        const phut = Math.floor(khoangCachGiay / 60);
        return phut + " phút trước";
      } else if (khoangCachGiay < 86400) {
        const gio = Math.floor(khoangCachGiay / 3600);
        return gio + " giờ trước";
      } else if (khoangCachGiay < 2592000) {
        const ngay = Math.floor(khoangCachGiay / 86400);
        return ngay + " ngày trước";
      } else {
        return ngayNhapVao.toLocaleDateString(); // Nếu quá 30 ngày, hiển thị ngày đầy đủ
      }
    }

    async function getUserFromToken() {
      if (token) {
        try {
          const response = await axios.get(
            `http://127.0.0.1:8000/api/users/token`,
            {
              headers: { Authorization: `Bearer ${token.access_token}` },
            }
          );
          userid.value = response.data._id;
        } catch (error) {
          if (error.response.status == 404) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
          }
          console.log(error);
        }
      }
    }

    const changeImg = (value) => {
      getDownloadURL(fbref(storage, "products/" + value)).then(
        (download_url) => (src.value = download_url)
      );
    };

    const handleMinusQuantity = () => {
      quantity.value = quantity.value <= 0 ? 0 : quantity.value - 1;
    };

    const handleChangeSize = (size, quantity) => {
      maxQuantity.value = quantity;
      sizeSelected.value = size;
    };

    const handlePlusQuantity = () => {
      quantity.value =
        quantity.value >= maxQuantity.value
          ? maxQuantity.value
          : quantity.value + 1;
      if (maxQuantity.value == 0) {
        Swal.fire({
          title: "Vui lòng chọn size trước",
          confirmButtonColor: "#3060FF",
        });
      }
    };

    const handleChangeSizeWithInput = () => {
      quantity.value = quantity.value <= 0 ? 0 : quantity.value - 1;
      quantity.value =
        quantity.value >= maxQuantity.value
          ? maxQuantity.value
          : quantity.value + 1;
    };

    async function handleAddToCart() {
      if (token) {
        if (sizeSelected.value && quantity.value > 0) {
          try {
            const response = await axios.post(
              `http://127.0.0.1:8000/api/carts`,
              {
                product: Number(route.params.id),
                size: sizeSelected.value,
                quantity: quantity.value,
              },
              {
                headers: { Authorization: `Bearer ${token.access_token}` },
              }
            );
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Đã thêm vào giỏ hàng",
              showConfirmButton: false,
              timer: 1500,
            });
          } catch (error) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
          }
        } else {
          Swal.fire({
            title: "Vui lòng chọn size và số lượng",
            confirmButtonColor: "#3060FF",
          });
        }
      } else {
        Swal.fire({
          title: "Chưa đăng nhập?",
          text: "Vui lòng đăng nhập để thực hiện",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Đăng nhập ngay!",
          cancelButtonText: "Đóng",
        }).then((result) => {
          if (result.isConfirmed) {
            router.push({ name: "login" });
          }
        });
      }
    }

    function scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    }

    getProductByID();
    getCommentsByProductID();
    getUserFromToken();
    scrollToTop();

    return {
      src,
      subsrc,
      ...toRefs(product),
      changeImg,
      quantity,
      handleMinusQuantity,
      handlePlusQuantity,
      handleChangeSize,
      sizeSelected,
      handleChangeSizeWithInput,
      activeKey,
      comments,
      userid,
      handleLike,
      formatTimeDifference,
      sortConmments,
      commentText,
      replyText,
      replyInput,
      handlePostComment,
      handleDeleteComment,
      handleReply,
      handlePostReply,
      showModal,
      visible,
      editText,
      handleEditComment,
      handleAddToCart,
    };
  },
});
</script>
