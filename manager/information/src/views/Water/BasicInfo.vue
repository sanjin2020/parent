<template>
    <el-row>
        <el-col :span="12" :offset="3">
            <el-form label-width="120px" :model="form" ref="myForm" :rules="rules" >
                <el-form-item label="名称" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="描述" prop="description">
                    <el-input type="textarea" v-model="form.description"></el-input>
                </el-form-item>
                <el-form-item label="银行名称" prop="bank">
                    <el-input v-model="form.bank"></el-input>
                </el-form-item>
                <el-form-item label="银行卡号" prop="bank_card">
                    <el-input type="number" v-model="form.bank_card"></el-input>
                </el-form-item>
                <el-form-item label="持卡人姓名" prop="bank_person">
                    <el-input v-model="form.bank_person"></el-input>
                </el-form-item>
                <el-form-item label="联系人姓名" prop="contact_person">
                    <el-input v-model="form.contact_person"></el-input>
                </el-form-item>
                <el-form-item label="联系人电话" prop="contact_phone">
                    <el-input type="number" v-model="form.contact_phone"></el-input>
                </el-form-item>
                <el-form-item label="纳税人识别号" prop="tax_identification_number">
                    <el-input v-model="form.tax_identification_number"></el-input>
                </el-form-item>
                <el-form-item label="Logo" prop="logo">
                    <el-upload
                            class="avatar-uploader"
                            :action="upload"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess1"
                            :before-upload="beforeAvatarUpload">
                        <img v-if="form.logo" :src="form.logo" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="submit">确认修改</el-button>
                </el-form-item>
            </el-form>
        </el-col>
    </el-row>
</template>

<script>
    export default {
        name: "BasicInfo",
        data() {
            let bank_card_validator =(rule,value,callback)=>{
                if(/^(\d{16}|\d{19})$/.test(value)){
                    callback();
                }else{
                    callback(new Error("银行卡号必须是16位或19位的数字"))
                }
            }
            return {
                form: {
                    id:sessionStorage.getItem("shop"),
                    name: "",
                    description: "",
                    bank: "",
                    bank_card: "",
                    bank_person: "",
                    contact_person: "",
                    contact_phone: "",
                    tax_identification_number: "",
                    logo: "",
                },
                rules: {
                    name: {required: true, message: "请输入店铺名称", trigger: "blur"},
                    description: {required: true, message: "请输入店铺描述", trigger: "blur"},
                    bank: {required: true, message: "请输入银行名称", trigger: "blur"},
                    bank_card: [
                        {required: true, message: "请输入银行卡号", trigger: "blur"},
                        {validator:bank_card_validator,trigger:"blur"}
                    ],
                    bank_person: {required: true, message: "请输入持卡人姓名", trigger: "blur"},
                    contact_person: {required: true, message: "请输入联系人", trigger: "blur"},
                    contact_phone: {required: true, message: "请输入联系人电话", trigger: "blur"},
                    tax_identification_number: {required: true, message: "请输入纳税人识别号", trigger: "blur"},
                    logo: {required: true, message: "请上传店铺LOGO", trigger: "blur"},
                },
                upload: this.$api.UPLOAD,
            }
        },
        methods: {
            beforeAvatarUpload(file) {
                let typeArr = ["image/jpg", "image/png", "image/page", "image/jpge"];
                if (!typeArr.includes(file.type)) {
                    this.$message.error("请选择正确的图片类型");
                    return false;
                }
                if (file.size > 200 * 1000) {
                    this.$message.error("文件大小超出限制");
                    return false;
                }
                return true;
            },
            handleAvatarSuccess1(res) {
                if (res !== "error") {
                    this.form.logo = res;
                    this.$refs.myForm.validateField("logo");
                } else {
                    this.$message.error("图片上传失败");
                }
            },
            submit() {
                this.$axios.put(this.$api.SHOP,this.form).then(res=>{
                    if(res.data.code===200){
                        this.$message.success("修改成功");
                    }
                });
            }
        },
        mounted() {
            this.$axios.get(this.$api.SHOP,{id:this.form.id}).then(res=>{
                if (res.data.code===200){
                    this.form=res.data.data;
                }
            })
        }
    }
</script>

<style scoped lang="scss">
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        border: 1px dashed #d9d9d9;
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }

    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
</style>