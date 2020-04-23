<template>
  <div style=" overflow: hidden;">
    <el-row>
      <el-col :span="18" :offset="0">
        <div
          id="dataShow"
          onmouseover="this.style.overflow='overlay'"
          onmouseout="this.style.overflow='hidden'"
          class="dataShow"
        >
          <div
            v-for="(item,index) in radioInfoList"
            :key="index"
            style="background-color: #f5f5f5; padding:24px;"
          >
            <el-row>
              <el-col :span="20" :offset="3">
                          <span class="aa">
                            {{ item.message1 }}
                          </span>
              </el-col>
              <el-col :span="1">
                          <span>
<!--                              <svg-icon slot="reference" icon-class="girl" style="font-size: 40px" />   &lt;!&ndash;对应头像&ndash;&gt;-->
                            <svg class="icon" aria-hidden="true" style="height: 3rem;width: 3rem">
                              <use xlink:href="#icon-icon-test"></use>
                            </svg>
                          </span>
              </el-col>
            </el-row>
            <br>
            <el-row>
              <el-col :span="1">
                          <span>
                            <svg class="icon" aria-hidden="true" style="height: 3rem;width: 3rem">
                              <use xlink:href="#icon-icon-test1"></use>
                            </svg>
                            <!--                              <svg-icon slot="reference" icon-class="person" style="font-size: 40px" />-->
                          </span>
              </el-col>
              <el-col :span="20">
                          <span class="bb">
                            {{ item.message2 }}
                          </span>
              </el-col>
            </el-row>
          </div>
        </div>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="18" :offset="0">
        <el-input
          id="condition"
          v-model="condition"
          type="textarea"
          autofocus
          clearable
          maxlength="100"
          show-word-limit
          :autosize="{minRows:6,maxRows:6}"
          resize="none"
          placeholder="在这里输入..."
          @keyup.enter.native="submitSearch"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  export default {

    name:"chatList",

    data() {
      return {
        isShow: true,
        isButton: false,
        radioTxt: '',
        condition: '',
        startRow: {
          dialogueId: '',
          robotSen: '',
          robotTime: '',
          customerSen: '',
          customerTime: ''
        },
        dialogueId: '',
        endRow: {},
        dataList: [], // 单次对话的信息
        radioInfoList: [{
          user: '周先生',
          robot: '专属客服小美',
          message1: '您好，周先生，我是你的专属客服',
          message2: '你好，有什么事情',
          time1: '2019-11-19 09:05:55',
          time2: '2019-11-19 09:06:55'
        }, {
          user: '周先生',
          robot: '专属客服小美',
          message1: '好的，我们稍后会把账单以短信的形式发给您，请您务必按照承诺在今晚六点之前还款，以免对您的账户及个人信用产生不良影响。我行会持续跟踪您的还款情况，如未按时到账将再次与您联系',
          message2: '这个，最近手头有点近，能缓两天吗，届时一定本息还完',
          time1: '2019-11-19 09:07:55',
          time2: '2019-11-19 09:08:55'
        }],
        visible: false,
        customer: '',
        userSrc: 'https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg',
        robotSrc: 'https://fuss10.elemecdn.com/1/8e/aeffeb4de74e2fde4bd74fc7b4486jpeg.jpeg',
        robotList: [
          'https://fuss10.elemecdn.com/1/8e/aeffeb4de74e2fde4bd74fc7b4486jpeg.jpeg'
        ],
        srcList: [
          'https://fuss10.elemecdn.com/8/27/f01c15bb73e1ef3793e64e6b7bbccjpeg.jpeg'
        ],
        browser: 'Chrome'
      }
    },
    created() {
      this.browser = this.myBrowser()
      console.log('当前浏览器：' + this.browser)
      this.startRow = {}
      this.customer = '王先生' + '\n' + '地区：浙江杭州'
    },
    methods: {
      // 滚动到底部
      scrollToBottom() {
        this.$nextTick(() => {
          const div = document.getElementById('dataShow')
          div.scrollTop = div.scrollHeight
        })
      },
      toRadioTalk() {
        this.$router.push({
          path: '/talkTest/talkList'
        })
      },
      start() {
        this.condition = ''
        this.endRow = {}
        this.dataList = []
        const data = {
          sysMap: {
            features: '开始对话'
          },
          parameterList:
            []
        }
        this.$request({
          url: '/talk/start',
          method: 'post',
          data
        }).then(res => {
          this.isButton = true
          this.endRow = res.retMap.talk
          this.radioInfoList.push(this.endRow)
          this.dataList.push(this.endRow)
          // Cookies.set('dialogueId', res.retMap.talk.dialogueId)
          this.dialogueId = res.retMap.talk.dialogueId
          // Cookies.set('talkRecord', this.radioInfoList)
          console.log(this.dialogueId)
          this.scrollToBottom()
        }).catch(e => {
          console.log(e)
          this.$message.error('未知错误，请联系开发人员...')
          this.isButton = false
        })
      },
      saveToTxt(id, list) {
        const data = {
          sysMap: {
            features: '写入txt'
          },
          parameterList:
            [
              id,
              list,
              this.$store.getters.name
            ]
        }
        this.$request({
          url: '/talk/writeToTxt',
          method: 'post',
          data
        }).then(res => {
          console.log(res.retCode)
          if (res.retCode === 'IF0000') {
            console.log('记录已保存')
          } else {
            console.log('记录保存失败')
          }
        })
      },
      // 去掉回车换行符
      clearBr(key) {
        key = key.replace(/<\/?.+?>/g, '')
        key = key.replace(/[\r\n]/g, '')
        return key
      },
      /*    keySubmit() {
        var test = this.condition
        console.log('---' + test)
        var tt = this.clearBr(test)
        console.log('-tt--' + tt)
        if (tt === '' || tt === undefined || tt === null) {
          this.$message.error('当前没有输入。。。')
          this.scrollToBottom()
        }
      },*/
      submitSearch() {
        this.condition = this.clearBr(this.condition)
        console.log('对话id' + this.dialogueId)
        if (this.condition === '' || this.condition === undefined || this.condition === null) {
          this.$message.error('当前没有输入。。。')
          this.scrollToBottom()
        } else if (this.dialogueId === undefined || this.dialogueId === '') {
          this.$message.error('请先start')
          this.condition = ''
        } else {
          console.log('id' + this.dialogueId)
          this.startRow = {}
          this.startRow.robotSen = null
          this.startRow.robotTime = null
          this.startRow.customerSen = this.condition
          this.startRow.dialogueId = this.dialogueId
          var d = new Date()
          this.startRow.customerTime = d.getFullYear() + '-' + ((d.getMonth() < 9 ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1))) +
            '-' + (d.getDate() < 10 ? '0' + d.getDate() : d.getDate()) + ' ' + (d.getHours() < 10 ? '0' + d.getHours() : d.getHours()) +
            ':' + (d.getMinutes() < 10 ? '0' + d.getMinutes() : d.getMinutes()) + ':' + (d.getSeconds() < 10 ? '0' + d.getSeconds() : d.getSeconds())
          // this.startRow.dialogueId = Cookies.get('dialogueId')
          this.radioInfoList.push(this.startRow)
          this.dataList.push(this.startRow)
          const data = {
            sysMap: {
              features: '对话细节'
            },
            parameterList:
              [
                this.startRow.dialogueId,
                this.startRow.customerSen,
                this.startRow.customerTime
              ]
          }
          this.$request({
            url: '/talk/talking',
            method: 'post',
            data
          }).then(res => {
            this.endRow = res.retMap.talk
            this.radioInfoList.push(this.endRow)
            this.dataList.push(this.endRow)
            this.scrollToBottom()
            this.condition = ''
            if (res.retCode === 'IF0002') {
              // this.$message.error('对话结束，记录已保存')
              this.saveToTxt(this.startRow.dialogueId, this.dataList)
              this.isButton = false
              this.startRow.dialogueId = ''
            }
          })
        }
      },
      myBrowser() {
        var userAgent = navigator.userAgent // 取得浏览器的userAgent字符串
        var isOpera = userAgent.indexOf('Opera') > -1 // 判断是否Opera浏览器
        var isIE = userAgent.indexOf('compatible') > -1 &&
          userAgent.indexOf('MSIE') > -1 && !isOpera // 判断是否IE浏览器
        var isEdge = userAgent.indexOf('Edge') > -1 // 判断是否IE的Edge浏览器
        var isFF = userAgent.indexOf('Firefox') > -1 // 判断是否Firefox浏览器
        var isSafari = userAgent.indexOf('Safari') > -1 &&
          userAgent.indexOf('Chrome') === -1 // 判断是否Safari浏览器
        var isChrome = userAgent.indexOf('Chrome') > -1 &&
          userAgent.indexOf('Safari') > -1 // 判断Chrome浏览器

        if (isIE) {
          var reIE = new RegExp('MSIE (\\d+\\.\\d+);')
          reIE.test(userAgent)
          var fIEVersion = parseFloat(RegExp['$1'])
          if (fIEVersion === 7) {
            return 'IE7'
          } else if (fIEVersion === 8) {
            return 'IE8'
          } else if (fIEVersion === 9) {
            return 'IE9'
          } else if (fIEVersion === 10) {
            return 'IE10'
          } else if (fIEVersion === 11) {
            return 'IE11'
          } else {
            return '0'
          }// IE版本过低
          // eslint-disable-next-line no-unreachable
          return 'IE'
        }
        if (isOpera) {
          return 'Opera'
        }
        if (isEdge) {
          return 'Edge'
        }
        if (isFF) {
          return 'FF'
        }
        if (isSafari) {
          return 'Safari'
        }
        if (isChrome) {
          return 'Chrome'
        }
      }
    }
  }
