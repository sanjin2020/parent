<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>水司管理</el-breadcrumb-item>
            <el-breadcrumb-item>商品管理</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
<!--        选项卡-->
        <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane label="商品列表" name="list">
                <el-form :inline="true" :model="searchForm" ref="searchForm">
                    <el-row>
                        <el-form-item label="商品名">
                            <el-input v-model="searchForm.name"></el-input>
                        </el-form-item>

                        <el-form-item label="价格区间">
                            <el-col :span="8"><el-input type="number" v-model.number="searchForm.price_low"></el-input></el-col>
                            <el-col :span="1">-</el-col>
                            <el-col :span="8"><el-input type="number" v-model.number="searchForm.price_high"></el-input></el-col>
                        </el-form-item>
                    </el-row>

                    <el-row>
                        <el-form-item label="审核状态">
                            <el-switch
                                    v-model="searchForm.audit"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949">
                            </el-switch>
                        </el-form-item>

                        <el-form-item label="上架状态">
                            <el-switch
                                    v-model="searchForm.state"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949">
                            </el-switch>
                        </el-form-item>

                        <el-form-item label="库存状态">
                            <el-switch
                                    v-model="searchForm.storage"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949">
                            </el-switch>
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
                    <el-table-column label="id" prop="id"></el-table-column>
                    <el-table-column label="名称" prop="name"></el-table-column>
                    <el-table-column label="分类" prop="catname"></el-table-column>
                    <el-table-column label="价格" prop="price"></el-table-column>
                    <el-table-column label="审核状态" prop="audit">
                        <template #default="o">
                            <span v-if="o.row.audit===0">未审核</span>
                            <span v-if="o.row.audit===1">审核通过</span>
                            <span v-if="o.row.audit===2">审核失败</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="上架状态" prop="state">
                        <template #default="o">
                            <el-switch
                                    :value="o.row.state===1"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949"
                                    @click.native="changeState(o.row.id,o.row.state)">
                            </el-switch>
                        </template>
                    </el-table-column>
                    <el-table-column label="库存状态" prop="storage">
                        <template #default="o">
                            <el-switch
                                    :value="o.row.storage>o.row.storage_alert"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949">
                            </el-switch>
                        </template>
                    </el-table-column>

                    <el-table-column label="库存量" prop="storage"></el-table-column>

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
            <el-tab-pane label="新增商品" name="add">
                <el-col :span="12">
                    <el-form label-width="80px" :model="form" :rules="rules" ref="addForm">
                        <el-form-item label="分类" prop="cid">
                            <el-select v-model="form.cid" placeholder="请选择">
                                <el-option-group
                                        v-for="group in categories"
                                        :key="group.label"
                                        :label="group.label">
                                    <el-option
                                            v-for="item in group.children"
                                            :key="item.id"
                                            :label="item.label"
                                            :value="item.id">
                                    </el-option>
                                </el-option-group>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="名称" prop="name">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>

                        <el-form-item label="描述" prop="description">
                            <el-input type="textarea" v-model="form.description"></el-input>
                        </el-form-item>

                        <el-form-item label="价格" prop="price">
                            <el-input type="number" v-model="form.price"></el-input>
                        </el-form-item>

                        <el-form-item label="优惠价" prop="benefit">
                            <el-input type="number" v-model="form.benefit"></el-input>
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

                        <el-form-item label="多图片" prop="pictures">
                            <el-upload
                                    class="upload-demo"
                                    :action="upload"
                                    :on-success="handleAvatarSuccess3"
                                    :before-upload="beforeAvatarUpload"
                                    :on-remove="handleRemove"
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
                                    :on-success="handleAvatarSuccess2"
                                    :before-upload="beforeAvatarUpload">
                                <img v-if="form.content" :src="form.content" class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>

                        <el-form-item label="库存量" prop="storage">
                            <el-input type="number" v-model="form.storage"></el-input>
                        </el-form-item>

                        <el-form-item label="库存报警值" prop="storage_alert">
                            <el-input type="number" v-model="form.storage_alert"></el-input>
                        </el-form-item>

                        <el-form-item label="运费" prop="fare">
                            <el-input type="number" v-model="form.fare"></el-input>
                        </el-form-item>

                        <el-form-item label="关键词" prop="keywords">
                            <el-input  v-model="form.keywords"></el-input>
                        </el-form-item>

                        <el-form-item label="上架状态" prop="state">
                            <el-switch
                                    v-model="form.state"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949">
                            </el-switch>
                        </el-form-item>

                        <el-form-item>
                            <el-button type="primary" @click="submit">确认发布</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-tab-pane>
        </el-tabs>

        <el-dialog
                title="商品编辑"
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
        name: "GoodsManage",
        data(){
            return{
                goodsData:[],
                activeName:"list",
                page:1,
                pageSize:5,
                total:0,
                searchForm:{
                    name:"",
                    price_low:0,
                    price_high:0,
                    state:"false",
                    audit:"false",
                    storage:"false",
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
                let obj={page:this.page,pageSize:this.pageSize,sid:sessionStorage.getItem("shop")};
                if(this.searchForm.name!==""){
                    obj.name = this.searchForm.name;
                }
                if(this.searchForm.price_low>0){
                    obj.price_low = this.searchForm.price_low;
                }
                if(this.searchForm.price_high>0){
                    obj.price_high = this.searchForm.price_high;
                }
                if(this.searchForm.state){
                    obj.state = 1;
                }
                if(this.searchForm.audit){
                    obj.audit = 1;
                }
                if(this.searchForm.storage){
                    obj.storage = 1;
                }

                this.$axios.get(this.$api.GOODS,obj).then(res=>{
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
                        price_low:0,
                        price_high:0,
                        state:"false",
                        audit:"false",
                        storage:"false",
                };
                this.page=1;
                this.fetchData();

            },
            changeState(id,state){
                let obj = {id,state:state===0?1:0};
                console.log(obj);
                // this.$axios.put(this.$api.GOODS,).then(res=>{
                this.$axios.put(this.$api.GOODS,obj).then(res=>{
                    if(res.data.code===200){
                        this.fetchData();
                        this.$message.success("修改成功");
                    }
                })

            },
            edit(id){
                this.$axios.get(this.$api.GOODS,{id}).then(res=>{
                    if(res.data.code===200){
                        this.editForm=res.data.data;
                        this.fileList=res.data.data.pictures.split(";").map((v,i)=>({name:"图片"+(i+1),url:v,response:v}));
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
                    this.$axios.delete(this.$api.GOODS,{id}).then(res=>{
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
                        this.$axios.post(this.$api.GOODS,this.form).then(res=>{
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
                        this.$axios.put(this.$api.GOODS,this.editForm).then(res=>{
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
</style>