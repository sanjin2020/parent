import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import './assets/iconFont/iconfont.css';
import './assets/iconFont/iconfont.js';

//引入请求插件
import axios from "./api/methods.js";

//引入api文件
import api from "./api/api.js";

//前置导航守卫
router.beforeEach((to,from,next)=>{
  if(to.name!=="login"&&!sessionStorage.getItem("login")&&to.name!=="sign"){
    next({name:"login"});
  }else{
    next();
  }
});

Vue.prototype.$axios=axios;
Vue.prototype.$api=api;

Vue.config.productionTip = false;
Vue.use(ElementUI);


/*router.addRoutes([{
  path:"/main",
  name:"main",
  component: () => import("./views/AdminMain"),
  children:[
    {
      path:"admin",
      name:"Admin",
      meta:{
        title:"管理员选项",
        icon:"el-icon-user"
      },
      component: () => import("./views/Admin/Admin"),
      children: [
        {
          path:"password",
          name:"PasswordChang",
          meta:{
            title:"密码修改"
          },
          component: () => import("./views/Admin/PasswordChang"),
        },
        {
          path:"manager",
          name:"ManagerShow",
          meta:{
            title:"查看管理员"
          },
          component: () => import("./views/Admin/ManagerShow"),
        },
        {
          path:"private",
          name:"PrivateManage",
          meta:{
            title:"隐私管理"
          },
          component: () => import("./views/Admin/PrivateManage"),

        },
      ]
    }
  ],
}]);*///动态添加路由信息

let r1=JSON.stringify([{
  path:"/main",
  name:"main",
  component: () => import("./views/AdminMain"),
  children:[
    {
      path:"admin",
      name:"Admin",
      meta:{
        title:"账号信息",
        icon:"el-icon-user"
      },
      children: [
        {
          path:"password",
          name:"PasswordChang",
          meta:{
            title:"密码修改"
          },
        },
      ]
    },
    {
      path:"student",
      name:"Student",
      meta:{
        title:"学生信息",
        icon:"el-icon-edit-outline"
      },
      children: [
        {
          path:"information",
          name:"StudentInformation",
          meta:{
            title:"学生信息管理"
          },
        },
      ]
    },
    {
      path:"grade",
      name:"Grade",
      meta:{
        title:"年级班级管理",
        icon:"el-icon-folder"
      },
      children: [
        {
          path:"category",
          name:"CategoryManage",
          meta:{
            title:"年级班级结构"
          },
        },
      ]
    },
    {
      path:"parent",
      name:"Parent",
      meta:{
        title:"家长信息查询",
        icon:"el-icon-document"
      },
      children: [
        {
          path:"query",
          name:"QueryInformation",
          meta:{
            title:"信息查询"
          },
        },
      ]
    },
    {
      path:"data",
      name:"Data",
      meta:{
        title:"家校联系",
        icon:"el-icon-phone-outline"
      },
      children: [
        {
          path:"platform",
          name:"PlatformData",
          meta:{
            title:"平台数据"
          },
        },
        {
          path:"chatList",
          name:"chatList",
          meta:{
            title:"聊天窗口"
          },
        },
      ]
    },
    {
      path:"activity",
      name:"Activity",
      meta:{
        title:"学校活动",
        icon:"el-icon-umbrella"
      },
      children: [
        {
          path:"school",
          name:"SchoolActivity",
          meta:{
            title:"活动安排"
          },
        },
      ]
    },
  ],
}]);//超级管理员权限
console.log(r1);
let r2=JSON.stringify([{
  path:"/main",
  name:"main",
  component: () => import("./views/AdminMain"),
  children:[
    {
      path:"admin",
      name:"Admin",
      meta:{
        title:"管理员选项",
        icon:"el-icon-user"
      },
      component: () => import("./views/Admin/Admin"),
      children: [
        {
          path:"password",
          name:"PasswordChang",
          meta:{
            title:"密码修改"
          },
          component: () => import("./views/Admin/PasswordChang"),
        },
      ]
    },
    // {
    //   path:"user",
    //   name:"User",
    //   meta:{
    //     title:"用户管理",
    //     icon:"el-icon-user"
    //   },
    //   children: [
    //     {
    //       path:"shop",
    //       name:"shop",
    //       meta:{
    //         title:"店铺管理"
    //       },
    //
    //     },
    //   ]
    // }
  ],
}]);//普通管理员权限

