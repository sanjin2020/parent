import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

export default new Router({
  routes: [
    {
      path:"/login",
      name:"login",
      component: () => import("./views/AdminLogin"),
    },
    {
      path:"/",
      redirect:"/login"
    },
    {
      path:"/sign",
      name:"sign",
      component:()=>import("./views/ShowSign"),
    }
  ]
})
