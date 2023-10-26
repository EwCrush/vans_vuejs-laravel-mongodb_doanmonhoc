<template>
  <section class="bg-white dark:bg-gray-900">
    <div class="container flex items-center justify-center px-6 mx-auto">
      <form @submit.prevent="SignIn" class="w-full max-w-md">
        <!-- <div class="flex justify-center mx-auto">
          <img
            class="w-auto h-7 sm:h-8"
            src="../assets/imgs/logo.webp"
            alt=""
          />
        </div> -->

        <div class="flex items-center justify-center mt-6">
          <div
            class="w-1/3 pb-4 font-medium text-center text-gray-800 capitalize border-b-2 border-blue-500 dark:border-blue-400 dark:text-white"
          >
            <router-link :to="{ name: 'login' }">sign in</router-link>
          </div>
          <div
            class="w-1/3 pb-4 font-medium text-center text-gray-500 capitalize border-b dark:border-gray-400 dark:text-gray-300"
          >
            <router-link :to="{ name: 'register' }">sign up</router-link>
          </div>
        </div>

        <div class="relative flex items-center mt-8">
          <span class="absolute">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
              />
            </svg>
          </span>

          <input
            type="text"
            class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
            placeholder="Username"
            v-model="username"
          />
        </div>
        <small class="text-red ml-2" v-if="errors.username">{{
          errors.username[0]
        }}</small>

        <div class="relative flex items-center mt-4">
          <span class="absolute">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
              />
            </svg>
          </span>

          <input
            type="password"
            class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
            placeholder="Password"
            v-model="password"
          />
        </div>
        <small class="text-red ml-2" v-if="errors.password">{{
          errors.password[0]
        }}</small>

        <div class="mt-6 text-center flex flex-row-reverse justify-between">
          <!-- <div class="mb-[0.125rem] block min-h-[1.5rem] pl-[1.5rem]">
            <input
              class="relative float-left -ml-[1.5rem] mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
              type="checkbox"
              value=""
              id="exampleCheck2"
            />
            <label
              class="inline-block pl-[0.15rem] hover:cursor-pointer"
              for="exampleCheck2"
            >
              Remember me
            </label>
          </div> -->
          <div
            @click="showSendMailModal"
            class="text-blue-500 hover:underline dark:text-blue-400"
          >
            Forgot password?
          </div>
        </div>

        <div class="mt-6">
          <button
            type="submit"
            class="w-full px-6 py-3 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50"
          >
            SIGN IN
          </button>

          <div
            class="my-4 flex items-center before:mt-0.5 before:flex-1 before:border-t before:border-neutral-300 after:mt-0.5 after:flex-1 after:border-t after:border-neutral-300"
          >
            <p class="mx-4 mb-0 text-center dark:text-neutral-200">OR</p>
          </div>

          <div
            @click="SignInWithGoogle"
            class="flex items-center justify-center px-6 py-3 mt-4 text-gray-600 transition-colors duration-300 transform border rounded-lg dark:border-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer"
          >
            <svg class="w-6 h-6 mx-2" viewBox="0 0 40 40">
              <path
                d="M36.3425 16.7358H35V16.6667H20V23.3333H29.4192C28.045 27.2142 24.3525 30 20 30C14.4775 30 10 25.5225 10 20C10 14.4775 14.4775 9.99999 20 9.99999C22.5492 9.99999 24.8683 10.9617 26.6342 12.5325L31.3483 7.81833C28.3717 5.04416 24.39 3.33333 20 3.33333C10.7958 3.33333 3.33335 10.7958 3.33335 20C3.33335 29.2042 10.7958 36.6667 20 36.6667C29.2042 36.6667 36.6667 29.2042 36.6667 20C36.6667 18.8825 36.5517 17.7917 36.3425 16.7358Z"
                fill="#FFC107"
              />
              <path
                d="M5.25497 12.2425L10.7308 16.2583C12.2125 12.59 15.8008 9.99999 20 9.99999C22.5491 9.99999 24.8683 10.9617 26.6341 12.5325L31.3483 7.81833C28.3716 5.04416 24.39 3.33333 20 3.33333C13.5983 3.33333 8.04663 6.94749 5.25497 12.2425Z"
                fill="#FF3D00"
              />
              <path
                d="M20 36.6667C24.305 36.6667 28.2167 35.0192 31.1742 32.34L26.0159 27.975C24.3425 29.2425 22.2625 30 20 30C15.665 30 11.9842 27.2359 10.5975 23.3784L5.16254 27.5659C7.92087 32.9634 13.5225 36.6667 20 36.6667Z"
                fill="#4CAF50"
              />
              <path
                d="M36.3425 16.7358H35V16.6667H20V23.3333H29.4192C28.7592 25.1975 27.56 26.805 26.0133 27.9758C26.0142 27.975 26.015 27.975 26.0158 27.9742L31.1742 32.3392C30.8092 32.6708 36.6667 28.3333 36.6667 20C36.6667 18.8825 36.5517 17.7917 36.3425 16.7358Z"
                fill="#1976D2"
              />
            </svg>

            <span class="mx-2">Sign in with Google</span>
          </div>

          <div class="my-6 text-center">
            <div
              class="text-sm text-blue-500 hover:underline dark:text-blue-400"
            >
              <router-link :to="{ name: 'register' }"
                >Don't have an account yet? Sign up</router-link
              >
            </div>
          </div>
        </div>
      </form>
    </div>
    <a-modal
      v-model:visible="sendMailModal"
      cancelText="Hủy"
      title="Tìm tài khoản"
      :okButtonProps="{ style: { backgroundColor: '#3060FF' } }"
      @ok="sendMail"
    >
      <div class="w-full pb-4">
        <label
          :class="{
            'text-red': errors.email,
            'min-w-label inline-block': true,
          }"
          >Email của tài khoản:
        </label>
        <a-input
          placeholder="Nhập vào địa chỉ Email của tài khoản..."
          allow-clear
          style="width: 60%"
          v-model:value="email"
          :class="{ 'border-1 border-rose-600': errors.email }"
        />
        <small class="text-red ml-fromLabel block" v-if="errors.email">{{
          errors.email[0]
        }}</small>
      </div>
    </a-modal>
    <a-modal
      v-model:visible="enterOTPModal"
      cancelText="Hủy"
      title="Nhập mã OTP"
      :okButtonProps="{ style: { backgroundColor: '#3060FF' } }"
      @ok="sendOTP"
      width="280px"
    >
      <div class="flex flex-col justify-center items-center mb-8">
        <a-avatar :size="128" :src="avatar"></a-avatar>
        <span class="mt-4 font-medium">{{ fullname }}</span>
      </div>
      <div class="w-full pb-4">
        <a-input
          placeholder="Nhập vào mã OTP..."
          allow-clear
          style="width: 100%"
          v-model:value="otp"
          size="large"
        />
      </div>
    </a-modal>
    <a-modal
      cancelText="Hủy"
      :okButtonProps="{ style: { backgroundColor: '#3060FF' } }"
      v-model:visible="ResetPasswordModal"
      title="Đặt lại mật khẩu"
      @ok="ResetPassword"
    >
      <div class="w-full pb-4">
        <label
          :class="{
            'text-red': errors.newpassword,
            'min-w-label inline-block': true,
          }"
          >Mật khẩu mới:
        </label>
        <a-input-password
          placeholder="Nhập vào mật khẩu mới..."
          allow-clear
          style="width: 60%"
          v-model:value="newpassword"
          :class="{ 'border-1 border-rose-600': errors.newpassword }"
        />
        <small class="text-red ml-fromLabel block" v-if="errors.newpassword">{{
          errors.newpassword[0]
        }}</small>
      </div>
      <div class="w-full pb-4">
        <label
          :class="{
            'text-red': errors.newpassword,
            'min-w-label inline-block': true,
          }"
          >Xác nhận mật khẩu:
        </label>
        <a-input-password
          placeholder="Nhập lại mật khẩu để xác nhận..."
          allow-clear
          style="width: 60%"
          v-model:value="newpassword_confirmation"
          :class="{ 'border-1 border-rose-600': errors.newpassword }"
        />
      </div>
    </a-modal>
  </section>
