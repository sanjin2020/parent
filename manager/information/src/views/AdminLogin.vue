<template>
    <el-container>
        <el-row type="flex" justify="center" align="middle">
            <el-col :span="8" class="box" >
                <h3>家长信息管理系统</h3>
                <el-form label-width="80px" :status-icon="true" :model="form" :rules="rules"   ref="loginForm">
                    <el-form-item label="用户名" prop="username">
                        <el-input v-model="form.username"></el-input>
                    </el-form-item>
                    <el-form-item label="密码" prop="password">
                        <el-input type="password" v-model="form.password"></el-input>
                    </el-form-item>
                    <el-form-item label="验证码" prop="captcha">
                        <el-row>
                            <el-col :span="16">
                                <el-input v-model="form.captcha"></el-input>
                            </el-col>
                            <el-col :span="8">
                                <img :src="src" alt="" class="captcha" @click="handleClick">
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submit">登陆</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </el-container>
</template>

<script>
    export default {
        name: "AdminLogin",
        data: () => ({
            src:"/api/admin/captcha",
            form:{
                username:"",
                password:"",
                captcha:""
            },
            rules:{
                username:{required:true,message:"请输入用户名",trigger:"blur"},
                password:{required:true,message:"请输入密码",trigger:"blur"},
                captcha:{required:true,message:"请输入验证码",trigger:"blur"}
            }
        }),
        methods: {
            handleClick(){
                this.src="/api/admin/captcha?t="+Date.now();
            },
            submit(){
                this.$refs.loginForm.validate(valid=>{
                    if(valid){
                        this.$axios.post(this.$api.LOGIN,this.form).then(res=>{
                            if(res.data.code===200){
                                this.$message.success("登陆成功");
                                sessionStorage.setItem("login",this.form.username);
                                sessionStorage.setItem("shop",res.data.shopid);
                                this.$store.commit("updateRouteData",res.data.route)
                            }
                        });
                    }else{
                        return false;
                    }
                });
                this.handleClick();
            }
        },
        mounted() {
        }
    }
</script>

<style scoped lang="scss">
    .el-container {
        height: 100%;
        background: #ccc;
        background: url("../assets/th.jpg") no-repeat center/cover;

        .el-row {
            height: 100%;
            width: 100%;

            .box{
                height: 330px;
                background: rgba(255, 255, 255, 0.9);
                padding: 30px;

                .captcha{
                    width: 100%;
                    height: 40px;
                }

                .el-button {
                    width: 78%;
                    background-color: #50b7c1;
                }
            }
        }
    }
</style>