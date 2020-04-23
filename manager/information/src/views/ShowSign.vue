<template>
    <el-row>
        <el-col :span="12" :offset="6">
            <h3>店铺入驻</h3>
            <el-form label-width="110px" :model="form" ref="myForm" :rules="rules" >
                <el-form-item label="类型" prop="type">
                    <el-select v-model="form.type" placeholder="请选择类型">
                        <el-option v-for="item in options2" :key="item.value" :label="item.label" :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="名称" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="描述" prop="description">
                    <el-input type="textarea" v-model="form.description"></el-input>
                </el-form-item>
                <el-form-item label="选择城市" prop="city">
                    <el-select placeholder="请选择" v-model="form.city">
                        <el-option
                                v-for="item in options"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
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

                <el-form-item label="身份证正面图片" prop="idcard1">
                    <el-upload
                            class="avatar-uploader"
                            :action="upload"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess2"
                            :before-upload="beforeAvatarUpload">
                        <img v-if="form.idcard1" :src="form.idcard1" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>

                <el-form-item label="身份证背面图片" prop="idcard2">
                    <el-upload
                            class="avatar-uploader"
                            :action="upload"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess3"
                            :before-upload="beforeAvatarUpload">
                        <img v-if="form.idcard2" :src="form.idcard2" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>

                <el-form-item label="营业执照" prop="license">
                    <el-upload
                            class="avatar-uploader"
                            :action="upload"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess4"
                            :before-upload="beforeAvatarUpload">
                        <img v-if="form.license" :src="form.license" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submit">申请注册</el-button>
                </el-form-item>
            </el-form>
        </el-col>
    </el-row>
</template>

<script>
    export default {
        name: "ShowSign",
        data() {
            let bank_card_validator =(rule,value,callback)=>{
              if(/^(\d{16}|\d{19})$/.test(value)){
                callback();
              }else{
                callback(new Error("银行卡号必须是16位或19位的数字"))
              }
            }
            return {
                options: [{label: "太原", value: "太原"}, {label: "大同", value: "大同"}],
                options2: [{label: "商家入驻", value: 1}, {label: "水司", value: 2}],
                form: {
                    type:"",
                    city: "",
                    name: "",
                    description: "",
                    bank: "",
                    bank_card: "",
                    bank_person: "",
                    contact_person: "",
                    contact_phone: "",
                    tax_identification_number: "",
                    logo: "",
                    idcard1: "",
                    idcard2: "",
                    license: "",
                },
                rules: {
                    type: {required: true, message: "请输入注册类型", trigger: "change"},
                    city: {required: true, message: "请输入所在区县", trigger: "change"},
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
                    idcard1: {required: true, message: "请上传身份证正面照片", trigger: "blur"},
                    idcard2: {required: true, message: "请上传身份证背面照片", trigger: "blur"},
                    license: {required: true, message: "请上传营业执照照片", trigger: "blur"},
                },
                upload: this.$api.UPLOAD,
            }
        },
        methods: {
            beforeAvatarUpload(file) {
                let typeArr = ["image/jpg", "image/png", "image/page", "image/jpeg"];
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
            handleAvatarSuccess2(res) {
                if (res !== "error") {
                    this.form.idcard1 = res;
                    this.$refs.myForm.validateField("idcard1");
                } else {
                    this.$message.error("图片上传失败");
                }
            },
            handleAvatarSuccess3(res) {
                if (res !== "error") {
                    this.form.idcard2 = res;
                    this.$refs.myForm.validateField("idcard2");
                } else {
                    this.$message.error("图片上传失败");
                }
            },
            handleAvatarSuccess4(res) {
                if (res !== "error") {
                    this.form.license = res;
                    this.$refs.myForm.validateField("license");
                } else {
                    this.$message.error("图片上传失败");
                }
            },

            submit() {
                this.$refs.myForm.validate(valid=>{
                    if(valid){
                        this.$axios.post(this.$api.SHOP, this.form).then(res => {
                            if (res.data.code === 200) {
                                this.$message.success("提交成功");
                                this.$refs.myForm.resetFields();
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