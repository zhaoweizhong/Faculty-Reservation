<template>
  <div>
    <a-card :bordered="false">
      <a-row>
        <a-col :sm="8" :xs="24">
          <head-info title="我的预约" :content="this.detail.total" :bordered="true"/>
        </a-col>
        <a-col :sm="8" :xs="24">
          <head-info title="等待审核预约" :content="this.detail.pending" :bordered="true"/>
        </a-col>
        <a-col :sm="8" :xs="24">
          <head-info title="已确认预约" :content="this.detail.confirmed"/>
        </a-col>
      </a-row>
    </a-card>
    <a-card
      style="margin-top: 24px"
      :bordered="false"
      title="预约列表"
    >
      <div slot="extra">
        <a-radio-group v-model="filter" @change="onFilterChange" buttonStyle="solid">
          <a-radio-button value="all">全部</a-radio-button>
          <a-radio-button value="confirmed">已确认</a-radio-button>
          <a-radio-button value="pending">待审核</a-radio-button>
          <a-radio-button value="refused">已拒绝</a-radio-button>
          <a-radio-button value="cancelled">已取消</a-radio-button>
        </a-radio-group>
      </div>
      <a-spin tip="正在加载..." :spinning="loading">
        <a-list size="large">
          <a-list-item v-for="pageResult in result" :key="pageResult.id">
            <a-list-item-meta
              :description="pageResult.content"
            >
              <a-avatar slot="avatar" size="large" shape="square" :src="pageResult.faculty.avatar_url"/>
              <router-link :to="'/user/'+pageResult.faculty.id" slot="title">{{pageResult.faculty.name}}</router-link>
            </a-list-item-meta>
            <div slot="actions">
              <router-link :to="'/appointment/' + pageResult.id">查看</router-link>
            </div>
            <div v-if="currentUser.type === 'student'" slot="actions">
              <a-dropdown>
                <a-menu slot="overlay">
                  <a-menu-item><router-link :to="'/appointment/' + pageResult.id">查看</router-link></a-menu-item>
                  <a-menu-item v-if="(pageResult.status != 'cancelled') && ( pageResult.status != 'refused')" @click="setReservationStatus(pageResult, 'cancel')"><a>取消</a></a-menu-item>
                </a-menu>
                <a>更多<a-icon type="down"/></a>
              </a-dropdown>
            </div>
            <div v-else-if="currentUser.type === 'faculty'" slot="actions">
              <a-dropdown v-if="(pageResult.status != 'cancelled')">
                <a-menu slot="overlay">
                  <a-menu-item v-if="( pageResult.status != 'confirmed')" @click="setReservationStatus(pageResult, 'confirm')"><a>确认</a></a-menu-item>
                  <a-menu-item v-if="( pageResult.status != 'refused')" @click="setReservationStatus(pageResult, 'refuse')"><a>拒绝</a></a-menu-item>
                  <a-menu-item v-if="( pageResult.status != 'refused')" @click="setReservationStatus(pageResult, 'cancel')"><a>取消</a></a-menu-item>
                </a-menu>
                <a>审核<a-icon type="down"/></a>
              </a-dropdown>
              <a-dropdown v-else disabled>
                <a>审核<a-icon type="down"/></a>
              </a-dropdown>
            </div>
            <div class="list-content">
              <div class="list-content-item">
                <span>开始时间</span>
                <p>{{pageResult.start_time}}</p>
              </div>
              <div class="list-content-item">
                <span>结束时间</span>
                <p>{{pageResult.end_time}}</p>
              </div>
              <div class="list-content-item">
                <a-tag v-if="pageResult.status == 'confirmed'" color="#87d068">已确认</a-tag>
                <a-tag v-else-if="pageResult.status == 'pending'" color="orange">待审核</a-tag>
                <a-tooltip v-else-if="pageResult.status == 'refused'" placement="top">
                  <template slot="title">
                    <span>{{pageResult.info}}</span>
                  </template>
                  <a-tag color="#f50">已拒绝</a-tag>
                </a-tooltip>
                <a-tag v-else-if="pageResult.status == 'cancelled'" color="#B0BEC5">已取消</a-tag>
              </div>
            </div>
          </a-list-item>
        </a-list>
      </a-spin>
      <div class="list-pagination">
        <a-pagination v-model="currentPage" @change="handlePageChange" :total="this.pagination.total" :pageSize="this.pagination.per_page"/>
      </div>
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
import APagination from 'ant-design-vue/es/pagination'
import ASpin from 'ant-design-vue/es/spin/Spin'
import ATag from "ant-design-vue/es/tag/Tag";
import ATooltip from 'ant-design-vue/es/tooltip/Tooltip'
import { getCookie } from "tiny-cookie";

const AListItemMeta = AListItem.Meta
const AMenuItem = AMenu.Item
const ARadioButton = ARadio.Button
export default {
  name: 'AppointmentList',
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
    APagination,
    ASpin,
    ATag,
    ATooltip,
  },
  created() {
    this.getPage(1, 'all')
    var currentUser = this.currentUser
  },
  data() {
    return {
      currentUser: JSON.parse(getCookie("user")),
      filter: 'all',
      loading: false,
      currentPage: 1,
      detail: {},
      result: {},
      pagination: {},
    }
  },
  methods: {
    getPage(page, filter) {
      this.loading = true
      var t = this
      axios.defaults.headers.common["Authorization"] = "Bearer " + getCookie("token");
      this.$axios.get('/api/user/' + this.currentUser.id + '/appointments?filter=' + filter + '&page=' + page)
          .then(function (response) {
            t.result = response.data.data
            t.detail = response.data.meta.detail
            t.pagination = response.data.meta.pagination
            t.loading = false
          })
          .catch(err => {
            console.log("Error: " + JSON.stringify(err));
          });
    },
    onFilterChange(e) {
      this.filter = e.target.value
      this.currentPage = 1
      this.getPage(this.currentPage, e.target.value)
    },
    handlePageChange(page, pageSize) {
      this.currentPage = page
      this.getPage(page, this.filter)
    },
    setReservationStatus(pageResult, status) {
      var formData = new FormData();
      formData.append("status", status);
      this.$axios
          .post("/api/appointment/" + pageResult.id + "/status", formData)
          .then(resp => {
            let res = resp.data;
            if (resp.status == 200) {
              this.$message.success("操作成功");
              if (status == 'cancel') {
                pageResult.status = 'cancelled'
              } else if (status == 'refuse') {
                pageResult.status = 'refused'
              } else if (status == 'confirm') {
                pageResult.status = 'confirmed'
              }
            } else {
              this.$message.error("操作失败");
              console.log("Error: " + JSON.stringify(res));
            }
          })
          .catch(err => {
            this.$message.error(err.response.data.message);
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

  .list-pagination {
    margin-top: 15px;
    text-align: center;
  }
</style>
<style>
.ant-spin-blur {
  filter: blur(3px);
}
</style>
