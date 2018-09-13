<template>
    <div>
        <img src="https://cn.vuejs.org/images/logo.png">
        <div>
            <h1>{{ msg }}</h1>
            <el-button icon="edit" @click="startHacking">Hello Element</el-button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            msg: 'Vue.js'
        }
    },
    methods: {
        startHacking () {
            this.$notify({
                title: 'It works!',
                type: 'success',
                message: 'We\'ve laid the ground work for you. It\'s time for you to build something epic!',
                duration: 5000
            })
        }
    }
}
</script>

<style>
#app {
  font-family: Helvetica, sans-serif;
  text-align: center;
}
</style>
