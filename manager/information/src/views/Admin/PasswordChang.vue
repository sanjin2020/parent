<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>管理员选项</el-breadcrumb-item>
            <el-breadcrumb-item>密码修改</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
        <el-col :span="12">
            <el-form label-width="90px" :model="form" :rules="rules" ref="updateForm">
                <el-form-item label="原始密码" prop="password">
                    <el-input type="password" v-model="form.password" ></el-input>
                </el-form-item>
                <el-form-item label="新密码" prop="newpassword1">
                    <el-input type="password" v-model="form.newpassword1"></el-input>
                </el-form-item>
                <el-form-item label="确认密码" prop="newpassword2">
                    <el-input type="password" v-model="form.newpassword2"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="update">确认修改</el-button>
                </el-form-item>
            </el-form>
        </el-col>

    </el-row>
</template>

<script>
    export default {
        name: "PasswordChang",
        data(){
            let passwordValidator1=(rule,value,callback)=>{
                let reg = /^[a-z0-9]{6,}$/;//从开始到结束 是由6位以上的字母或数字组成
                if(reg.test(value)){
                    callback();
                }else{
                    callback(new Error("密码必须是六位以上的字母或数字组成"));
                }
            };
            let passwordValidator2=(rule,value,callback)=>{
                if(value!==this.form.newpassword1){
                    callback(new Error("两次密码密码必须保持一致"));
                }else{
                    callback();
                }
            };
                return {
            form:{
                password:"",
                newpassword1:"",
                newpassword2:"",
                username:sessionStorage.getItem("login")
            },
            rules:{
                password:{required:true,message:"请输入原始密码",trigger:"blur"},
                newpassword1:[
                    {required:true,message:"请输入新密码",trigger:"blur"},
                    {validator:passwordValidator1,trigger:"blur"}
                    ],
                newpassword2:[
                    {required:true,message:"请再次输入新密码",trigger:"blur"},
                    {validator:passwordValidator2,trigger:"blur"}
                    ],
            }
        }},
        methods: {
            update(){
                this.$refs.updateForm.validate(valid=>{
                    if(valid){
                        this.$axios.put(this.$api.PASSWORD,this.form).then(res=>{
                            if(res.data.code===200){
                                this.$message.success("修改成功");
                                this.$refs.updateForm.resetFields();
                            }
                        })
                    }else{
                        return false;
                    }
                })
            }
        },
        mounted() {
        }
    }
</script>

<style scoped lang="scss">

</style>