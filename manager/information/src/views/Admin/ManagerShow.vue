<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>管理员选项</el-breadcrumb-item>
            <el-breadcrumb-item>查看管理员</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
        <!--        @tab-click="handleClick"-->
        <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane label="查看管理员" name="show">
                <el-form :inline="true">
                    <el-form-item>
                        <el-input placeholder="输入管理员姓名" v-model="value"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button circle type="primary" icon="el-icon-search" @click="serch"></el-button>
                    </el-form-item>
                </el-form>

                <el-table :data="tableData">
                    <el-table-column label="id" prop="id"></el-table-column>

                    <el-table-column label="用户名" prop="username"></el-table-column>

                    <el-table-column label="类型" >
                        <template #default="o">
                            <div v-if="o.row.role==2">普通管理员</div>
                            <div v-if="o.row.role==3">店铺管理员</div>
                            <div v-if="o.row.role==4">水司管理员</div>
                        </template>
                    </el-table-column>

                    <el-table-column label="状态" prop="state">
                        <template #default="o">
                            <div v-if="o.row.state===1">白名单</div>
                            <div v-if="o.row.state===2">黑名单</div>
                        </template>
                    </el-table-column>

                    <el-table-column label="上次登陆的时间" prop="last_login_time"></el-table-column>
                    <el-table-column label="操作">
                        <template #default="o">
                            <el-button type="text" @click="del(o.row.id)">删除</el-button>
                            <el-button type="text" v-if="o.row.state===1" @click="move(o.row.id,2)">加入黑名单</el-button>
                            <el-button type="text" v-else @click="move(o.row.id,1)">移出黑名单</el-button>
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
            <el-tab-pane label="添加管理员" name="add">
                <el-col :span="12">
                    <el-form :model="form" :rules="rules" ref="addForm">
                        <el-form-item label="请输入新的管理员名称" prop="username" >
                            <el-input v-model="form.username"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="add">新增</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-tab-pane>

            <el-tab-pane label="添加店铺管理员" name="add2">
                <el-col :span="12">
                    <el-form :model="form2" :rules="rules2" ref="addForm2" label-width="80px">
                        <el-form-item label="名称" prop="username" >
                            <el-input v-model="form2.username"></el-input>
                        </el-form-item>
                        <el-form-item label="店铺" prop="shopid" >
                            <el-select v-model="form2.shopid">
                                <el-option v-for="item in shop" :key="item.value" :label="item.label" :value="item.value"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="add2">新增</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-tab-pane>

            <el-tab-pane label="添加水司管理员" name="add3">
                <el-col :span="12">
                    <el-form :model="form3" :rules="rules3" ref="addForm3" label-width="80px">
                        <el-form-item label="名称" prop="username" >
                            <el-input v-model="form3.username"></el-input>
                        </el-form-item>
                        <el-form-item label="水司" prop="shopid" >
                            <el-select v-model="form3.shopid">
                                <el-option v-for="item in water" :key="item.value" :label="item.label" :value="item.value"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="add3">新增</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-tab-pane>
        </el-tabs>
    </el-row>
</template>

<script>
    export default {
        name: "ManagerShow",
        data: () => ({
            activeName: "add",
            tableData: [],
            total: 0,//总条数
            page: 1,//页码
            pageSize: 5,//每一行可以显示的条数
            value:"",//搜索的内容
            form:{
                username:"",
                role:2
            },
            rules:{
                username:{required:true,message: "请输入管理员名称",trigger:"blur"}
            },
            form2:{
                username:"",
                shop:"",
                role:3

            },
            rules2:{
                username:{required:true,message: "请输入管理员名称",trigger:"blur"},
                shopid:{required:true,message: "请选择对应店铺",trigger:"change"}
            },
            form3:{
                username:"",
                shop:"",
                role:4

            },
            rules3:{
                username:{required:true,message: "请输入管理员名称",trigger:"blur"},
                shopid:{required:true,message: "请选择对应店铺",trigger:"change"}
            },
            shop:[

            ],
            water:[

            ],

        }),
        methods: {
            fetchData() {
                this.$axios.get(this.$api.MANAGER, {role: 2, page: this.page, pageSize: this.pageSize,search:this.value}).then(res => {
                    // console.log(res);
                    if (res.data.code === 200) {
                        this.tableData = res.data.data;
                        this.total = res.data.total;
                    }
                })
            },

            handleClick1() {
                this.fetchData();
            },
            serch(){
                this.page=1;
                this.fetchData();
            },
            del(id){
                this.$confirm('此操作将永久删除该管理员, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete(this.$api.MANAGER,{id}).then(res=>{
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
            move(id,state){
                this.$axios.put(this.$api.MANAGER,{id,state}).then(res=>{
                    if(res.data.code===200){
                        this.$message.success("修改成功");
                        this.page=1;
                        this.fetchData();
                    }
                })
            },
            add(){
                this.$refs.addForm.validate(valid=>{
                    if(valid){
                        this.$axios.post(this.$api.MANAGER,this.form).then(res=>{
                            if(res.data.code===200){
                                this.$message.success("添加成功");
                                this.activeName="show";
                                this.page=1;
                                this.fetchData();
                                this.$refs.addForm.resetFields();
                            }
                        });
                    }else{
                        return false;
                    }

                })
            },
            add2(){
                this.$refs.addForm2.validate(valid=>{
                    if(valid){
                        this.$axios.post(this.$api.MANAGER,this.form2).then(res=>{
                            if(res.data.code===200){
                                this.$message.success("添加成功");
                                this.activeName="show";
                                this.page=1;
                                this.fetchData();
                                this.$refs.addForm2.resetFields();
                            }
                        });
                    }else{
                        return false;
                    }

                })
            },
            add3(){
                this.$refs.addForm3.validate(valid=>{
                    if(valid){
                        this.$axios.post(this.$api.MANAGER,this.form3).then(res=>{
                            if(res.data.code===200){
                                this.$message.success("添加成功");
                                this.activeName="show";
                                this.page=1;
                                this.fetchData();
                                this.$refs.addForm3.resetFields();
                            }
                        });
                    }else{
                        return false;
                    }

                })
            },
            handleClick(tab){
                if(this.activeName==="add2"&&this.shop.length===0){
                    this.$axios.get(this.$api.SHOP,{state:2,quit:1,type:1}).then(res=>{
                        if(res.data.code===200){
                            let data = res.data.data;
                            this.shop=data.map(v=>({value:v.id,label:v.name}));
                            console.log(this.shop[0].value);
                        }
                    })
                }else if (this.activeName==="add3"&&this.water.length===0) {
                    this.$axios.get(this.$api.SHOP,{state:2,quit:1,type:2}).then(res=>{
                        if(res.data.code===200){
                            let data = res.data.data;
                            this.water=data.map(v=>({value:v.id,label:v.name}));
                            console.log(this.water[0].value);
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

</style>