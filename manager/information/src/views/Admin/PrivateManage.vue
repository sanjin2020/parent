<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>管理员选项</el-breadcrumb-item>
            <el-breadcrumb-item>隐私管理</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>

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
            <el-table-column label="操作">
                <template #default="o">
                    <el-button type="text" @click="reset(o.row.id)">重置密码</el-button>
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
                @current-change="handleClick"
        >
        </el-pagination>

    </el-row>
</template>

<script>
    export default {
        name: "PrivateManage",
        data: () => ({
            tableData: [],
            total: 0,//总条数
            page: 1,//页码
            pageSize: 5,//每一行可以显示的条数
            value:"",//搜索的内容
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
            handleClick() {
                this.fetchData();
            },
            serch(){
                this.page=1;
                this.fetchData();
            },
            reset(id){
                this.$axios.put(this.$api.MANAGER,{id,password:"123456"}).then(res=>{
                    if(res.data.code===200){
                        this.$message.success("更新密码成功");
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

</style>