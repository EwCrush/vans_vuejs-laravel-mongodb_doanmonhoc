<template>
    <div class=" bg-black w-full h-20">
       <div class="container flex items-center justify-between h-full">
           <div class="header-img">
               <img src="../imgs/logo.webp" alt="">
           </div>
           <nav class="header-nav text-white flex justify-evenly items-center w-full">
               <a class="header-nav-item" href="">HOME</a>
               <a class="header-nav-item" href="">SHOP</a>
               <a class="header-nav-item" href="">SALES</a>
               <a class="header-nav-item" href="">CONTACT</a>
               <a class="header-nav-item" href="">NEWS</a>
               <div class="header-search">
                   <input type="text" placeholder="Tìm kiếm..." class="header-search-textbox">
                   <i class="fa-solid fa-magnifying-glass text-black header-nav-item header-search-icon"></i>
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

