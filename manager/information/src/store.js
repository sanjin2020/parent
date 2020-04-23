import Vue from 'vue'
import Vuex from 'vuex'
import router from "./router"

let rr = [{
    "path": "/main",
    "name": "main",
    "children": [{
        "path": "admin",
        "name": "Admin",
        "meta": {"title": "管理员选项", "icon": "el-icon-user"},
        "children": [{
            "path": "password",
            "name": "PasswordChang",
            "meta": {"title": "密码修改"}},
            {
            "path": "manager",
            "name": "ManagerShow",
            "meta": {"title": "查看管理员"}
        }, {"path": "private",
                "name": "PrivateManage",
                "meta": {"title": "隐私管理"}}]
    }]
}];

Vue.use(Vuex);

function loader(routes) {
    routes[0].component=()=>import("@/views/AdminMain");
    routes[0].children.forEach(v=>{
        v.component=()=>import(`@/views/${v.name}/${v.name}`);
        v.children.forEach(o=>{
            o.component=()=>import(`@/views/${v.name}/${o.name}`)
        })
    })
    return routes;
}


export default new Vuex.Store({
    state: {
        routeData: []
    },
    mutations: {
        updateRouteData(state, obj) {
            if(state.routeData.length!==0){
                return;
            }
            sessionStorage.setItem("route",obj);
            let routes = JSON.parse(obj);
            state.routeData = routes;
            //{path:"",name:"",meta:"",children:[]}
            routes=loader(routes);
            //{path:"",name:"",meta:"",children:[],componets:}
            router.addRoutes(routes);
            router.push({name:"main"});
        },
        updateRouteData2(state, obj) {
            if(state.routeData.length!==0){
                return;
            }
            sessionStorage.setItem("route",obj);
            let routes = JSON.parse(obj);
            state.routeData = routes;
            //{path:"",name:"",meta:"",children:[]}
            routes=loader(routes);
            //{path:"",name:"",meta:"",children:[],componets:}
            router.addRoutes(routes);
        },
    },
    actions: {}
})
