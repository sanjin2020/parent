<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>商家管理</el-breadcrumb-item>
            <el-breadcrumb-item>水司管理</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
        <el-tabs v-model="activeName">
            <el-tab-pane label="已审核" name="audited">
                <el-form :inline="true">
                    <el-form-item>
                        <el-input placeholder="输入店铺名称" v-model="search1"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button circle type="primary" icon="el-icon-search"  @click="handleSearch1"></el-button>
                    </el-form-item>
                </el-form>
                <el-table :data="tableData1" :row-class-name="tableRowClassName">>
                    <el-table-column type="expand">
                        <template #default="o">
                            <el-form>
                                <el-col :span="24">
                                    <el-form-item label="描述">
                                        <span>{{o.row.description}}</span>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                    <el-form-item label="银行">
                                        <span>{{o.row.bank}}</span>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                    <el-form-item label="银行卡号">
                                        <span>{{o.row.bank_card}}</span>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                    <el-form-item label="持卡人">
                                        <span>{{o.row.bank_person}}</span>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                    <el-form-item label="logo">
                                        <img :src="o.row.logo" alt="" width="100">
                                    </el-form-item>
                                </el-col>

                                <el-col :span="24" v-if="o.row.quit===2">
                                    <el-form-item label="注销原因">
                                        <span>{{o.row.reason}}</span>
                                    </el-form-item>
                                </el-col>

                            </el-form>

                        </template>
                    </el-table-column>
                    <el-table-column label="id" prop="id"></el-table-column>
                    <el-table-column label="企业名称" prop="name"></el-table-column>
                    <el-table-column label="所在城市" prop="city"></el-table-column>
                    <el-table-column label="操作">
                        <template #default="o">
                            <el-button type="text" v-if="o.row.quit===2" @click="quit(o.row.id)">容许注销</el-button>
                            <span v-else-if="o.row.quit===3">
                                已注销
                            </span>
                        </template>
                    </el-table-column>
                </el-table>
                <el-pagination
                        style="margin-top: 30px"
                        background
                        layout="prev, pager, next"
                        :current-page.sync="page1"
                        :page-size.sync="pageSize1"
                        :total="total1"
                        @current-change="handleChange1"
                >
                </el-pagination>
            </el-tab-pane>

            <el-tab-pane label="未审核" name="notAuited">
                <el-form :inline="true">
                    <el-form-item>
                        <el-input placeholder="输入店铺名称" v-model="search2"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button circle type="primary" icon="el-icon-search"  @click="handleSearch2"></el-button>
                    </el-form-item>
                </el-form>
                <el-table :data="tableData2">
                    <el-table-column type="expand">
                        <template #default="o">
                            <el-form>
                                <el-col :span="24">
                                    <el-form-item label="描述">
                                        <span>{{o.row.description}}</span>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                    <el-form-item label="银行">
                                        <span>{{o.row.bank}}</span>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                    <el-form-item label="银行卡号">
                                        <span>{{o.row.bank_card}}</span>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                    <el-form-item label="持卡人">
                                        <span>{{o.row.bank_person}}</span>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                    <el-form-item label="logo">
                                        <img :src="o.row.logo" alt="" width="100">
                                    </el-form-item>
                                </el-col>

                            </el-form>

                        </template>
                    </el-table-column>
                    <el-table-column label="id" prop="id"></el-table-column>
                    <el-table-column label="企业名称" prop="name"></el-table-column>
                    <el-table-column label="所在城市" prop="city"></el-table-column>
                    <el-table-column label="操作">
                        <template #default="o">
                            <el-button type="text" @click="audit(o.row.id)">审核通过</el-button>
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
                        @current-change="handleChange2"
                >
                </el-pagination>
            </el-tab-pane>
        </el-tabs>
    </el-row>
</template>

<script>
    export default {
        name: "WaterManage",
        data: () => ({
            activeName:"audited",
            tableData1:[],
            page1:1,
            pageSize1:5,
            total1:0,
            search1:"",
            tableData2:[],
            page2:1,
            pageSize2:5,
            total2:0,
            search2:""
        }),
        methods: {
            fetchData1(){
                this.$axios.get(this.$api.SHOP, {state: 2, page: this.page1, pageSize: this.pageSize1,search:this.search1,type:2}).then(res => {
                    // console.log(res);
                    if (res.data.code === 200) {
                        this.tableData1 = res.data.data;
                        this.total1 = res.data.total;
                    }
                })
            },
            handleChange1(){
                this.fetchData1();
            },
            handleSearch1(){
                this.page1=1;
                this.fetchData1();
            },
            fetchData2(){
                this.$axios.get(this.$api.SHOP, {state: 1, page: this.page2, pageSize: this.pageSize2,search:this.search2,type:2}).then(res => {
                    // console.log(res);
                    if (res.data.code === 200) {
                        this.tableData2 = res.data.data;
                        this.total2 = res.data.total;
                    }
                })
            },
            handleChange2(){
                this.fetchData2();
            },
            handleSearch2(){
                this.page2=1;
                this.fetchData2();
            },
            audit(id){
                this.$confirm('确定将当前店铺审核状态设置为通过?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.put(this.$api.SHOP,{id,state:2}).then(res=>{
                        if(res.data.code===200){
                            this.activeName="audited";
                            this.fetchData1();
                            this.$message.success("操作成功");
                            this.fetchData2();
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消操作'
                    });
                });
            },
            quit(id){
                this.$confirm('确定将该店铺注销?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.put(this.$api.SHOP,{id,quit:3}).then(res=>{
                        if(res.data.code===200){
                            this.activeName="audited";
                            this.fetchData1();
                            this.$message.success("操作成功");
                            this.fetchData2();
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消操作'
                    });
                });
            },
            tableRowClassName({row,rowIndex}){
                if(row.quit===3){
                    return "delete";
                }
            },
        },
        mounted() {
            this.fetchData1();
            this.fetchData2();
        }
    }
</script>

<style scoped lang="scss">

</style>

<style >
    .el-table .delete{
        background: rgba(235 ,62 ,62 ,0.33);
    }
</style>


