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
          <router-link :to="{ name: 'size-chart' }">SIZE CHART</router-link>
        </div>
        <div class="header-nav-item">
          <router-link :to="{ name: 'about-us' }">ABOUT US</router-link>
        </div>
        <div class="header-nav-item">
          <router-link :to="{ name: 'contact' }">CONTACT</router-link>
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
        <div class="header-nav-item"><div class="header-nav-item">
          <router-link :to="{ name: 'login' }">SIGN IN</router-link>
        </div></div>
        <span>/</span>
        <div class="header-nav-item"><div class="header-nav-item">
          <router-link :to="{ name: 'register' }">SIGN UP</router-link>
        </div></div>
      </div>
    </div>
  </div>
</template>
