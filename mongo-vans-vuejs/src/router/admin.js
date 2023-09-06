const admin = [
    {
        path: "/admin",
        component: () => import("../layouts/admin.vue"),
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
            }
        ]
    }
];

export default admin;
