<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>学生管理</el-breadcrumb-item>
            <el-breadcrumb-item>学生学生信息管理</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
        <!--        选项卡-->
        <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane label="学生列表" name="list">
                <el-form :inline="true" :model="searchForm" ref="searchForm">
                    <el-row>
                        <el-form-item label="学生姓名">
                            <el-input v-model="searchForm.name"></el-input>
                        </el-form-item>

                        <el-form-item label="所在年级" >
                            <el-select v-model="searchForm.grade" placeholder="请选择">
                                <el-option
                                        v-for="group in categories"
                                        :key="group.label"
                                        :label="group.label"
                                        :value="group.label">
                                </el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="所在班级" >
                            <el-select v-model="searchForm.class" placeholder="请选择">
                                <el-option-group
                                        v-for="group in categories"
                                        :key="group.label"
                                        :label="group.label">
                                    <el-option
                                            v-for="item in group.children"
                                            :key="item.label"
                                            :label="item.label"
                                            :value="item.label">
                                    </el-option>
                                </el-option-group>
                            </el-select>
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
                    <el-table-column label="id" prop="id" width="40px"></el-table-column>
                    <el-table-column label="学号" prop="studentid"></el-table-column>
                    <el-table-column label="姓名" prop="name" width="70px"></el-table-column>
                    <el-table-column label="电话" prop="tel"></el-table-column>
                    <el-table-column label="班级" prop="class"></el-table-column>
                    <el-table-column label="年级" prop="grade" width="70px"></el-table-column>
                    <el-table-column label="母亲" prop="mother"></el-table-column>
                    <el-table-column label="母亲电话" prop="mtel"></el-table-column>
                    <el-table-column label="父亲" prop="father"></el-table-column>
                    <el-table-column label="父亲电话" prop="ftel"></el-table-column>

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
            <el-tab-pane label="新增学生" name="add">
                <el-col :span="12">
                    <el-form label-width="80px" :model="form" :rules="rules" ref="addForm">
                        <el-form-item label="所在年级" prop="grade">
                            <el-select v-model="form.grade" placeholder="请选择">
                                <el-option
                                        v-for="group in categories"
                                        :key="group.label"
                                        :label="group.label"
                                        :value="group.label">
                                </el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="所在班级" prop="class">
                            <el-select v-model="form.class" placeholder="请选择">
                                <el-option-group
                                        v-for="group in categories"
                                        :key="group.label"
                                        :label="group.label">
                                    <el-option
                                            v-for="item in group.children"
                                            :key="item.label"
                                            :label="item.label"
                                            :value="item.label">
                                    </el-option>
                                </el-option-group>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="学号" prop="studentid">
                            <el-input type="number" v-model="form.studentid"></el-input>
                        </el-form-item>

                        <el-form-item label="姓名" prop="name">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>

                        <el-form-item label="电话" prop="tel">
                            <el-input type="number" v-model="form.tel"></el-input>
                        </el-form-item>

                        <el-form-item label="母亲姓名" prop="mother">
                            <el-input v-model="form.mother"></el-input>
                        </el-form-item>

                        <el-form-item label="母亲电话" prop="mtel">
                            <el-input type="number" v-model="form.mtel"></el-input>
                        </el-form-item>

                        <el-form-item label="父亲姓名" prop="father">
                            <el-input v-model="form.father"></el-input>
                        </el-form-item>

                        <el-form-item label="父亲电话" prop="ftel">
                            <el-input type="number" v-model="form.ftel"></el-input>
                        </el-form-item>

                        <el-form-item>
                            <el-button type="primary" @click="submit">确认提交</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-tab-pane>
        </el-tabs>

        <el-dialog
                title="学生个人信息编辑"
                :visible.sync="dialogVisible"
                width="50%"
                :before-close="handleClose">
            <el-form label-width="80px" :model="editForm" :rules="editRules" ref="editForm">

                <el-form-item label="所在年级" prop="grade">
                    <el-select v-model="editForm.grade" placeholder="请选择">
                        <el-option
                                v-for="group in categories"
                                :key="group.label"
                                :label="group.label"
                                :value="group.label">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="所在班级" prop="class">
                    <el-select v-model="editForm.class" placeholder="请选择">
                        <el-option-group
                                v-for="group in categories"
                                :key="group.label"
                                :label="group.label">
                            <el-option
                                    v-for="item in group.children"
                                    :key="item.label"
                                    :label="item.label"
                                    :value="item.label">
                            </el-option>
                        </el-option-group>
                    </el-select>
                </el-form-item>

                <el-form-item label="学号" prop="studentid">
                    <el-input type="number" v-model="editForm.studentid"></el-input>
                </el-form-item>

                <el-form-item label="姓名" prop="name">
                    <el-input v-model="editForm.name"></el-input>
                </el-form-item>

                <el-form-item label="电话" prop="tel">
                    <el-input type="number" v-model="editForm.tel"></el-input>
                </el-form-item>

                <el-form-item label="母亲姓名" prop="mother">
                    <el-input v-model="editForm.mother"></el-input>
                </el-form-item>

                <el-form-item label="母亲电话" prop="mtel">
                    <el-input type="number" v-model="editForm.mtel"></el-input>
                </el-form-item>

                <el-form-item label="父亲姓名" prop="father">
                    <el-input v-model="editForm.father"></el-input>
                </el-form-item>

                <el-form-item label="父亲电话" prop="ftel">
                    <el-input type="number" v-model="editForm.ftel"></el-input>
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
        name: "StudentInformation",
        data(){
            return{
                goodsData:[],
                activeName:"list",
                page:1,
                pageSize:5,
                total:0,
                searchForm:{
                    name:"",
                    class:"",
                    grade:"",
                },
                form:{
                    studentid:"",
                    name:"",
                    tel:"",
                    class:"",
                    grade:"",
                    mother:"",
                    mtel:"",
                    father:"",
                    ftel:"",
                },
                editForm:{},
                rules: {
                    studentid: {required: true, message: "请输入学号", trigger: "blur"},
                    name: {required: true, message: "请输入学生姓名", trigger: "blur"},
                    tel: {required: true, message: "请输入学生电话", trigger: "blur"},
                    class: {required: true, message: "请选择学生班级", trigger: "change"},
                    grade: {required: true, message: "请选择学生年级", trigger: "change"},
                    mother: {required: true, message: "请输入学生母亲姓名", trigger: "blur"},
                    mtel: {required: true, message: "请输入学生母亲电话", trigger: "blur"},
                    father: {required: true, message: "请输入学生父亲姓名", trigger: "blur"},
                    ftel: {required: true, message: "请输入学生父亲联系电话", trigger: "blur"},
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

                if(this.searchForm.grade!==""){
                    obj.grade = this.searchForm.grade;
                }

                if(this.searchForm.class!==""){
                    obj.class = this.searchForm.class;
                }



                this.$axios.get(this.$api.STUDENT,obj).then(res=>{
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
                this.$axios.get(this.$api.STUDENT,{id}).then(res=>{
                    if(res.data.code===200){
                        this.editForm=res.data.data;
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
                        // this.form.state = Number(this.form.state);
                        this.$axios.post(this.$api.STUDENT,this.form).then(res=>{
                            if(res.data.code){
                                this.$message.success("提交成功");
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
                if(this.activeName==="list"&&this.categories.length===0){
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
            this.handleClick();
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