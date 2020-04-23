<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>学校活动</el-breadcrumb-item>
            <el-breadcrumb-item>活动安排</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
        <!--        选项卡-->
        <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane label="活动列表" name="list">
                <el-form :inline="true" :model="searchForm" ref="searchForm">
                    <el-row>
                        <el-form-item label="活动名称">
                            <el-input v-model="searchForm.name"></el-input>
                        </el-form-item>

                        <el-form-item >
                            <el-button type="primary" circle icon="el-icon-search" @click="handleSearch"></el-button>
                        </el-form-item>

                        <el-form-item >
                            <el-button type="primary" circle icon="el-icon-refresh" @click="handleReset"></el-button>
                        </el-form-item>
                    </el-row>
                </el-form>

                <el-table :data="goodsData">

                    <el-table-column type="expand">
                        <template slot-scope="props">
                            <el-form label-position="left" inline class="demo-table-expand">

                                <el-form-item label="活动照片">
                                    <img :src="props.row.thumb" alt="">
                                </el-form-item>

                                <el-form-item label="活动内容">
                                    <span>{{ props.row.content }}</span>
                                </el-form-item>

                            </el-form>
                        </template>
                    </el-table-column>

                    <el-table-column label="id" prop="id"></el-table-column>
                    <el-table-column label="名称" prop="name"></el-table-column>
                    <el-table-column label="描述" prop="description"></el-table-column>
                    <el-table-column label="活动时间" prop="time"></el-table-column>

                    <el-table-column label="操作">
                        <template #default="o">
                            <el-button type="text" @click="del(o.row.id)">删除</el-button>
                            <el-button type="text" @click="edit(o.row.id)">编辑</el-button>
                        </template>
                    </el-table-column>
                </el-table>

                <el-pagination
                        style="margin-top: 30px"
                        background
                        layout="prev, pager, next"
                        :current-page.sync="page"
                        :page-size.sync="pageSize"
                        :total="total"
                        @current-change="handleClick1"
                >
                </el-pagination>
            </el-tab-pane>
            <el-tab-pane label="新增活动" name="add">
                <el-col :span="12">
                    <el-form label-width="80px" :model="form" :rules="rules" ref="addForm">
                        <el-form-item label="名称" prop="name">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>

                        <el-form-item label="时间" prop="time">
                            <el-date-picker
                                    v-model="form.time"
                                    type="datetime"
                                    placeholder="选择日期时间"
                                    format="yyyy-MM-dd HH:mm:ss">
                            </el-date-picker>
                        </el-form-item>

                        <el-form-item label="描述" prop="description">
                            <el-input type="textarea" v-model="form.description"></el-input>
                        </el-form-item>

                        <el-form-item label="内容" prop="content">
                            <el-input type="textarea" v-model="form.content"></el-input>
                        </el-form-item>

                        <el-form-item label="缩略图" prop="thumb">
                            <el-upload
                                    class="avatar-uploader"
                                    :action="upload"
                                    :show-file-list="false"
                                    :on-success="handleAvatarSuccess1"
                                    :before-upload="beforeAvatarUpload">
                                <img v-if="form.thumb" :src="form.thumb" class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>


                        <el-form-item>
                            <el-button type="primary" @click="submit">确认发布</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-tab-pane>
        </el-tabs>

        <el-dialog
                title="活动编辑"
                :visible.sync="dialogVisible"
                width="50%"
                :before-close="handleClose">
            <el-form label-width="80px" :model="editForm" :rules="editRules" ref="editForm">

                <el-form-item label="名称" prop="name">
                    <el-input v-model="editForm.name"></el-input>
                </el-form-item>

                <el-form-item label="描述" prop="description">
                    <el-input type="textarea" v-model="editForm.description"></el-input>
                </el-form-item>

                <el-form-item label="价格" prop="price">
                    <el-input type="number" v-model="editForm.price"></el-input>
                </el-form-item>

                <el-form-item label="优惠价" prop="benefit">
                    <el-input type="number" v-model="editForm.benefit"></el-input>
                </el-form-item>

                <el-form-item label="缩略图" prop="thumb">
                    <el-upload
                            class="avatar-uploader"
                            :action="upload"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess4"
                            :before-upload="beforeAvatarUpload">
                        <img v-if="editForm.thumb" :src="editForm.thumb" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>

                <el-form-item label="多图片" prop="pictures">
                    <el-upload
                            class="upload-demo"
                            :action="upload"
                            :on-success="handleAvatarSuccess6"
                            :before-upload="beforeAvatarUpload"
                            :on-remove="handleRemove1"
                            :file-list="fileList"
                            list-type="picture">
                        <el-button size="small" type="primary">点击上传</el-button>
                        <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                    </el-upload>
                </el-form-item>

                <el-form-item label="详情图" prop="content">
                    <el-upload
                            class="avatar-uploader"
                            :action="upload"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess5"
                            :before-upload="beforeAvatarUpload">
                        <img v-if="editForm.content" :src="editForm.content" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>

                <el-form-item label="库存量" prop="storage">
                    <el-input type="number" v-model="editForm.storage"></el-input>
                </el-form-item>

                <el-form-item label="库存报警值" prop="storage_alert">
                    <el-input type="number" v-model="editForm.storage_alert"></el-input>
                </el-form-item>

                <el-form-item label="运费" prop="fare">
                    <el-input type="number" v-model="editForm.fare"></el-input>
                </el-form-item>

                <el-form-item label="关键词" prop="keywords">
                    <el-input  v-model="editForm.keywords"></el-input>
                </el-form-item>

                <el-form-item label="上架状态" prop="state">
                    <el-switch
                            v-model="editForm.state"
                            active-color="#13ce66"
                            inactive-color="#ff4949">
                    </el-switch>
                </el-form-item>

            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-tooltip class="item" effect="dark" content="修改之后会重新提交审核" placement="top-start">
                    <el-button type="primary" @click="update">确 定</el-button>
                </el-tooltip>
            </span>
        </el-dialog>
    </el-row>
