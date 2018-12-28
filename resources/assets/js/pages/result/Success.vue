<template>
    <a-card :bordered="false">
      <result :is-success="true" :description="description" :title="title">
        <template slot="action">
          <a-button type="primary" @click="toAppintmentList">返回列表</a-button>
          <a-button style="margin-left: 8px" @click="toAppintment">查看预约</a-button>
        </template>
        <div>
          <div style="font-size: 16px; color: rgba(0, 0, 0, 0.85); font-weight: 500; margin-bottom: 20px;">项目名称</div>
          <detail-list size="small" style="margin-bottom: 8px">
            <detail-list-item term="预约ID">{{this.appointment.id}}</detail-list-item>
            <detail-list-item term="预约人">{{this.appointment.student.name}}</detail-list-item>
            <detail-list-item term="预约教师">{{this.appointment.faculty.name}}</detail-list-item>
            <detail-list-item term="预约时间">{{this.appointment.start_time.substr(0, 16)}} ~ {{this.appointment.end_time.substr(0, 16)}}</detail-list-item>
          </detail-list>
          <a-steps :current="1" progressDot>
            <a-step title="创建预约">
              <a-step-item-group slot="description">
                <a-step-item :title="this.appointment.student.name"/>
                <a-step-item :title="this.appointment.created_at.date.substr(0, 19)"/>
              </a-step-item-group>
            </a-step>
            <a-step title="老师审核">
              <a-step-item-group slot="description">
                <a-step-item :title="this.appointment.faculty.name"/>
              </a-step-item-group>
            </a-step>
            <a-step title="完成" ></a-step>
          </a-steps>
        </div>
      </result>
    </a-card>
</template>

<script>
import Result from '../../components/result/Result'
import ACard from 'ant-design-vue/es/card/Card'
import AButton from 'ant-design-vue/es/button/button'
import ACol from 'ant-design-vue/es/grid/Col'
import ARow from 'ant-design-vue/es/grid/Row'
import ASteps from 'ant-design-vue/es/steps/index'
import AIcon from 'ant-design-vue/es/icon/icon'
import DetailList from '../../components/tool/DetailList'
import AStepItem from '../../components/tool/AStepItem'

const AStep = ASteps.Step
const AStepItemGroup = AStepItem.Group
const DetailListItem = DetailList.Item
export default {
  name: 'Success',
  components: {
    AStepItemGroup,
    AStepItem,
    DetailListItem,
    DetailList,
    AIcon,
    AStep,
    ASteps,
    ARow,
    ACol,
    AButton,
    ACard,
    Result
  },
  created() {
    this.getAppointment()
  },
  data () {
    return {
      title: '创建成功',
      description: '您已经成功创建了预约，请等待老师审核。',
      id: this.$route.params.id,
      appointment: {},
    }
  },
  methods: {
    toAppintmentList () {
      this.$router.push('/appointments/list')
    },
    toAppintment () {
      this.$router.push('/appointment/'+this.id)
    },
    getAppointment() {
      var t = this
      this.$axios.get('/api/appointment/' + this.id)
          .then(function (response) {
            t.appointment = response.data
          })
          .catch(err => {
            console.log("Error: " + JSON.stringify(err));
          });
    }
  }
}
</script>

<style>
.step-item {
  text-align: center;
}
.detail-list > .ant-row > div {
  width: 21%;
}
.detail-list > .ant-row > div:last-child {
  width: 35%;
}
</style>
