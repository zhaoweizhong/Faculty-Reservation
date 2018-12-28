<template>
  <div>
    <a-list
      :grid="{ gutter: 24, xl: 4, lg: 3, md: 3, sm: 2, xs: 1 }"
    >
       <a-list-item style="padding: 0 12px" v-for="searchResult in result" :key="searchResult.id">
        <a-card>
          <a-card-meta class="card-name" :title="searchResult.name" :description="searchResult.department" @click="redirectProfile(searchResult.id)">
            <a-avatar slot="avatar" :src="searchResult.avatar_url" size="large" />
          </a-card-meta>
            <a-tooltip class="tool"  title="详情" slot="actions" @click="redirectProfile(searchResult.id)">
              <a-icon type="user" />
            </a-tooltip>
            <a-tooltip class="tool"  title="发消息" slot="actions" @click="redirectMessage(searchResult.sid)">
              <a-icon type="message" />
            </a-tooltip>
            <a-tooltip class="tool"  title="预约" slot="actions" @click="redirectAppointment(searchResult.sid)">
              <a-icon type="schedule" />
            </a-tooltip>
          <div class="content">
            <div>
              <span>研究方向：</span>
              <span>{{ searchResult.fields }}</span>
            </div>
            <div>
              <span>个人介绍：</span>
              <span>{{ searchResult.intro }}</span>
            </div>
          </div>
        </a-card>
      </a-list-item>
    </a-list>
    <div class="list-pagination">
        <a-pagination v-model="currentPage" @change="handlePageChange" :total="this.pagination.total" :pageSize="this.pagination.per_page"/>
    </div>
  </div>
</template>

<script>
import ACard from 'ant-design-vue/es/card/Card'
import AList from 'ant-design-vue/es/list'
import AListItem from 'ant-design-vue/es/list/Item'
import ACardMeta from 'ant-design-vue/es/card/Meta'
import AAvatar from 'ant-design-vue/es/avatar/Avatar'
import ATooltip from 'ant-design-vue/es/tooltip/Tooltip'
import AIcon from 'ant-design-vue/es/icon/icon'
import ADropdown from 'ant-design-vue/es/dropdown'
import AMenu from 'ant-design-vue/es/menu/index'
import APagination from 'ant-design-vue/es/pagination'

const AMenuItem = AMenu.Item

export default {
  name: 'SearchList',
  components: {AMenuItem, AMenu, ADropdown, AIcon, ATooltip, AAvatar, ACardMeta, AListItem, AList, ACard, APagination},
  created() {
    this.doSearch(1)
  },
  props: {
    fatherKeyword: {
      type: String,
      default: () => {
        return ''
      }
    }
  },
  data() {
    return {
      keyword: this.fatherKeyword,
      currentPage: 1,
      detail: {},
      result: {},
      pagination: {},
    }
  },
  methods: {
    doSearch (page) {
      this.$emit('loading' , true);
      var t = this
      var formData = new FormData();
      formData.append("keyword", this.keyword);
      this.$axios
      .post("/api/users/faculty/search?page=" + page, formData)
      .then(resp => {
        console.log("resp " + JSON.stringify(resp));
        if (resp.status == 200) {
          t.result = resp.data.data
          t.detail = resp.data.meta.detail
          t.pagination = resp.data.meta.pagination
          t.$emit('loading' , false);
        } else {
          console.log("Error: " + JSON.stringify(res));
        }
      })
      .catch(err => {
        console.log("Error: " + JSON.stringify(err));
      });
    },
    redirectProfile (id) {
      this.$router.push('/user/' + id)
    },
    redirectMessage (sid) {
      this.$router.push('/messages/new/' + sid)
    },
    redirectAppointment (sid) {
      this.$router.push('/appointments/new/' + sid)
    },
    handlePageChange (page, pageSize) {
      this.currentPage = page,
      this.doSearch(page)
    }
  },
  watch: {
    '$route' (to, from) {
      this.keyword = to.params.keyword
      this.doSearch(1)
    }
  },
}
</script>

<style>
.card-name {
  position: relative;
  cursor: pointer;
}
.card-name > .ant-card-meta-detail {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  margin-left: 56px;
}
.card-name > .ant-card-meta-detail > .ant-card-meta-title {
  line-height: 18px;
  margin-bottom: 2px;
}
.card-name > .ant-card-meta-detail > .ant-card-meta-description {
  font-size: 10px;
}
</style>


<style lang="less" scoped>
  .clearfix() {
    zoom: 1;
    &:before,
    &:after {
      content: ' ';
      display: table;
    }
    &:after {
      clear: both;
      visibility: hidden;
      font-size: 0;
      height: 0;
    }
  }
  .content {
    font-size: 13px;
    margin-top: 20px;
    div {
      position: relative;
      margin-bottom: 5px;
      span:first-child {
        color: rgba(0, 0, 0, 0.85);
        float: left;
      }
      span:last-child {
        color: rgba(0, 0, 0, 0.65);
      }
    }
    div:last-child {
      span:last-child {
        margin-bottom: 0;
      }
    }
  }
  .list-pagination {
    margin-top: 15px;
    text-align: center;
  }
</style>
