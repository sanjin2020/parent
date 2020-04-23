<template>
    <el-row>
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>家长信息查询</el-breadcrumb-item>
            <el-breadcrumb-item>信息查询</el-breadcrumb-item>
        </el-breadcrumb>
        <el-divider></el-divider>
        <!--        选项卡-->
        <el-tabs v-model="activeName">
            <el-tab-pane label="被监护人列表" name="list">
                <el-form :inline="true" :model="searchForm" ref="searchForm">
                    <el-row>
                        <el-form-item label="学生姓名">
                            <el-input v-model="searchForm.name"></el-input>
                        </el-form-item>

                        <el-form-item label="所在年级">
                            <el-select v-model="searchForm.grade" placeholder="请选择">
                                <el-option
                                        v-for="group in categories"
                                        :key="group.label"
                                        :label="group.label"
                                        :value="group.label">
                                </el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="所在班级">
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

                        <el-form-item>
                            <el-button type="primary" circle icon="el-icon-search" @click="handleSearch"></el-button>
                        </el-form-item>

                        <el-form-item>
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
        </el-tabs>
    </el-row>
</template>

<script>
    export default {
        name: "QueryInformation",
        data: () => ({
            goodsData: [],
            activeName: "list",
            page: 1,
            pageSize: 5,
            total: 0,
            categories:[],
            searchForm: {
                name: "",
                class: "",
                grade: "",
            },
            form: {
                studentid: "",
                name: "",
                tel: "",
                class: "",
                grade: "",
                mother: "",
                mtel: "",
                father: "",
                ftel: "",
            },
        }),
        methods: {
            fetchData() {
                let obj = {page: this.page, pageSize: this.pageSize};
                if (this.searchForm.name !== "") {
                    obj.name = this.searchForm.name;
                }

                if (this.searchForm.grade !== "") {
                    obj.grade = this.searchForm.grade;
                }

                if (this.searchForm.class !== "") {
                    obj.class = this.searchForm.class;
                }


                this.$axios.get(this.$api.STUDENT, obj).then(res => {
                    if (res.data.code === 200) {
                        this.goodsData = res.data.data;
                        this.total = res.data.total;
                    }
                })
            },
            handleSearch() {
                this.page = 1;
                this.fetchData();
            },
            handleReset() {
                this.searchForm = {
                    name: "",
                    price_low: 0,
                    price_high: 0,
                    state: "false",
                    audit: "false",
                    storage: "false",
                };
                this.page = 1;
                this.fetchData();

            },
            handleClick1() {
                this.fetchData();
            },
            handleClick(){
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
        },
    }
</script>

<style scoped lang="scss">

</style>