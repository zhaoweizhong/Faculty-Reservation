<template>
    <div>
        <div class="search-div">
            <div class="search-logo">
                <img width="375" src="../../../images/logo.svg" />
            </div>
            <div class="search-bar">
                <a-input-search style="width: 600px; height: 45px;" placeholder="请输入..." size="large" enterButton="搜索" />
            </div>
        </div>
    </div>
</template>

<script>
import AInputSearch from 'ant-design-vue/es/input/Search'

export default {
    name: 'Search',
    components: {AInputSearch},
  mounted: function() {
    var divHeight = window.getComputedStyle($('.ant-layout-content')[0]).getPropertyValue('height')
    $('.search-div').eq(0).css("margin-top", "calc(" + divHeight + "/3.6)")
  }
}
</script>


<style lang="less" scoped>
/*.search-input {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -160px;
    margin-top: -170px;
}*/
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
.ant-input-suffix > .ant-btn-lg {
    height: 44px!important;
}
</style>

