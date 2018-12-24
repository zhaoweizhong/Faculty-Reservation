<template>
  <div>
    <page-view></page-view>
    <div class="search-head">
      <div class="search-input">
        <a-input-search style="width: 522px" placeholder="请输入..." size="large" enterButton="搜索" />
      </div>
    </div>
    <div class="search-content">
      <search-list></search-list>
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

const ATabPane = ATabs.TabPane

export default {
  name: 'SearchLayout',
  components: {PageView, ATabPane, ATabs, AInputSearch, AButton, AInputGroup, AInput, SearchList}
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
