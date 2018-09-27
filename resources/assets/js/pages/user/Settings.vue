<template>
<page-layout title="个人设置">
    <a-card
        style="width:100%;"
        :bordered="false"
        class="settings"
    >
        <div class="main">
            <div class="col-left">
                <a-menu
                    style="width: 224px;float: left;"
                    :defaultSelectedKeys="['1']"
                    mode="inline"
                    @click="handleClick"
                >
                    <a-menu-item key="1">个人资料</a-menu-item>
                    <a-menu-item key="2">安全设置</a-menu-item>
                    <a-menu-item key="3">其他设置</a-menu-item>
                </a-menu>
            </div>

            <div v-if="this.current == '1'" class="col-right">
                <div class="title">
                    <span>个人资料</span>
                </div>
                <div class="profile-content">
                    <div class="left ant-col-md-8">
                        <a-form>
                            <a-form-item
                                :labelCol="{ span: 4 }"
                                :wrapperCol="{ span: 24 }"
                                label='姓名'
                                :colon='false'
                            >
                                <a-input placeholder='姓名' :value='currUser.name' disabled/>
                            </a-form-item>
                            <a-form-item
                                :labelCol="{ span: 4 }"
                                :wrapperCol="{ span: 24 }"
                                label='邮箱'
                                :colon='false'
                            >
                                <a-input placeholder='邮箱' :value='currUser.email' disabled/>
                            </a-form-item>
                            <a-form-item
                                :labelCol="{ span: 4 }"
                                :wrapperCol="{ span: 24 }"
                                label='院系'
                                :colon='false'
                            >
                                <a-input placeholder='院系' :value='currUser.department' disabled/>
                            </a-form-item>
                            <a-form-item
                                :labelCol="{ span: 4 }"
                                :wrapperCol="{ span: 24 }"
                                label='专业'
                                :colon='false'
                            >
                                <a-input placeholder='专业' :value='currUser.major' disabled/>
                            </a-form-item>
                            <a-form-item
                                :labelCol="{ span: 4 }"
                                :wrapperCol="{ span: 24 }"
                                label='GPA'
                                :colon='false'
                            >
                                <a-input placeholder='GPA' :value='currUser.gpa' disabled/>
                            </a-form-item>
                            <a-form-item
                                :labelCol="{ span: 8 }"
                                :wrapperCol="{ span: 24 }"
                                label='感兴趣的方向'
                                :colon='false'
                                fieldDecoratorId="interested_fields"
                                :fieldDecoratorOptions="{rules: [{ required: true, message: '请输入感兴趣的方向' }]}"
                            >
                                <a-textarea placeholder='感兴趣的方向...' :value='currUser.interested_fields' id='interested_fields' :autosize="{ minRows: 2 }"/>
                            </a-form-item>
                        </a-form>
                    </div>
                    <div class="right ant-col-md-16">

                    </div>
                </div>
            </div>
            <div v-else-if="this.current == '2'" class="col-right">
                <div class="title">
                    <span>安全设置</span>
                </div>
            </div>
            <div v-else="this.current == '3'" class="col-right">
                <div class="title">
                    <span>其他设置</span>
                </div>
            </div>
        </div>
    </a-card>
</page-layout>
</template>

<script>
import ACard from 'ant-design-vue/es/card/Card'
import AForm from 'ant-design-vue/es/form/Form'
import AFormItem from 'ant-design-vue/es/form/FormItem'
import AInput from 'ant-design-vue/es/input/Input'
import ATextarea from 'ant-design-vue/es/input/TextArea'
import AButton from 'ant-design-vue/es/button/button'
import ATag from 'ant-design-vue/es/tag/Tag'
import ATooltip from 'ant-design-vue/es/tooltip/Tooltip'
import AIcon from 'ant-design-vue/es/icon/icon'
import DetailList from '../../components/tool/DetailList'
import ADivider from 'ant-design-vue/es/divider/index'
import PageLayout from '../../layouts/PageLayout'
import AMenu from 'ant-design-vue/es/menu/index'

const AMenuItem = AMenu.Item
const DetailListItem = DetailList.Item

export default {
    name: 'BasicDetail',
    components: {
        AMenu,
        AMenuItem,
        PageLayout,
        ADivider,
        DetailListItem,
        DetailList,
        AButton,
        ATooltip,
        ACard,
        AInput,
        ATextarea,
        AFormItem,
        AForm,
        AIcon,
        ATag
    },
    data() {
        return {
            current: '1'
        }
    },
    computed: {
        currUser() {
            return this.$store.state.account.user
        }
    },
    methods: {
        handleClick(e) {
            this.current = e.key
        }
    }
}
</script>

<style lang="less">
.title {
    color: rgba(0,0,0,.85);
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 16px;
}

.settings .ant-card-body {
    padding-left: 0;
}

.settings .ant-menu-item-selected {
    font-weight: 700;
}

.main {
    display: flex;
    padding-top: 16px;
    .col-right {
        padding: 8px 40px;
        width: 100%;
        .title {
            color: rgba(0,0,0,.85);
            font-size: 20px;
            font-weight: 500;
            line-height: 28px;
            margin-bottom: 12px;
        }
    }
}

.profile-content {
    display: flex;
    .left{
        max-width: 448px;
        min-width: 224px;
        float: left;
        text-align: left;
        .ant-form-item {
            margin-bottom: 15px;
        }
        .ant-form-item-label {
            text-align: left;
            line-height: 2;
        }
    }
    .right {
        padding-left: 104px;
        position: relative;
    }
}
</style>
