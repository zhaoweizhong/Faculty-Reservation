<template>
  <a-spin tip="正在加载..." :spinning="loading">
    <a-card :bordered="false">
      <detail-list>
        <router-link :to="'/user/' + this.message.sender.id"><detail-list-item term="发件人"><a>{{this.message.sender.name}}</a></detail-list-item></router-link>
        <router-link :to="'/user/' + this.message.receiver.id"><detail-list-item term="收件人"><a>{{this.message.receiver.name}}</a></detail-list-item></router-link>
        <detail-list-item term="发送时间">{{this.message.created_at}}</detail-list-item>
      </detail-list>
      <detail-list title="消息详情">
        <p>{{this.message.content}}</p>
      </detail-list>
      <a-divider style="margin-bottom: 20px"/>
      <a-card type="inner" title="回复">
        <div v-for="reply in this.message.replies" :key="reply.id">
          <detail-list title="组名称" size="small">
            <router-link :to="'/user/1'"><detail-list-item term="发件人"><a>钟兆玮</a></detail-list-item></router-link>
            <router-link :to="'/user/33'"><detail-list-item term="收件人"><a>谭斌</a></detail-list-item></router-link>
            <detail-list-item term="所属部门">XX公司-YY部</detail-list-item>
            <detail-list-item term="过期时间">2018-08-08</detail-list-item>
            <detail-list-item term="描述">这段描述很长很长很长很长很长很长很长很长很长很长很长很长很长很长...</detail-list-item>
          </detail-list>
          <a-divider style="margin: 16px 0" />
        </div>
      </a-card>
    </a-card>
  </a-spin>
</template>

<script>
import ACard from 'ant-design-vue/es/card/Card'
import ATooltip from 'ant-design-vue/es/tooltip/Tooltip'
import AAvatar from 'ant-design-vue/es/avatar/Avatar'
import DetailList from '../../components/tool/DetailList'
import ADivider from 'ant-design-vue/es/divider/index'
import ATable from 'ant-design-vue/es/table'
import PageLayout from '../../layouts/PageLayout'
import ASpin from 'ant-design-vue/es/spin/Spin'
import { getCookie } from "tiny-cookie";

const DetailListItem = DetailList.Item

export default {
  name: 'MessageDetail',
  components: {PageLayout, ATable, ADivider, DetailListItem, DetailList, AAvatar, ATooltip, ACard, ASpin},
  created() {
    this.getMessage()
    this.setRead()
    var currentUser = this.currentUser
  },
  data () {
    return {
      currentUser: JSON.parse(getCookie("user")),
      id: this.$route.params.id,
      message: {
        id: '',
        content: '',
        reply_src_id: '',
        reply_id: '[]',
        sender: {
          name: '',
          id: '',
        },
        receiver: {
          name: '',
          id: '',
        },
        created_at: {
          date: ''
        },
        replies: [{
          id: '',
          content: '',
          reply_src_id: '',
          reply_id: '',
          sender: {
            name: '',
            id: '',
          },
          receiver: {
            name: '',
            id: '',
          },
          created_at: {
            date: ''
          },
        }]
      },
      loading: false,
    }
  },
  methods: {
    getMessage() {
      this.loading = true
      var t = this
      axios.defaults.headers.common["Authorization"] = "Bearer " + getCookie("token");
      this.$axios.get('/api/message/' + this.id)
          .then(function (response) {
            t.message = response.data
            t.loading = false
          })
          .catch(err => {
            console.log("Error: " + JSON.stringify(err));
          });
    },
    setRead() {
      var t = this
      axios.defaults.headers.common["Authorization"] = "Bearer " + getCookie("token");
      this.$axios.get('/api/message/' + this.id + '/read')
          .then(function (response) {
            console.log("read: " + t.id);
          })
          .catch(err => {
            console.log("Error: " + JSON.stringify(err));
          });
    },
    getUserName(id) {
      this.$axios.get('/api/user/' + id)
          .then(function (response) {
            var name = response.data.name
            //return name
          })
          .catch(err => {
            console.log("Error: " + JSON.stringify(err));
            return ''
          });
    }
  },
}
</script>

<style lang="less" scoped>
  .title {
    color: rgba(0,0,0,.85);
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 16px;
  }
</style>