</script>

<style>
  .font{
    font-size: 16px;
    font-weight: bold;
    font-family: "微软雅黑 Light";
  }
  /*  <!--非Chrome 隐藏滚动条-->*/
  .dataShowFF{
    height:600px;
    overflow-y: hidden;
    overflow-x: hidden;
    margin-bottom: -14px;
    margin-top: 0px;
    background-color: #f5f5f5;
    scrollbar-width: none;
  }
  /*  <!--Chrome 隐藏滚动条-->*/
  .dataShowCM{
    height:600px;
    overflow-y: hidden;
    overflow-x: hidden;
    margin-bottom: -14px;
    margin-top: 0px;
    background-color: #f5f5f5;
  }::-webkit-scrollbar {
     display: none;
   }
  .aa{
    float: right;
    position: relative;
    display: inline-block;
    max-width: calc(40%);
    line-height: 2.1;
    min-height: 35px;
    font-size: 15px;
    padding: 6px 10px;
    text-align: left;
    word-break: break-all;
    background-color: #9eea6a;
    color: #000;
    border-radius: 4px;
    box-shadow: 0px 1px 7px -5px #000;
    border:0px solid #000;
    margin-top: 0;
    margin-right: 10px;
  }
  .aa:after {
    content: "";
    border-top: solid 5px #00800000;
    border-left: solid 10px #9eea6a;
    border-right: solid 10px #00800000;
    border-bottom: solid 5px #00800000;
    position: absolute;
    top: 10px;
    left: 100%;
  }
  .bb{
    display: inline-block;
    line-height:30px;
    font-style: normal;
    background-color: white;
    letter-spacing: 2px;
    position: relative;
    max-width: calc(40%);
    min-height: 35px;
    line-height: 2.1;
    font-size: 15px;
    padding: 6px 10px;
    text-align: left;
    word-break: break-all;
    border-radius: 4px;
    color: #000;
    box-shadow: 0px 1px 7px -5px #000;
    border:0px solid #000;
    margin-top: 0;
    margin-left: 10px;
  }
  .bb:after {
    content: "";
    border-top: solid 5px #00800000;
    border-left: solid 10px #00800000;
    border-right: solid 10px white;
    border-bottom: solid 5px #00800000;
    position: absolute;
    top: 10px;
    right: 100%;
    
  }
  #condition{
    border:1px solid #f4c08a;
  }
  #submit{
    border:0px solid #000;
    float: right;
  }
  #start{
    border:0px solid #000;
  }
</style>