</template>

<script>
import { defineComponent, reactive, ref, toRefs } from "vue";
import { useRouter } from "vue-router";
// import { googleAuthCodeLogin } from "vue3-google-login";
import { ref as fbref, getDownloadURL } from "firebase/storage";
import { storage } from "../firebase";
export default defineComponent({
  setup() {
    const router = useRouter();
    const account = reactive({
      username: "",
      password: "",
    });

    const userOTP = reactive({
      fullname: "Your name",
      avatar:
        "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAjVBMVEXZ3OFwd39yd3tweHtydn/c3+TZ3eBveXtxd31weH7e4eZuc3dtcnZsdHdrc3bV2N3LztOAho1rcnukqa3Gyc57foOrrrNpdHWRlp20uLx8g4a+w8eHjJDP1Nits7eeo6eTm563v8GjrK5+h4qTl6DCxs+anaKNkpaIjJWNlJd2f4HP2dtlbXV/gYaJi5DMCHAdAAAHH0lEQVR4nO2da1OrOhRA29ANNISAPPri0arntlbr+f8/7waq3qOntUASknizPjmO47Amr71DsplMLBaLxWKxWCwWi8VisVgsFovFYrFYLBaLxWKxWCwWi8VisVgsFovFYrFYLBaL5X8LAMYYXNwAqh9GPICjIl3dPXuOk9+t0iLCWPUjCQQA4vT1lCTJdM6YTtlPp7s0hp/SkuCuS5JMvxKQch39BEeY1DlB6C/BqY8CktfmO+L7DUXokmHzW4du7s0ejzha/d09P5McI+yqfs6huLhAtwSnfoAKY5sRHqgzv2mIEElNHYx76nldDBFZqX7UIbhuSW7ZfViS0sCh6JbBhQn0MggFpern7Q0cLi6C1wxRcDBsLMKe+L7fuZcyyA5M6ql40XkMfrQjWRjUilBQp7chIoU5iq7nef0NEYpUP3hX8M1Q7TLh0ZDgBorTIMHpfGlKP807T6JfDJ1c9aN3AqdkqKFHUhP6aYXQQEPfcZ4r1Y9/G9aEHIahAY0YIZ42dPRfMXCddI/WvjL357TWfTp1H4fqnQkfNTeEjPIZejTTWxFveQ3DreaGz71D7s/4CGk9m0JMe4fcn2EpRqza4jugFmCo9WyKVyGnoY+Clc7dFOfdN2eukutsWAUCDBONwxrIemywXTfMVHtcB+6FGK713XWDWkgvrV1XV0VIhRimWFtDvBViuNW3DQUYzmazZPuj29AaKkaUob7jEFIh66HGu1GwEGKo8UsoWAuKaVSLXAUKfkPf1/ktm4DI23GcUOPIW0D21BjG+rbhxL14gq2foedoLDjBrw7nLgZbD+/0XSxE7NOwBf+Xzoaw4N1rY4YaL4ftpj6/4T9aG054DT2PqJb4HlyGnIZhqfMwbI6ULjkNqdbDkC2Ice/jXp9xqM7rfQPccRo+qja4BSyGnYd6hy70HoaMirMN9T9ugvfB4LMYCAV7zUchw824DDV/i9+Cj8Fgw8CI04luxWGo+1JxBqeE5bFDDM04uNewGWhoxuHLSZthDDJMTJhmzuC6/0n2+fxUm9JHm0tr/Y9GeXRrjiBThH3f4I3sjOmiZ/oqJgYEM1+Ah/Z27M2lsbmd5zj0wTjB9gJp0NEwQGbeBoboQG6HN80d0oOxN7pxsbmZ8vtkUxhcRALw/WNIPYbziebubPNbb7ks1wb7NQBk2w2l4d+GTkjpZpv9gNIRgKNscXimtPVsGzMMk1OSHxZZZHj7fQAAblXU6f54KMvysNumdVExddXPJRgADH+g+nEsFovFoi/uG6qfQx7W0GjaknvvsDDuB0U1jVmUrRfpr9XTXcPT0z6t71nYjX9AaMraKq63jyEhlJJkNpu1p55mfkAYYbmtY9fV9/7ILVjaFC8OLMldtsmuN28FW8Vmc+acAC+944OhSRSOin2eJP60rZV4rpf4x96F759rKM59P0le94VpkuBmu2cazt5a7VvY3zT1BfcmZfvYrTftvkVnQ0ZIN3VkxsSDqz1dnree+hiyUUmDfaW/I8Q7Gt4sQ3cFL6R7zauasvYjrHty1MUIiM7tiKMHp9025DBsNuJSXbfA8Tqn5+3QYYLtMfYGkq91vPmEowPhv03SghA5vujWVQHWRMTtyjdDtnas9VKEl+OVWroDDR1PrxdSuMiTpvbh4NI7nzn/oyTXpzYtXgSd1vaeIKLLSUx3Rx0JgmxmpVqcXoDJI4vRZLRhk139nqh2dKHK+Q4Ff0+QV4oVIUaBREE2reZqjyviTNgScc0wQJnC+QZngbAl4jLs36s8dMq6qFS9N0mEVClCNTiL6GmI1Ew3UD2Ht0uuizHMlZQdckt/PoJgOxZDFQXN8Y6zul4fPLoffULF3LdF+8Cy4rFjVMgI753mPngOIuMeAocXcdlgZ8uXUQ2PweiGy+OIjQi1wIy+Kx4d7yw/i2UccRl9V1h4MVpsA4fuH+cQSfI0kqGYKjRDOI21ATf+LPOGH4zih7eyU6arzMe5VxPTUaLRi4YeHaEELz4MvUApwjA4SG/EplCSwjZ05Adv8CRhZ7Qzs9lMdiNClig2lH3bG1Yydrd7GUoOT+NAtaEvd+sNeMvm8+MHctfEkLNsvgBDhCS+AIf1mFsX1wyJxLqK+HH4KQRxir7Egm6VqqTiMydpoZuYAqz8yKvohjeq0qYvvErqphDLfBfah2UsZzrlLQEljqWkj3zg36rN3vEkVa2rtBiDDZ4j5XUbFJzl9MQh6YOeONXHMJBSjAhKfQxRKaMNo2flMek7cl4K83+sShxyPnulQ17xDpKSX0DKWwRZHMwwlWB41CMmPZOsxBu6pU6G0zvhgu33RVVr/YEvfjKtiK+TYSK+zmk88FPUkjgJT6AGf2xbEifhkSmsdUkOz4hfEKEmc/X7bP8RCE+C8cOwepay8IWXsMNpqJeh8KBGO0Phby/wVi/DpPOHkf8FzHmAerbNDZEAAAAASUVORK5CYII=",
    });

    const PasswordReset = reactive({
      newpassword: "",
      newpassword_confirmation: "",
    });

    const email = ref("");
    const otp = ref("");

    const sendMailModal = ref(false);
    const enterOTPModal = ref(false);
    const ResetPasswordModal = ref(false);
    const errors = ref({});

    const SignIn = () => {
      axios
        .post("http://127.0.0.1:8000/api/login", account)
        .then(function (response) {
          localStorage.setItem("token", JSON.stringify(response.data));
          router.push({ name: "shop" });
          //console.log(JSON.parse(localStorage.getItem('token')).access_token)
        })
        .catch(function (error) {
          if (error.response.status == 422) {
            errors.value = error.response.data.errors;
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
          }
        });
      // console.log(account);
    };

    function showSendMailModal() {
      sendMailModal.value = true;
    }

    const sendMail = () => {
      axios
        .post("http://127.0.0.1:8000/api/users/sendmail", {
          email: email.value,
        })
        .then(function (response) {
          sendMailModal.value = false;
          userOTP.fullname = response.data.fullname;
          getDownloadURL(fbref(storage, "users/" + response.data.avatar)).then(
            (download_url) => (userOTP.avatar = download_url)
          );
          enterOTPModal.value = true;
        })
        .catch(function (error) {
          errors.value = error.response.data.errors;
        });
    };

    const sendOTP = () => {
      axios
        .post("http://127.0.0.1:8000/api/users/sendotp", {
          email: email.value,
          otp: otp.value,
        })
        .then(function (response) {
          enterOTPModal.value = false;
          ResetPasswordModal.value = true;
        })
        .catch(function (error) {
          if (error.response.status == 422) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
            enterOTPModal.value = false;
            otp.value = "";
          }
        });
    };

    const ResetPassword = () => {
      axios
        .post("http://127.0.0.1:8000/api/users/resetpassword", {
          email: email.value,
          otp: otp.value,
          newpassword: PasswordReset.newpassword,
          newpassword_confirmation: PasswordReset.newpassword_confirmation,
        })
        .then(function (response) {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: response.data.message,
            showConfirmButton: false,
            timer: 1500,
          });
          ResetPasswordModal.value = false;
          otp.value = "";
          PasswordReset.newpassword = "";
          PasswordReset.newpassword_confirmation = "";
        })
        .catch(function (error) {
          if (error.response.status == 422) {
            errors.value = error.response.data.errors;
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: error.response.data.message,
            });
            ResetPasswordModal.value = false;
          }
        })
    };

    const SignInWithGoogle = () => {
      // googleAuthCodeLogin()
      //   .then((response) => {
      //     console.log("Handle the response", response);
      //   })
      //   .catch(function (error) {
      //     console.log(error);
      //   });
    };
    return {
      SignIn,
      errors,
      ...toRefs(account),
      SignInWithGoogle,
      sendMailModal,
      showSendMailModal,
      sendMail,
      email,
      enterOTPModal,
      sendOTP,
      ...toRefs(userOTP),
      ResetPasswordModal,
      otp,
      ...toRefs(PasswordReset),
      ResetPassword,
    };
  },
});
</script>
