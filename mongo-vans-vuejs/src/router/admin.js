const admin = [
    {
        path: "/admin",
        component: () => import("../layouts/admin.vue"),
        beforeEnter: (to, from, next) => {
            if((!localStorage.getItem("token"))||(JSON.parse(localStorage.getItem("token")).role!="admin")){
                next({ name: 'shop' })
            }
            else next()
            // console.log(JSON.parse(localStorage.getItem("token")).role)
        },
        children: [
            {
                path: "",
                //component: () => import ("../pages/admin/categories/index.vue")
                redirect: "admin/categories"
            },
            //quan ly nguoi dung
            {
                path: "users",
                name: "admin-users",
                component: () => import ("../pages/admin/users/index.vue")
            },
            //quan ly loai san pham
            {
                path: "categories",
                name: "admin-categories",
                component: () => import ("../pages/admin/categories/index.vue"),
            },
            {
                path: "categories/add",
                name: "admin-categories-add",
                component: () => import ("../pages/admin/categories/add.vue"),
            },
            {
                path: "categories/edit/:id",
                name: "admin-categories-edit",
                component: () => import ("../pages/admin/categories/edit.vue"),
            },
            //quan ly san pham
            {
                path: "products",
                name: "admin-products",
                component: () => import ("../pages/admin/products/index.vue")
            },
            {
                path: "products/add",
                name: "admin-products-add",
                component: () => import ("../pages/admin/products/add.vue"),
            },
            {
                path: "products/edit/:id",
                name: "admin-products-edit",
                component: () => import ("../pages/admin/products/edit.vue"),
            },
            {
                path: "products/images/:id",
                name: "admin-products-images",
                component: () => import ("../pages/admin/products/images/index.vue"),
            },
            {
                path: "products/images/add/:id",
                name: "admin-products-images-add",
                component: () => import ("../pages/admin/products/images/add.vue"),
            },
            {
                path: "products/sizes/:id",
                name: "admin-products-sizes",
                component: () => import ("../pages/admin/products/sizes/index.vue"),
            },
            {
                path: "products/sizes/add/:id",
                name: "admin-products-sizes-add",
                component: () => import ("../pages/admin/products/sizes/add.vue"),
            },
            {
                path: "products/sizes/edit/:id",
                name: "admin-products-sizes-edit",
                component: () => import ("../pages/admin/products/sizes/edit.vue"),
            },
            //quan ly user
            {
                path: "users",
                name: "admin-users",
                component: () => import ("../pages/admin/users/index.vue")
            },
            {
                path: "users/add",
                name: "admin-users-add",
                component: () => import ("../pages/admin/users/add.vue"),
            },
            {
                path: "users/edit/:id",
                name: "admin-users-edit",
                component: () => import ("../pages/admin/users/edit.vue"),
            },
        ]
    },
    {
        path: "/",
        component: () => import("../layouts/user.vue"),
        children: [
            {
                path: "",
                name: "home",
                component: () => import ("../pages/home.vue")
            },
            {
                path: "shop/:id",
                name: "shop-category",
                component: () => import ("../pages/shop.vue")
            },
            {
                path: "shop",
                name: "shop",
                component: () => import ("../pages/shop.vue")
            },
            {
                path: "size-chart",
                name: "size-chart",
                component: () => import ("../pages/sizechart.vue")
            },
            {
                path: "about-us",
                name: "about-us",
                component: () => import ("../pages/about.vue")
            },
            {
                path: "contact",
                name: "contact",
                component: () => import ("../pages/contact.vue")
            },
            {
                path: "login",
                name: "login",
                component: () => import ("../pages/login.vue"),
                beforeEnter: (to, from, next) => {
                    if(localStorage.getItem("token")){
                        next({ name: 'shop' })
                    }
                    else next()
                },
            },
            {
                path: "register",
                name: "register",
                component: () => import ("../pages/register.vue"),
                beforeEnter: (to, from, next) => {
                    if(localStorage.getItem("token")){
                        next({ name: 'shop' })
                    }
                    else next()
                },
            },
        ]
    },
];

export default admin;
