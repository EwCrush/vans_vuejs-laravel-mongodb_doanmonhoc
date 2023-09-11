<template>
  <div class="bg-black w-full h-20">
    <div class="container flex items-center justify-between h-full">
      <div class="header-img">
        <router-link :to="{ name: 'home' }"
          ><img src="../../assets/imgs/logo.webp" alt=""
        /></router-link>
      </div>
      <nav
        class="header-nav text-white flex justify-evenly items-center w-full"
      >
        <div class="header-nav-item">
          <router-link :to="{ name: 'shop' }">SHOP</router-link>
        </div>
        <div class="header-nav-item">
          <router-link :to="{ name: 'sizechart' }">SIZE CHART</router-link>
        </div>
        <div class="header-nav-item">
          <router-link :to="{ name: 'about' }">ABOUT US</router-link>
        </div>
        <div class="header-nav-item">
          <router-link :to="{ name: 'about' }">CONTACT</router-link>
        </div>
        <div class="header-search">
          <input
            type="text"
            placeholder="Search for products"
            class="header-search-textbox"
          />
          <i
            class="fa-solid fa-magnifying-glass text-black header-nav-item header-search-icon"
          ></i>
        </div>
      </nav>
      <div class="header-user text-white flex justify-evenly w-1/6">
        <a href="" class="header-nav-item">SIGN IN</a>
        <span>/</span>
        <a href="" class="header-nav-item">SIGN UP</a>
      </div>
    </div>
  </div>
</template>
