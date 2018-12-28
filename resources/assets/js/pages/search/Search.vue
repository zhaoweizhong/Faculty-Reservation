<template>
  <div>
    <div class="search-div">
      <div class="search-logo">
        <img width="375" src="../../../images/logo.svg">
      </div>
      <div class="search-bar">
        <a-input-search
          style="width: 600px; height: 45px;"
          placeholder="输入教师姓名、院系、研究方向、工号搜索..."
          size="large"
          enterButton="搜索"
          @search="onSearch"
        />
      </div>
    </div>
  </div>
</template>

<script>
import AInputSearch from "ant-design-vue/es/input/Search";
import AForm from "ant-design-vue/es/form/Form";

export default {
  name: "Search",
  components: { AInputSearch, AForm },
  methods: {
    setHeight() {
      var divHeight = window
        .getComputedStyle($(".ant-layout-content")[0])
        .getPropertyValue("height");
      $(".search-div")
        .eq(0)
        .css("margin-top", "calc(" + divHeight + "/3.6)");
    },
    onSearch (value) {
      this.$router.push('/search/' + value)
    }
  }
};
</script>


<style lang="less" scoped>
.search-div {
  padding-top: 10vw;
}
.search-logo {
  margin-bottom: 40px;
  text-align: center;
}
.search-bar {
  height: 100%;
  text-align: center;
}
</style>

<style>
.search-bar > span > .ant-input-suffix > .ant-btn-lg {
  height: 44px !important;
}
</style>

