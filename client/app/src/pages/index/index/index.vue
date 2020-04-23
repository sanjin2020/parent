<template>
    <div>
        <header class="header">
            中北
        </header>
        <div class="search">
        <span class="search-span">
            太原
            <span class="iconfont icon-xiala"></span>
        </span>
            <a href="" class="search-a">
                <p class="search-p">寻找您需要的服务 &nbsp;商品</p>
            </a>
        </div>
        <swiper  indicator-dots="{{indicatorDots}}"  autoplay="{{autoplay}}" interval="{{interval}}"  duration="{{duration}}">
            <block  wx:for="{{imgUrls}}">
                <swiper-item>
                    <image src="{{item}}" class="slide-image" width="355" height="150"/>
                </swiper-item>
            </block>
        </swiper>
        <button bindtap="changeIndicatorDots"> indicator-dots </button>
        <button bindtap="changeAutoplay"> autoplay </button>
        <slider bindchange="intervalChange" show-value min="500" max="2000"/>
        <slider bindchange="durationChange" show-value min="1000" max="10000"/>
<!--        <div class="category">-->
<!--            <li>-->
<!--                <a href="https://sanjin2020.github.io/wuif-water/list.html"><img src="../../../../static/images/l1.png" alt=""></a>-->
<!--                <p>水路综合</p>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="https://sanjin2020.github.io/wuif-water/list.html"><img src="../../../../static/images/l2.png" alt=""></a>-->
<!--                <p>水管维修</p>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="https://sanjin2020.github.io/wuif-water/list.html"><img src="../../../../static/images/l3.png" alt=""></a>-->
<!--                <p>普通维修</p>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href=""><img src="../../../../static/images/l4.png" alt=""></a>-->
<!--                <p>龙头维修</p>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href=""><img src="../../../../static/images/l5.png" alt=""></a>-->
<!--                <p>地漏疏通</p>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href=""><img src="../../../../static/images/l6.png" alt=""></a>-->
<!--                <p>阀门维修</p>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href=""><img src="../../../../static/images/l7.png" alt=""></a>-->
<!--                <p>下水疏通</p>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href=""><img src="../../../../static/images/l8.png" alt=""></a>-->
<!--                <p>其它</p>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href=""><img src="" alt=""></a>-->
<!--                <p></p>-->
<!--            </li>-->
<!--        </div>-->
    </div>
</template>

<script>
    export default {
        name: "index",
        data: () => ({
            imgUrls: [
                '../../../../static/images/banner.png',
                '../../../../static/images/banner.png',
                '../../../../static/images/banner.png'
            ],
            indicatorDots: false,
            autoplay: false,
            interval: 5000,
            duration: 1000
        }),
        methods: {
            changeIndicatorDots: function(e) {
                this.setData({
                    indicatorDots: !this.data.indicatorDots
                })
            },
            changeAutoplay: function(e) {
                this.setData({
                    autoplay: !this.data.autoplay
                })
            },
            intervalChange: function(e) {
                this.setData({
                    interval: e.detail.value
                })
            },
            durationChange: function(e) {
                this.setData({
                    duration: e.detail.value
                })
            }
        },
        mounted() {
        }
    }
</script>

<style scoped>
    @import "index.css";
</style>