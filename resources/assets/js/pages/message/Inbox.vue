<template>
  <div>
    <a-card
      style="margin-top: 24px"
      :bordered="false"
      title="消息列表"
    >
      <router-link to="/messages/new"><a-button type="dashed" style="width: 100%" icon="plus">新消息</a-button></router-link>
      <a-spin tip="正在加载..." :spinning="loading">
        <a-list size="large">
          <a-list-item v-for="message in messages" :key="message.id">
            <a-list-item-meta
              :description="message.content"
            >
              <a-avatar slot="avatar" size="large" shape="square" :src="message.sender.avatar_url"/>
              <router-link :to="'/user/'+message.sender.id" slot="title">{{message.sender.name}}</router-link>
            </a-list-item-meta>
            <div slot="actions">
              <router-link :to="'/message/' + message.id">查看</router-link>
            </div>
            <div class="list-content">
              <div class="list-content-item">
                <span>发送时间</span>
                <p>{{message.created_at}}</p>
              </div>
              <div class="list-content-item">
                <a-tag v-if="message.read == '1'" color="#87d068">已读</a-tag>
                <a-tag v-else-if="message.read == '0'" color="#f50">未读</a-tag>
              </div>
            </div>
          </a-list-item>
        </a-list>
      </a-spin>
    </a-card>
  </div>
</template>

<script>
import ACard from 'ant-design-vue/es/card/Card'
import ARow from 'ant-design-vue/es/grid/Row'
import ACol from 'ant-design-vue/es/grid/Col'
import HeadInfo from '../../components/tool/HeadInfo'
import AButton from 'ant-design-vue/es/button/button'
import AList from 'ant-design-vue/es/list/index'
import AListItem from 'ant-design-vue/es/list/Item'
import AAvatar from 'ant-design-vue/es/avatar/Avatar'
import AProgress from 'ant-design-vue/es/progress'
import ADropdown from 'ant-design-vue/es/dropdown'
import AMenu from 'ant-design-vue/es/menu/index'
import AIcon from 'ant-design-vue/es/icon/icon'
import AButtonGroup from 'ant-design-vue/es/button/button-group'
import AInput from 'ant-design-vue/es/input/Input'
import AInputSearch from 'ant-design-vue/es/input/Search'
import ARadioGroup from 'ant-design-vue/es/radio/Group'
import ARadio from 'ant-design-vue/es/radio'
import ASpin from 'ant-design-vue/es/spin/Spin'
import ATag from "ant-design-vue/es/tag/Tag";
import { getCookie } from "tiny-cookie";

const AListItemMeta = AListItem.Meta
const AMenuItem = AMenu.Item
const ARadioButton = ARadio.Button
export default {
  name: 'Inbox',
  components: {
    ARadioButton,
    ARadio,
    ARadioGroup,
    AInputSearch,
    AInput,
    AButtonGroup,
    AIcon,
    AMenuItem,
    AMenu,
    ADropdown,
    AProgress,
    AAvatar,
    AListItemMeta,
    AListItem,
    AList,
    AButton,
    HeadInfo,
    ACol,
    ARow,
    ACard,
    ASpin,
    ATag
    },
    created() {
      this.getPage(1,'d')
    },
    data() {
      return {
        messages: '',
        loading: false,
      }
    },
    methods: {
      getPage(page, filter) {
      this.loading = true
      var t = this
      axios.defaults.headers.common["Authorization"] = "Bearer " + getCookie("token");
      this.$axios.get('/api/messages/inbox')
          .then(function (response) {
            t.messages = response.data.data
            t.loading = false
          })
          .catch(err => {
            console.log("Error: " + JSON.stringify(err));
          });
    },
    },
}
</script>

<style lang="less" scoped>
  .ant-avatar-lg{
    width: 48px;
    height: 48px;
    line-height: 48px;
  }
  .list-content-item{
    color: rgba(0,0,0,.45);
    display: inline-block;
    vertical-align: middle;
    font-size: 14px;
    margin-left: 40px;
    span{
      line-height: 20px;
    }
    p{
      margin-top: 4px;
      margin-bottom: 0;
      line-height: 22px;
    }
  }
</style>
