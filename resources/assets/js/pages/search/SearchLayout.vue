<template>
  <div>
    <page-view></page-view>
    <div class="search-head">
      <div class="search-input">
        <a-input-search style="width: 522px" placeholder="输入教师姓名、院系、研究方向、工号搜索..." size="large" enterButton="搜索" @search="onSearch"/>
      </div>
    </div>
    <div class="search-content">
      <a-spin tip="正在加载..." :spinning="loading">
        <search-list :fatherKeyword="fatherKeyword" @loading="doLoading"></search-list>
      </a-spin>
    </div>
  </div>
</template>

<script>
import AInput from 'ant-design-vue/es/input/Input'
import AInputGroup from 'ant-design-vue/es/input/Group'
import AButton from 'ant-design-vue/es/button/button'
import AInputSearch from 'ant-design-vue/es/input/Search'
import ATabs from 'ant-design-vue/es/tabs'
import SearchList from "./SearchList"
import PageView from "../../layouts/PageView" 
import ASpin from 'ant-design-vue/es/spin/Spin'

const ATabPane = ATabs.TabPane

export default {
  name: 'SearchLayout',
  components: {PageView, ATabPane, ATabs, AInputSearch, AButton, AInputGroup, AInput, SearchList, ASpin},
  data() {
    return {
      loading: false,
      fatherKeyword: this.$route.params.keyword
    }
  },
  methods: {
    onSearch (value) {
      this.$router.push('/search/' + value)
    },
    doLoading (msg) {
      this.loading = msg
    },
  },
}
</script>

<style lang="less" scoped>
  .search-head{
    background-color: #fff;
    margin: -25px -24px -24px;
    .search-input{
      text-align: center;
      padding-bottom: 25px;
    }
  }

  .search-content{
    margin-top: 48px;
  }
</style>

<style>
.ant-spin-blur {
  filter: blur(3px);
}
</style>

