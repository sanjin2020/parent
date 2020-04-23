<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>年级班级管理</el-breadcrumb-item>
            <el-breadcrumb-item>年级班级信息</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
        <el-tabs v-model="activeName">
            <el-tab-pane label="分类查看" name="list">
                <el-tree :data="data" :expand-on-click-node="false">
                    <template #default="o">
                        <span class="custom-tree-node">
                            <span>{{ o.node.label }}</span>
                            <span style="float:right">
                                <el-button type="text" size="mini" @click="edit(o.data.id)">
                                    编辑
                                </el-button>
                                <el-button type="text" size="mini" @click="del(o)">
                                    删除
                                </el-button>
                            </span>
                        </span>
                    </template>
                </el-tree>
                <el-dialog
                        title="编辑"
                        :visible.sync="dialogVisible"
                        width="40%"
                        :before-close="handleClose">
                    <el-form label-width="90px" :model="form" :rules="rules" ref="myForm">

                        <el-form-item label="分类名称" prop="name">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>

                        <el-form-item label="分类排序" prop="sort">
                            <el-input type="number" v-model="form.sort"></el-input>
                        </el-form-item>
                    </el-form>
                    <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="submit">确 定</el-button>
            </span>
                </el-dialog>
            </el-tab-pane>

            <el-tab-pane label="分类添加" name="add">
                <el-col :span="12">
                    <el-form :model="form2" label-width="100px" :rules="rules2" ref="addForm">

                        <el-form-item label="上级分类" prop="pid">
                            <el-select v-model="form2.pid" @input="keyChange">
                                <el-option label="无" :value="0"></el-option>
                                <el-option v-for="item in data" :key="item.id" :label="item.label" :value="item.id"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="名称" prop="name">
                            <el-input v-model="form2.name"></el-input>
                        </el-form-item>

                        <el-form-item label="分类排序" prop="sort">
                            <el-input type="number" v-model="form2.sort"></el-input>
                        </el-form-item>

                        <el-form-item label="班级数" prop="number" v-if="grade">
                            <el-input type="number" v-model="form2.number" ></el-input>
                        </el-form-item>

                        <el-form-item label="年级总人数" prop="g_number" v-if="grade">
                            <el-input type="number" v-model="form2.g_number" ></el-input>
                        </el-form-item>

                        <el-form-item label="入学年份" prop="time" v-if="pclass">
                            <el-input  v-model="form2.time" ></el-input>
                        </el-form-item>

                        <el-form-item label="班级人数" prop="c_number" v-if="pclass">
                            <el-input type="number" v-model="form2.c_number" ></el-input>
                        </el-form-item>

                        <el-form-item >
                            <el-button type="primary" @click="add">提交</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-tab-pane>
        </el-tabs>
    </el-row>
</template>

<script>
    export default {
        name: "CategoryManage",
        data() {
            return {
                activeName: "list",
                data: [],
                dialogVisible:false,
                grade:false,
                pclass:false,
                form:{
                    name:"",
                    sort:""
                },
                rules:{
                    name:{required:true,message:"请输入分类名称",trigger:"blur"},
                    sort:{required:true,message:"请输入分类排序",trigger:"blur"}
                },
                form2:{
                    name:"",
                    sort:"",
                    pid:"",
                    time:"",
                    number:"",
                    c_number:"",
                    g_number:""
                },
                rules2:{
                    name:{required:true,message:"请输入分类名称",trigger:"blur"},
                    sort:{required:true,message:"请输入分类排序",trigger:"blur"},
                    pid:{required:true,message:"请选择父类栏目",trigger:"change"},
                    time:{required:true,message:"输入入学年份",trigger:"blur"},
                    number:{required:true,message:"请输入班级数",trigger:"blur"},
                    c_number:{required:true,message:"请输入班级人数",trigger:"blur"},
                    g_number:{required:true,message:"请输入年级总人数",trigger:"blur"},
                },
            }
        },
        methods: {
            fetchData() {
                this.$axios.get(this.$api.CATEGORY).then(res => {
                    if (res.data.code === 200) {
                        let data = res.data.data;
                        let arr = [];
                        data.forEach(obj => {
                            if (obj.pid == 0) {
                                let o = {};
                                o.label = obj.name;
                                o.children = [];
                                o.id = obj.id;
                                o.sort = obj.sort;
                                arr.push(o);
                            } else {
                                let o = {};
                                o.label = obj.name;
                                o.id = obj.id;
                                o.sort = obj.sort;
                                let p = arr.find(v => (v.id == obj.pid));
                                p.children.push(o);
                            }
                        });
                        arr.sort((a, b) => {
                            if (a.sort > b.sort) {
                                return 1;
                            } else {
                                return -1;
                            }
                        });
                        this.data = arr;
                    }
                })
            },
            del(o) {
                let arr = [];
                o.data.children.forEach(v => (arr.push(v.label)));


                let msg = "";
                if (o.node.level === 1) {
                    msg = "删除当前栏目后,当前栏目的子栏目也会被删除,确定删除吗?"
                } else {
                    msg = "删除当前栏目后,当前栏目下的班级信息学生信息也会被删除,确定删除吗?"
                }
                this.$confirm(msg, '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete(this.$api.CATEGORY, {id: o.data.id,time: o.data.label,arr:arr}).then(res => {
                        if (res.data.code === 200) {
                            this.$message.success("删除成功");
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
            edit(id){
                this.$axios.get(this.$api.CATEGORY,{id}).then(res=>{
                    if(res.data.code===200){
                        this.form=res.data.data;
                        this.dialogVisible=true;
                    }
                })
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            submit(){
                this.$refs.myForm.validate(valid=>{
                    if(valid){
                        this.$axios.put(this.$api.CATEGORY,this.form).then(res=>{
                            if(res.data.code===200){
                                this.dialogVisible = false;
                                this.$message.success("修改成功");
                                this.fetchData()
                            }
                        })
                    }else{
                        return false;
                    }
                })
            },
            keyChange(){
                if(this.form2.pid == 0){
                    this.grade = true;
                    this.pclass = false;
                }else{
                    this.grade = false;
                    this.pclass = true;
                }
            },
            add(){
                if(this.form2.g_number){
                    this.form2.time = this.form2.name;
                }else{
                    this.form2.class = this.form2.name;
                }
                this.$refs.addForm.validate(valid=>{
                    if(valid){
                        this.$axios.post(this.$api.CATEGORY,this.form2).then(res=>{
                            if(res.data.code===200){
                                this.$message.success("添加成功");
                                this.activeName="list";
                                this.fetchData();
                                this.$refs.addForm.resetFields();
                            }
                        })
                    }else{
                        return false;
                    }
                })
            }
        },
        mounted() {
            this.fetchData();
        }
    }
</script>

<style scoped lang="scss">
    .custom-tree-node {
        width: 15%;
    }
</style>