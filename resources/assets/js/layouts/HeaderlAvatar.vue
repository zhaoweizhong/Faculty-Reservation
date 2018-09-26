<template>
  <a-dropdown style="display: inline-block; height: 100%; vertical-align: initial" >
    <span style="cursor: pointer">
      <a-avatar class="avatar" size="small" shape="circle" :src="currUser.avatar_url"/>
      <span>{{currUser.name}}</span>
    </span>
    <a-menu style="width: 150px" slot="overlay">
      <a-menu-item @click="profile">
        <a-icon type="user" />
        <span>个人中心</span>
      </a-menu-item>
      <a-menu-item @click="settings">
        <a-icon type="setting" />
        <span>个人设置</span>
      </a-menu-item>
      <a-menu-divider />
      <a-menu-item @click="logout">
        <a-icon type="poweroff" />
        <span>退出登录</span>
      </a-menu-item>
    </a-menu>
  </a-dropdown>
</template>

<script>
import ADropdown from 'ant-design-vue/es/dropdown'
import AAvatar from 'ant-design-vue/es/avatar/Avatar'
import AIcon from 'ant-design-vue/es/icon/icon'
import AMenu from 'ant-design-vue/es/menu/index'

const AMenuItem = AMenu.Item
const AMenuDivider = AMenu.Divider

export default {
  name: 'HeaderAvatar',
  components: {AMenu, AMenuItem, AMenuDivider, AIcon, AAvatar, ADropdown},
  computed: {
    currUser () {
      return this.$store.state.account.user
    }
  },
  methods: {
      logout() {
          this.$store.commit('account/logout')
          this.$axios.defaults.headers.common['Authorization'] = ''
          this.$router.push('/login')
          this.$message.success('您已成功退出')
      },
      profile() {
          this.$router.push('/user/profile')
      },
      settings() {
          this.$router.push('/user/settings')
      }
  }
 }
</script>

<style lang="less" scoped>
  .avatar{
    margin: 20px 4px 20px 0;
    color: #1890ff;
    background: hsla(0,0%,100%,.85);
    vertical-align: middle;
  }
</style>