</template>

<script>
    export default {
        name: "SchoolActivity",
        data(){
            return{
                goodsData:[],
                activeName:"list",
                page:1,
                pageSize:5,
                total:0,
                searchForm:{
                    name:"",
                },
                form:{
                    name:"",
                    description:"",
                    price:"",
                    benefit:"",
                    thumb:"",
                    pictures:"",
                    content:"",
                    storage:"",
                    storage_alert:"",
                    fare:"",
                    keywords:"",
                    state:0,
                    cid:"",
                    sid:sessionStorage.getItem("shop"),
                },
                editForm:{},
                rules: {
                    name: {required: true, message: "请输入商品名称", trigger: "blur"},
                    description: {required: true, message: "请输入商品描述", trigger: "blur"},
                    price: {required: true, message: "请输入商品价格", trigger: "blur"},
                    benefit: {required: true, message: "请输入商品优惠价格", trigger: "blur"},
                    thumb: {required: true, message: "请选择商品缩略图", trigger: "blur"},
                    pictures: {required: true, message: "请选择商品图片", trigger: "blur"},
                    content: {required: true, message: "请选择详情图片", trigger: "blur"},
                    storage: {required: true, message: "请输入库存量", trigger: "blur"},
                    storage_alert: {required: true, message: "请输入库存量报警值", trigger: "blur"},
                    fare: {required: true, message: "请输入商品运费", trigger: "blur"},
                    keywords: {required: true, message: "请输入商品关键词", trigger: "blur"},
                    cid: {required: true, message: "请输入商品分类", trigger: "change"},
                },
                editRules: {
                    name: {required: true, message: "请输入商品名称", trigger: "blur"},
                    description: {required: true, message: "请输入商品描述", trigger: "blur"},
                    price: {required: true, message: "请输入商品价格", trigger: "blur"},
                    benefit: {required: true, message: "请输入商品优惠价格", trigger: "blur"},
                    thumb: {required: true, message: "请选择商品缩略图", trigger: "blur"},
                    pictures: {required: true, message: "请选择商品图片", trigger: "blur"},
                    content: {required: true, message: "请选择详情图片", trigger: "blur"},
                    storage: {required: true, message: "请输入库存量", trigger: "blur"},
                    storage_alert: {required: true, message: "请输入库存量报警值", trigger: "blur"},
                    fare: {required: true, message: "请输入商品运费", trigger: "blur"},
                    keywords: {required: true, message: "请输入商品关键词", trigger: "blur"},
                },
                upload: this.$api.UPLOAD,
                categories:[],
                dialogVisible:false,
                fileList:[],
            }
        },
        methods: {
            fetchData(){
                let obj={page:this.page,pageSize:this.pageSize};
                if(this.searchForm.name!==""){
                    obj.name = this.searchForm.name;
                }

                this.$axios.get(this.$api.ACTIVITY,obj).then(res=>{
                    if(res.data.code===200){
                        this.goodsData = res.data.data;
                        this.total = res.data.total;
                    }
                })
            },
            handleClick1(){
                this.fetchData();
            },
            handleSearch(){
                this.page=1;
                this.fetchData();
            },
            handleReset(){
                this.searchForm={
                    name:"",
                };
                this.page=1;
                this.fetchData();

            },
            changeState(id,state){
                let obj = {id,state:state===0?1:0};
                console.log(obj);
                // this.$axios.put(this.$api.STUDENT,).then(res=>{
                this.$axios.put(this.$api.STUDENT,obj).then(res=>{
                    if(res.data.code===200){
                        this.fetchData();
                        this.$message.success("修改成功");
                    }
                })

            },
            edit(id){
                this.$axios.get(this.$api.STUDENT,{id}).then(res=>{
                    if(res.data.code===200){
                        this.editForm=res.data.data;
                        // this.fileList=res.data.data.pictures.split(";").map((v,i)=>({name:"图片"+(i+1),url:v,response:v}));
                        this.dialogVisible = true;
                    }
                })
            },
            del(id){
                this.$confirm('此操作将永久删除该商品, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete(this.$api.ACTIVITY,{id}).then(res=>{
                        console.log(res);
                        if(res.data.code===200){
                            this.$message.success("删除成功");
                            this.page=1;
                            this.fetchData();
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            submit(){
                this.$refs.addForm.validate(valid=>{
                    if(valid){
                        this.form.state = Number(this.form.state);
                        console.log(this.form.time);
                        this.$axios.post(this.$api.ACTIVITY,this.form).then(res=>{
                            if(res.data.code){
                                this.$message.success("发布成功");
                                this.activeName = "list";
                                this.fetchData();
                                this.$refs.addForm.resetFields();
                            }
                        })
                    }else{

                    }
                });
            },
            update(){
                this.$refs.editForm.validate(valid=>{
                    if(valid){
                        this.editForm.audit=0;
                        this.$axios.put(this.$api.STUDENT,this.editForm).then(res=>{
                            if(res.data.code===200){
                                this.$message.success("修改成功");
                                this.dialogVisible=false;
                                this.fetchData();
                            }
                        })
                    }
                })
            },
            handleClose(){
                this.dialogVisible = false
            },
            beforeAvatarUpload(file) {
                let typeArr = [ "image/png", "image/page","image/jpg", "image/jpeg"];
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
                    this.form.thumb = res;
                    this.$refs.addForm.validateField("thumb");
                } else {
                    this.$message.error("图片上传失败");
                }
            },
            handleAvatarSuccess2(res) {
                if (res !== "error") {
                    this.form.content = res;
                    this.$refs.addForm.validateField("content");
                } else {
                    this.$message.error("图片上传失败");
                }
            },
            handleAvatarSuccess3(res,file,fileList) {
                this.form.pictures=fileList.map(v=>v.response).join(";");
                this.$refs.addForm.validateField("pictures");
            },
            handleAvatarSuccess4(res) {
                if (res !== "error") {
                    this.editForm.thumb = res;
                    this.$refs.editForm.validateField("thumb");
                } else {
                    this.$message.error("图片上传失败");
                }
            },
            handleAvatarSuccess5(res) {
                if (res !== "error") {
                    this.editForm.content = res;
                    this.$refs.editForm.validateField("content");
                } else {
                    this.$message.error("图片上传失败");
                }
            },
            handleAvatarSuccess6(res,file,fileList) {
                this.editForm.pictures=fileList.map(v=>v.response).join(";");
                this.$refs.editForm.validateField("pictures");
            },

            handleRemove(file, fileList) {
                this.form.pictures=fileList.map(v=>v.response).join(";");
                this.$refs.addForm.validateField("pictures");
            },
            handleRemove1(file, fileList) {
                console.log(fileList);
                this.editForm.pictures=fileList.map(v=>v.response).join(";");
                this.$refs.editForm.validateField("pictures");
            },
            handlePreview(file) {
                console.log(file);
            },
            handleClick(name){
                if(this.activeName==="add"&&this.categories.length===0){
                    this.$axios.get(this.$api.CATEGORY).then(res=>{
                        if(res.data.code===200){
                            let data = res.data.data;
                            let arr = [];
                            data.forEach(obj => {
                                if (obj.pid == 0) {
                                    let o = {};
                                    o.label = obj.name;
                                    o.children = [];
                                    o.id =obj.id;
                                    arr.push(o);
                                } else {
                                    let o = {};
                                    o.label = obj.name;
                                    o.id =obj.id;
                                    let p = arr.find(v => (v.id == obj.pid));
                                    p.children.push(o);
                                }
                            });
                            this.categories = arr;
                        }
                    })
                }
            }
        },
        mounted() {
            this.fetchData();
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
    .demo-table-expand {
        font-size: 0;
    }
    .demo-table-expand label {
        width: 90px;
        color: #99a9bf;
    }
    .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
    }
</style>