<template>
  <a-spin tip="正在加载..." :spinning="loading">
    <page-layout :title="'预约ID：' + this.appointment.id" logo="https://gw.alipayobjects.com/zos/rmsportal/nxkuOJlFJuAUhzlMTCEe.png">
      <detail-list size="small" :col="2" slot="headerContent" style="margin-left: 44px;">
        <detail-list-item term="预约人">{{this.appointment.student.name}}</detail-list-item>
        <detail-list-item term="预约教师">{{this.appointment.faculty.name}}</detail-list-item>
        <detail-list-item term="创建时间">{{this.appointment.created_at.date.substr(0, 19)}}</detail-list-item>
        <detail-list-item term="教师办公室">{{this.appointment.faculty.office}}</detail-list-item>
        <detail-list-item term="预约时间">{{this.appointment.start_time.substr(0, 16)}} ~ {{this.appointment.end_time.substr(0, 16)}}</detail-list-item>
        <detail-list-item term="备注">{{this.appointment.content}}</detail-list-item>
      </detail-list>
      <a-row slot="extra">
        <a-col :xs="24" :sm="24">
          <div class="text">状态</div>
          <div class="heading" v-if="this.appointment.status == 'confirmed'"><a-icon type="check-circle" style="color: #87d068"/> 已确认</div>
          <div class="heading" v-else-if="this.appointment.status == 'pending'"><a-icon type="clock-circle" style="color: #2db7f5"/> 待审核</div>
          <div class="heading" v-else-if="this.appointment.status == 'refused'"><a-icon type="exclamation-circle" style="color: #f50"/> 已拒绝</div>
          <div class="heading" v-else-if="this.appointment.status == 'cancelled'"><a-icon type="close-circle" style="color: #fa8c16"/> 已取消</div>
        </a-col>
      </a-row>
      <template v-if="currentUser.type === 'student'" slot="action">
        <a-button v-if="(this.appointment.status != 'cancelled') && ( this.appointment.status != 'refused')" @click="setReservationStatus('cancel')" type="danger">取消</a-button>
        <a-button v-else type="danger" disabled>取消</a-button>
      </template>
      <template v-if="currentUser.type === 'faculty'" slot="action">
        <a-button-group style="margin-right: 4px;">
          <a-button v-if="(this.appointment.status != 'cancelled') && ( this.appointment.status != 'refused')" @click="setReservationStatus('refuse')" type="danger">拒绝</a-button>
          <a-button v-else type="danger" disabled>拒绝</a-button>
          <a-button v-if="(this.appointment.status != 'cancelled') && ( this.appointment.status != 'refused')" @click="setReservationStatus('cancel')" type="danger">取消</a-button>
          <a-button v-else type="danger" disabled>取消</a-button>
        </a-button-group>
        <a-button v-if="(this.appointment.status != 'cancelled') && ( this.appointment.status != 'confirmed')" @click="setReservationStatus('confirm')" type="primary">确认</a-button>
        <a-button v-else type="primary" disabled>确认</a-button>
      </template>
      <a-card :bordered="false" title="流程进度">
        <a-steps :current="currentStep" :status="currentStatus">
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
          <a-step title="完成">
          </a-step>
        </a-steps>
      </a-card>
      <a-card style="margin-top: 24px" :bordered="false" title="详细信息">
        <detail-list title="学生信息">
          <router-link :to="'/user/' + this.appointment.student.id"><detail-list-item term="学生姓名"><a>{{this.appointment.student.name}}</a></detail-list-item></router-link>
          <detail-list-item term="学号">{{this.appointment.student.sid}}</detail-list-item>
          <detail-list-item term="E-mail">{{this.appointment.student.email}}</detail-list-item>
          <detail-list-item term="GPA">{{this.appointment.student.gpa}}</detail-list-item>
          <detail-list-item term="院系">{{this.appointment.student.department}}</detail-list-item>
          <detail-list-item term="专业">{{this.appointment.student.major}}</detail-list-item>
          <detail-list-item term="兴趣方向">{{this.appointment.student.interested_fields}}</detail-list-item>
        </detail-list>
        <detail-list title="教师信息">
          <router-link :to="'/user/' + this.appointment.faculty.id"><detail-list-item term="教师姓名"><a>{{this.appointment.faculty.name}}</a></detail-list-item></router-link>
          <detail-list-item term="工号">{{this.appointment.faculty.sid}}</detail-list-item>
          <detail-list-item term="E-mail">{{this.appointment.faculty.email}}</detail-list-item>
          <detail-list-item term="办公室">{{this.appointment.faculty.office}}</detail-list-item>
          <detail-list-item term="院系">{{this.appointment.faculty.department}}</detail-list-item>
          <detail-list-item term="兴趣方向">{{this.appointment.faculty.fields}}</detail-list-item>
        </detail-list>
        <detail-list title="预约信息">
          <detail-list-item term="预约ID">{{this.appointment.id}}</detail-list-item>
          <detail-list-item term="备注">{{this.appointment.content}}</detail-list-item>
          <detail-list-item term="创建时间">{{this.appointment.created_at.date.substr(0, 19)}}</detail-list-item>
          <detail-list-item term="预约开始时间">{{this.appointment.start_time.substr(0, 16)}}</detail-list-item>
          <detail-list-item term="预约结束时间">{{this.appointment.end_time.substr(0, 16)}}</detail-list-item>
        </detail-list>
      </a-card>
    </page-layout>
  </a-spin>
