<template>
    <el-container>
        <el-header  style="height:75px;background-color:#50b7c1">
            <el-menu style="float: right;width: 100%;"
                     class="el-menu-demo"
                     mode="horizontal"
                     background-color="#50b7c1"
                     text-color="#fff"
                     active-text-color="#ffd04b">
                <el-menu-item index="1" class="item-one">家长信息管理系统</el-menu-item>
                <el-menu-item index="logout" @click="logout" style="float: right">退出登陆</el-menu-item>
                <el-menu-item index="4" style="float: right"><a href="https://www.ele.me" target="_blank">订单管理</a></el-menu-item>
                <el-menu-item index="3" style="float: right">消息中心</el-menu-item>
            </el-menu>
        </el-header>
        <el-row style="height:100%;">
            <el-col :span="4" style="padding-top: 20px;background-color: #78cdd1;height:100%" >
                <el-aside width="99%" height="100%">
                    <el-menu
                            background-color="#78cdd1">
                        <el-submenu :index="item.name" v-for="(item,index) in routeData" :key="index">
                            <template #title>
                                <i :class="item.meta.icon"></i><span>{{item.meta.title}}</span>
                            </template>
                            <el-menu-item :index="navItem.name" v-for="navItem in item.children" :key="navItem.name">
                                <router-link :to="{name:navItem.name}">{{navItem.meta.title}}</router-link>
                            </el-menu-item>
                        </el-submenu>
                    </el-menu>
                </el-aside>
            </el-col>
            <el-col :span="20" style="padding-top: 20px">
                <el-main>
                    <router-view></router-view>
                </el-main>
            </el-col>
        </el-row>
    </el-container>
</template>

<script>
    export default {
        name: "AdminMain",
        data: () => ({
            routeData: []
        }),
        methods: {
            logout() {
                sessionStorage.removeItem("login");
                // this.$router.push({name:"login"});
                sessionStorage.clear();
                location.reload();
            }
        },
        mounted() {
            // console.log(this.$router);
            //this.routeData = this.$router.options.routes.pop().children;

            this.routeData = this.$store.state.routeData[0].children;
        }
    }
</script>

<style scoped lang="scss">
    .el-container {
        height: 100%;

        .el-header {
            overflow: hidden;
            .el-menu {
                height: 100%;
                line-height: 75px;
                color: #393d49;

                .item-one {
                    font-size: 30px;
                    margin-left: 3%;
                }

                .el-menu-item {
                    height: 100%;
                    line-height: 75px;
                }

                .el-submenu {
                    height: 100%;
                    line-height: 75px;
                    padding-top: 8px;
                }
            }
        }

        .el-aside{
            height: 100%;
            .el-submenu
            {
                background-color: #393d49;
                color: #fff;
                .el-submenu__title, .el-icon-user:before
                {
                    color: #495469;
                }
            }
        }

        .el-icon-user:before,.el-icon-edit-outline:before,.el-icon-document:before,.el-icon-s-opportunity:before,.el-icon-umbrella:before
        {
            color: #495469;
        }

        a {
            display: block;
            width: 100%;
            height: 100%;
        }

        .el-icon-folder:before,.el-icon-edit-outline:before,.el-icon-phone:before,.el-icon-folder-opened:before,.el-icon-phone-outline:before
        {
            color: #495469;
        }
    }
</style>