let r3=JSON.stringify([{
  path:"/main",
  name:"main",
  children:[
    {
      path:"admin",
      name:"Admin",
      meta:{
        title:"基本设置",
        icon:"el-icon-user"
      },
      component: () => import("./views/Admin/Admin"),
      children: [
        {
          path:"password",
          name:"PasswordChang",
          meta:{
            title:"密码修改"
          },
          component: () => import("./views/Admin/PasswordChang"),
        },
      ]
    },
    {
      path:"business",
      name:"Besiness",
      meta:{
        title:"商家管理",
        icon:"el-icon-user"
      },
      children: [
        {
          path:"basic",
          name:"BasicInfo",
          meta:{
            title:"基本信息"
          },
        },
        {
          path:"goods",
          name:"GoodsManage",
          meta: {
            title: "商品管理"
          }
        },
      ]
    },
    {
      path:"sale",
      name:"Sale",
      meta:{
        title:"销售管理",
        icon:"el-icon-user"
      },
      children: [
        {
          path:"order",
          name:"OrderManage",
          meta:{
            title:"订单管理"
          },
        },
        {
          path:"comments",
          name:"CommentsManage",
          meta: {
            title: "评论管路"
          }
        },
      ]
    },
    {
      path:"data",
      name:"ShopData",
      meta:{
        title:"数据中心",
        icon:"el-icon-user"
      },
      children: [
        {
          path:"order",
          name:"Order",
          meta:{
            title:"订单统计"
          },
        },
        {
          path:"sales",
          name:"SaleManage",
          meta: {
            title: "销售统计"
          }
        },
      ]
    },
  ],
}]);//店铺管理员权限

let r4=JSON.stringify([{
  path:"/main",
  name:"main",
  children:[
    {
      path:"admin",
      name:"Admin",
      meta:{
        title:"基本设置",
        icon:"el-icon-user"
      },
      component: () => import("./views/Admin/Admin"),
      children: [
        {
          path:"password",
          name:"PasswordChang",
          meta:{
            title:"密码修改"
          },
          component: () => import("./views/Admin/PasswordChang"),
        },
      ]
    },
    {
      path:"water",
      name:"Water",
      meta:{
        title:"水司管理",
        icon:"el-icon-user"
      },
      children: [
        {
          path:"basic",
          name:"BasicInfo",
          meta:{
            title:"基本信息"
          },
        },
        {
          path:"category",
          name:"CategoryManage",
          meta: {
            title: "分类管理"
          }
        },
        {
          path:"goods",
          name:"GoodsManage",
          meta: {
            title: "商品管理"
          }
        },
        {
          path:"service",
          name:"ServiceManage",
          meta: {
            title: "服务管理"
          }
        },
      ]
    },
    {
      path:"sale",
      name:"Sale",
      meta:{
        title:"销售管理",
        icon:"el-icon-user"
      },
      children: [
        {
          path:"order",
          name:"OrderManage",
          meta:{
            title:"订单管理"
          },
        },
        {
          path:"comments",
          name:"CommentsManage",
          meta: {
            title: "评论管路"
          }
        },
      ]
    },
    {
      path:"data",
      name:"ShopData",
      meta:{
        title:"数据中心",
        icon:"el-icon-user"
      },
      children: [
        {
          path:"order",
          name:"Order",
          meta:{
            title:"订单统计"
          },
        },
        {
          path:"sales",
          name:"SaleManage",
          meta: {
            title: "销售统计"
          }
        },
      ]
    },
  ],
}]);//水司管理员权限
// console.log(r4);
/*
* 建立一张数据表 role route 保存不同权限的路由信息
* 登陆 从表中找出对应的路由信息 返回给前台
* 由路由信息返回之后再进行动态的添加(Vuex+sessionStorage)
* */
if(sessionStorage.getItem("route")&&store.state.routeData.length===0){
  store.commit("updateRouteData2",sessionStorage.getItem("route"));
}

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