</template>

<script>
import PageLayout from '../../layouts/PageLayout'
import AButtonGroup from 'ant-design-vue/es/button/button-group'
import AButton from 'ant-design-vue/es/button/button'
import AIcon from 'ant-design-vue/es/icon/icon'
import DetailList from '../../components/tool/DetailList'
import ARow from 'ant-design-vue/es/grid/Row'
import ACol from 'ant-design-vue/es/grid/Col'
import ACard from 'ant-design-vue/es/card/Card'
import ASteps from 'ant-design-vue/es/steps/index'
import AStepItem from '../../components/tool/AStepItem'
import ADivider from 'ant-design-vue/es/divider/index'
import ATable from 'ant-design-vue/es/table'
import ASpin from 'ant-design-vue/es/spin/Spin'
import { getCookie } from "tiny-cookie";

const DetailListItem = DetailList.Item
const AStep = ASteps.Step
const AStepItemGroup = AStepItem.Group

export default {
  name: 'AppointmentDetail',
  created() {
    this.getAppointment()
    var currentUser = this.currentUser
  },
  watch: {
    appointmentStatus: function () {
      if (this.appointmentStatus == "pending") {
        this.currentStep = 1
        this.currentStatus = 'process'
      } else if (this.appointmentStatus == "confirmed") {
        this.currentStep = 2
        this.currentStatus = 'finish'
      } else if (this.appointmentStatus == "cancelled") {
        this.currentStep = 1
        this.currentStatus = 'error'
      } else if (this.appointmentStatus == "refused") {
        this.currentStep = 2
        this.currentStatus = 'error'
      }
    }
  },
  data () {
    return {
      currentUser: JSON.parse(getCookie("user")),
      id: this.$route.params.id,
      appointment: {
        id: '',
        student: {
          name: ''
        },
        faculty: {
          name:'',
          office: ''
        },
        created_at: {
          date: ''
        },
        start_time: '',
        end_time: '',
        status: '',
        content: '',
      },
      appointmentStatus: '',
      currentStep: 0,
      currentStatus: 'process',
      loading: false,
    }
  },
  methods: {
    getAppointment() {
      this.loading = true
      var t = this
      this.$axios.get('/api/appointment/' + this.id)
          .then(function (response) {
            t.appointment = response.data
            t.appointmentStatus = response.data.status
            t.loading = false
          })
          .catch(err => {
            console.log("Error: " + JSON.stringify(err));
          });
    },
    setReservationStatus(status) {
      var formData = new FormData();
      formData.append("status", status);
      var t = this
      this.$axios
          .post("/api/appointment/" + t.appointment.id + "/status", formData)
          .then(resp => {
            let res = resp.data;
            if (resp.status == 200) {
              this.$message.success("操作成功");
              if (status == 'cancel') {
                t.appointment.status = 'cancelled'
                t.appointmentStatus = 'cancelled'
              } else if (status == 'refuse') {
                t.appointment.status = 'refused'
                t.appointmentStatus = 'refused'
              } else if (status == 'confirm') {
                t.appointment.status = 'confirmed'
                t.appointmentStatus = 'confirmed'
              }
            } else {
              this.$message.error("操作失败");
              console.log("Error: " + JSON.stringify(res));
            }
          })
          .catch(err => {
            this.$message.error(err.response.data.message);
            console.log("Error: " + JSON.stringify(err));
          });
    },
  },
  components: {
    ATable,
    ADivider,
    AStepItemGroup,
    AStepItem,
    AStep,
    ASteps,
    ACard,
    ACol,
    ARow,
    DetailListItem,
    DetailList,
    AIcon,
    AButton,
    AButtonGroup,
    PageLayout,
    ASpin,
    }
}
</script>

<style lang="less" scoped>
  .text{
    color: rgba(0,0,0,.45);
  }
  .heading{
    color: rgba(0,0,0,.85);
    font-size: 20px;
  }
  .nodata{
    color: rgba(0,0,0,.25);
    text-align: center;
    line-height: 64px;
    font-size: 16px;
    & :global{
      i {
        font-size: 24px;
        margin-right: 16px;
        position: relative;
        top: 3px;
      }
    }
  }
</style>

<style>
.detail-list > .ant-row > div {
  width: 33.333333%;
}
.detail-list > .ant-row > div:last-child {
  width: 33.333333%;
}
</style>
