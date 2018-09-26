<template>
<page-layout title="个人设置">
    <a-card :bordered="false">
        <div class="info">
            <div class="avatar" id="avatar">
                <div class="avatar-image">
                    <a-avatar shape="square" :size="140" :src="currUser.avatar_url" />
                </div>
                <label class="avatar-wrapper">
                    <div class="mask mask-hidden" id="mask">
                        <div class="mask-inner"></div>
                        <div class="mask-content">
                            <svg class="Zi Zi--Camera UserAvatarEditor-cameraIcon" fill="currentColor" viewBox="0 0 24 24" width="36" height="36"><path d="M20.094 6S22 6 22 8v10.017S22 20 19 20H4.036S2 20 2 18V7.967S2 6 4 6h3s1-2 2-2h6c1 0 2 2 2 2h3.094zM12 16a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7zm0 1.5a5 5 0 1 0-.001-10.001A5 5 0 0 0 12 17.5zm7.5-8a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" fill-rule="evenodd"></path></svg>
                            <div class="mask-text">修改我的头像</div>
                        </div>
                    </div>
                </label>
            </div>
            <div class="content">
                <div class="name">
                    <span>{{ currUser.name }}</span>
                    <a-tag color="#87d068" v-if="currUser.type=='student'"><a-icon type="user" /> 学生</a-tag>
                    <a-tag color="#2db7f5" v-else><a-icon type="user" /> 教师</a-tag>
                </div>
                <div class="detail">
                    <div v-if="currUser.type=='student'">
                        <div>
                            <a-icon type="mail" />
                            <span>{{ currUser.email }}</span>
                        </div>
                        <div>
                            <a-icon type="book" />
                            <span>{{ currUser.department}}</span><span class="divider"></span><span>{{ currUser.major}}</span>
                        </div>
                    </div>
                    <div v-else>
                        <div>
                            <a-icon type="mail" />
                            <span>{{ currUser.email }}</span>
                        </div>
                        <div>
                            <a-icon type="book" />
                            <span>{{ currUser.department}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="edit">
                <router-link to="/user/settings">
                    <a-button type="dashed" icon="edit" size="default">编辑个人资料</a-button>
                </router-link>
            </div>
        </div>

        <a-divider style="margin-bottom: 32px" />
        <detail-list title="用户信息">
            <detail-list-item term="用户姓名">付小小</detail-list-item>
            <detail-list-item term="联系电话">18100000001</detail-list-item>
            <detail-list-item term="常用快递">菜鸟仓储</detail-list-item>
            <detail-list-item term="取货地址">浙江省杭州市西湖区万塘路19号</detail-list-item>
            <detail-list-item term="备注">无</detail-list-item>
        </detail-list>
        <a-divider style="margin-bottom: 32px" />
        <div class="title">退货商品</div>
        <a-table style="margin-bottom: 24px" :columns="goodsColumns" :dataSource="goodsData" :pagination="false">
        </a-table>
        <div class="title">退货进度</div>
        <a-table :columns="scheduleColumns" :dataSource="scheduleData" :pagination="false">
        </a-table>
    </a-card>
</page-layout>
</template>

<script>
import ACard from 'ant-design-vue/es/card/Card'
import AButton from 'ant-design-vue/es/button/button'
import ATag from 'ant-design-vue/es/tag/Tag'
import ATooltip from 'ant-design-vue/es/tooltip/Tooltip'
import AAvatar from 'ant-design-vue/es/avatar/Avatar'
import AIcon from 'ant-design-vue/es/icon/icon'
import DetailList from '../../components/tool/DetailList'
import ADivider from 'ant-design-vue/es/divider/index'
import ATable from 'ant-design-vue/es/table'
import PageLayout from '../../layouts/PageLayout'

const DetailListItem = DetailList.Item

const goodsColumns = [{
        title: '商品编号',
        dataIndex: 'id',
        key: 'id'
    },
    {
        title: '商品名称',
        dataIndex: 'name',
        key: 'name'
    },
    {
        title: '商品条码',
        dataIndex: 'barcode',
        key: 'barcode'
    },
    {
        title: '单价',
        dataIndex: 'price',
        key: 'price',
        align: 'right'
    },
    {
        title: '数量（件）',
        dataIndex: 'num',
        key: 'num',
        align: 'right'
    },
    {
        title: '金额',
        dataIndex: 'amount',
        key: 'amount',
        align: 'right'
    }
]

const goodsData = [{
        id: '1234561',
        name: '矿泉水 550ml',
        barcode: '12421432143214321',
        price: '2.00',
        num: '1',
        amount: '2.00'
    },
    {
        id: '1234562',
        name: '凉茶 300ml',
        barcode: '12421432143214322',
        price: '3.00',
        num: '2',
        amount: '6.00'
    },
    {
        id: '1234563',
        name: '好吃的薯片',
        barcode: '12421432143214323',
        price: '7.00',
        num: '4',
        amount: '28.00'
    },
    {
        id: '1234564',
        name: '特别好吃的蛋卷',
        barcode: '12421432143214324',
        price: '8.50',
        num: '3',
        amount: '25.50'
    }
]

const scheduleColumns = [{
        title: '时间',
        dataIndex: 'time',
        key: 'time'
    },
    {
        title: '当前进度',
        dataIndex: 'rate',
        key: 'rate'
    },
    {
        title: '状态',
        dataIndex: 'status',
        key: 'status'
    },
    {
        title: '操作员ID',
        dataIndex: 'operator',
        key: 'operator'
    },
    {
        title: '耗时',
        dataIndex: 'cost',
        key: 'cost'
    }
]

const scheduleData = [{
        key: '1',
        time: '2017-10-01 14:10',
        rate: '联系客户',
        status: 'processing',
        operator: '取货员 ID1234',
        cost: '5mins'
    },
    {
        key: '2',
        time: '2017-10-01 14:05',
        rate: '取货员出发',
        status: 'success',
        operator: '取货员 ID1234',
        cost: '1h'
    },
    {
        key: '3',
        time: '2017-10-01 13:05',
        rate: '取货员接单',
        status: 'success',
        operator: '取货员 ID1234',
        cost: '5mins'
    },
    {
        key: '4',
        time: '2017-10-01 13:00',
        rate: '申请审批通过',
        status: 'success',
        operator: '系统',
        cost: '1h'
    },
    {
        key: '5',
        time: '2017-10-01 12:00',
        rate: '发起退货申请',
        status: 'success',
        operator: '用户',
        cost: '5mins'
    }
]

export default {
    name: 'BasicDetail',
    components: {
        PageLayout,
        ATable,
        ADivider,
        DetailListItem,
        DetailList,
        AButton,
        AAvatar,
        ATooltip,
        ACard,
        AIcon,
        ATag
    },
    computed: {
      currUser () {
        return this.$store.state.account.user
      }
    },
    mounted() {
        $("#avatar").hover(function(){
            $("#mask").removeClass("mask-hidden");
        },function(){
            $("#mask").addClass("mask-hidden");
        });
    },
    data() {
        return {
            goodsColumns,
            goodsData,
            scheduleColumns,
            scheduleData
        }
    }
}



</script>

<style lang="less" scoped>
.fa {
    color: #8590a6
}
.title {
    color: rgba(0,0,0,.85);
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 16px;
}

.info {
    position: relative;
    min-height: 140px;
}

.avatar {
    float: left;
    width: 140px;
    .avatar-wrapper {
        cursor: pointer;
    }
    .mask {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1;
        width: 140px;
        transition: opacity .2s ease-in;
    }
    .mask-hidden {
        pointer-events: none;
        opacity: 0;
    }
    .mask-inner {
        z-index: 4;
        border-radius: 4px;
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        opacity: .4;
        background: #1a1a1a;
        box-sizing: border-box;
    }
    .mask-content {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 5;
        color: #fff;
        text-align: center;
        -webkit-transform: translate(-50%,-50%);
        transform: translate(-50%,-50%);
    }
    .mask-text {
        white-space: nowrap;
    }
}

.content {
    padding-top: 40px;
    padding-left: 180px;
}

.name {
    margin-bottom: 13px;
    span {
        color: #000;
        font-size: 30px;
        font-weight: 600;
        font-synthesis: style;
        line-height: 30px;
    }

    .ant-tag {
        font-size: 13px;
        margin-left: 10px;
        height: 23px;
        vertical-align: super;
    }
}

.detail > div > div {
    margin-bottom: 4px;
    font-size: 15px;
    i {
        margin-right: 5px;
    }
    .divider {
        display: inline-block;
        width: 1px;
        height: 11px;
        margin: 0 8px;
        background: #ebebeb;
    }
}

.edit {
    position: absolute;
    float: right;
    top: 0;
    right: 0;
    a > button {
        height: 37px;
    }
}
</style>
