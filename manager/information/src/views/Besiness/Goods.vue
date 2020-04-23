<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>商家管理</el-breadcrumb-item>
            <el-breadcrumb-item>商品管理</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
        <!--        选项卡-->
        <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane label="审核通过" name="audit">
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
                    <el-table-column label="所属店铺" prop="shop"></el-table-column>
                    <el-table-column label="价格" prop="price"></el-table-column>


                    <el-table-column label="操作">
                        <template #default="o">
                            <el-button type="text" @click="edit(o.row.id)">查看</el-button>
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
            <el-tab-pane label="未审核/审核未通过" name="unaudit">
                <el-form :inline="true" :model="searchForm2" ref="searchForm2">
                    <el-row>
                        <el-form-item label="商品名">
                            <el-input v-model="searchForm2.name"></el-input>
                        </el-form-item>

                        <el-form-item label="价格区间">
                            <el-col :span="8"><el-input type="number" v-model.number="searchForm2.price_low"></el-input></el-col>
                            <el-col :span="1">-</el-col>
                            <el-col :span="8"><el-input type="number" v-model.number="searchForm2.price_high"></el-input></el-col>
                        </el-form-item>
                    </el-row>

                    <el-row>
                        <el-form-item >
                            <el-button type="primary" circle icon="el-icon-search" @click="handleSearch2"></el-button>
                        </el-form-item>

                        <el-form-item >
                            <el-button type="primary" circle icon="el-icon-refresh" @click="handleReset2"></el-button>
                        </el-form-item>
                    </el-row>
                </el-form>

                <el-table :data="goodsData2">
                    <el-table-column label="id" prop="id"></el-table-column>
                    <el-table-column label="名称" prop="name"></el-table-column>
                    <el-table-column label="分类" prop="catname"></el-table-column>
                    <el-table-column label="所属店铺" prop="shop"></el-table-column>
                    <el-table-column label="价格" prop="price"></el-table-column>
                    <el-table-column label="审核状态" >
                        <template #default="o">
                            <span v-if="o.row.audit===0">未审核</span>
                            <span v-if="o.row.audit===2">审核失败</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作">
                        <template #default="o">
                            <el-button type="text" @click="edit(o.row.id)">查看</el-button>
                            <el-button type="text" @click="audit(o.row.id)">审核</el-button>
                        </template>
                    </el-table-column>
                </el-table>

                <el-pagination
                        style="margin-top: 30px"
                        background
                        layout="prev, pager, next"
                        :current-page.sync="page2"
                        :page-size.sync="pageSize2"
                        :total="total2"
                        @current-change="handleClick2"
                >
                </el-pagination>
            </el-tab-pane>
        </el-tabs>

        <el-dialog
                title="商品编辑"
                :visible.sync="dialogVisible"
                width="50%"
                :before-close="handleClose">
            <el-form label-width="80px" :model="editForm" >

                <el-form-item label="名称" >
                    <el-input v-model="editForm.name" readonly></el-input>
                </el-form-item>

                <el-form-item label="描述" >
                    <el-input type="textarea" v-model="editForm.description" readonly></el-input>
                </el-form-item>

                <el-form-item label="价格" >
                    <el-input type="number" v-model="editForm.price" readonly></el-input>
                </el-form-item>

                <el-form-item label="优惠价" >
                    <el-input type="number" v-model="editForm.benefit" readonly></el-input>
                </el-form-item>

                <el-form-item label="缩略图" >
                        <img :src="editForm.thumb" class="avatar">
                </el-form-item>

                <el-form-item label="多图片" prop="pictures">
                    <el-upload
                            class="upload-demo"
                            :action="upload"
                            :file-list="fileList"
                            list-type="picture"
                            :disabled="true">
                        <el-button size="small" type="primary">点击上传</el-button>
                        <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                    </el-upload>
                </el-form-item>


                <el-form-item label="详情图" >
                        <img  :src="editForm.content" class="avatar">
                </el-form-item>

                <el-form-item label="运费" >
                    <el-input type="number" v-model="editForm.fare" readonly></el-input>
                </el-form-item>

                <el-form-item label="关键词" >
                    <el-input  v-model="editForm.keywords" readonly></el-input>
                </el-form-item>

            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">确定</el-button>
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
                goodsData2:[],
                activeName:"audit",
                page:1,
                page2:1,
                pageSize:5,
                pageSize2:5,
                total:0,
                total2:0,
                searchForm:{
                    name:"",
                    price_low:0,
                    price_high:0,
                },
                searchForm2:{
                    name:"",
                    price_low:0,
                    price_high:0,
                },
                editForm:{},
                dialogVisible:false,
                fileList:[],
                upload: this.$api.UPLOAD,
            }
        },
        methods: {
            fetchData1(){
                let obj={page:this.page,pageSize:this.pageSize,audit:1};
                if(this.searchForm.name!==""){
                    obj.name = this.searchForm.name;
                }
                if(this.searchForm.price_low>0){
                    obj.price_low = this.searchForm.price_low;
                }
                if(this.searchForm.price_high>0){
                    obj.price_high = this.searchForm.price_high;
                }

                this.$axios.get(this.$api.GOODS,obj).then(res=>{
                    if(res.data.code===200){
                        this.goodsData = res.data.data;
                        this.total = res.data.total;
                    }
                })
            },
            fetchData2(){
                let obj={page:this.page2,pageSize:this.pageSize2,audit:0};
                if(this.searchForm2.name!==""){
                    obj.name = this.searchForm2.name;
                }
                if(this.searchForm2.price_low>0){
                    obj.price_low = this.searchForm2.price_low;
                }
                if(this.searchForm2.price_high>0){
                    obj.price_high = this.searchForm2.price_high;
                }

                this.$axios.get(this.$api.GOODS,obj).then(res=>{
                    if(res.data.code===200){
                        this.goodsData2 = res.data.data;
                        this.total2 = res.data.total;
                    }
                })
            },
            handleClick1(){
                this.fetchData1();
            },
            handleClick2(){
                this.fetchData2();
            },
            handleSearch(){
                this.page=1;
                this.fetchData1();
            },
            handleSearch2(){
                this.page=1;
                this.fetchData2();
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
                this.fetchData1();

            },
            handleReset2(){
                this.searchForm2={
                    name:"",
                    price_low:0,
                    price_high:0,
                    state:"false",
                    audit:"false",
                    storage:"false",
                };
                this.page=1;
                this.fetchData1();

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
            handleClose(){
                this.dialogVisible = false
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
            },
            audit(id){
                    this.$confirm('修改当前审核状态', '提示', {
                        confirmButtonText: '通过',
                        cancelButtonText: '未通过',
                        type: 'warning'
                    }).then(() => {
                       this.$axios.put(this.$api.GOODS,{id,audit:1}).then(res=>{
                           if(res.data.code===200){
                               this.$message.success("操作成功");
                               this.fetchData1();
                               this.fetchData2();
                           }
                       })
                    }).catch(() => {
                        this.$axios.put(this.$api.GOODS,{id,audit:2}).then(res=>{
                            if(res.data.code===200){
                                this.$message.success("操作成功");
                                this.fetchData1();
                                this.fetchData2();
                            }
                        })
                    });
            }
        },
        mounted() {
            this.fetchData1();
            this.fetchData2();
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