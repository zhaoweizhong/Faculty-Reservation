<template>
<page-layout title="用户详情">
	<a-card :bordered="false">
		<div class="info">
			<div class="avatar" id="avatar">
				<div class="avatar-image">
					<a-avatar shape="square" :size="140" :src="userInfo.avatar_url" />
				</div>
			</div>
			<div class="info-content">
				<div class="name">
					<span>{{ userInfo.name }}</span>
					<a-tag color="#87d068" v-if="userInfo.type=='student'"><a-icon type="user" /> 学生</a-tag>
					<a-tag color="#2db7f5" v-else><a-icon type="user" /> 教师</a-tag>
				</div>
				<div class="detail">
					<div v-if="userInfo.type=='student'">
						<div>
							<a-icon type="mail" />
							<span>{{ userInfo.email }}</span>
						</div>
						<div>
							<a-icon type="book" />
							<span>{{ userInfo.department}}</span><span class="divider"></span><span>{{ userInfo.major }}</span>
						</div>
					</div>
					<div v-else>
						<div>
							<a-icon type="mail" />
							<span>{{ userInfo.email }}</span>
						</div>
						<div>
							<a-icon type="book" />
							<span>{{ userInfo.department}}</span>
						</div>
					</div>
				</div>
			</div>
			<div class="operations">
				<a>
					<a-button type="default" icon="message" size="default" @click="showMessageModal">发送消息</a-button>
					<MessageCreateForm
						ref="messageForm"
						:messageVisible="messageVisible"
						:facultyId="facultyId"
						@cancel="handleMessageCancel"
						@create="handleMessageCreate"
					/>
				</a>
				<a>
					<a-button type="primary" icon="schedule" size="default" @click="showReservationModal">预约老师</a-button>
					<ReservationCreateForm
						ref="reservationForm"
						:reservationVisible="reservationVisible"
						:timeValidateStatus="timeValidateStatus"
						:timeValidateHelp="timeValidateHelp"
						:facultyId="facultyId"
						@cancel="handleReservationCancel"
						@create="handleReservationCreate"
					/>
				</a>
			</div>
		</div>

		<a-divider style="margin-bottom: 32px" />
		<detail-list title="个人资料">
			<div v-if="userInfo.type=='student'">
				<detail-list-item term="学号">{{ userInfo.sid }}</detail-list-item>
				<detail-list-item term="邮箱">{{ userInfo.email }}</detail-list-item>
				<detail-list-item term="院系">{{ userInfo.department }}</detail-list-item>
				<detail-list-item term="专业">{{ userInfo.major }}</detail-list-item>
				<detail-list-item term="GPA">{{ userInfo.gpa }}</detail-list-item>
				<detail-list-item term="兴趣方向">{{ userInfo.interested_fields }}</detail-list-item>
			</div>
			<div v-else>
				<detail-list-item term="工号">{{ userInfo.sid }}</detail-list-item>
				<detail-list-item term="邮箱">{{ userInfo.email }}</detail-list-item>
				<detail-list-item term="院系">{{ userInfo.department }}</detail-list-item>
				<detail-list-item term="办公室">{{ userInfo.office }}</detail-list-item>
				<detail-list-item term="研究方向">{{ userInfo.fields }}</detail-list-item>
			</div>
		</detail-list>
		<a-divider style="margin-bottom: 32px" />
		<div class="intro-title">个人介绍</div>
		<div class="intro-content">{{ userInfo.intro }}</div>
	</a-card>
</page-layout>
</template>

<script>
import ACard from "ant-design-vue/es/card/Card";
import AButton from "ant-design-vue/es/button/button";
import ATag from "ant-design-vue/es/tag/Tag";
import ATooltip from "ant-design-vue/es/tooltip/Tooltip";
import AAvatar from "ant-design-vue/es/avatar/Avatar";
import AIcon from "ant-design-vue/es/icon/icon";
import DetailList from "../../components/tool/DetailList";
import ADivider from "ant-design-vue/es/divider/index";
import AForm from "ant-design-vue/es/form/Form";
import AFormItem from "ant-design-vue/es/form/FormItem";
import ARadioGroup from 'ant-design-vue/es/radio/Group'
import ARadio from 'ant-design-vue/es/radio'
import AInput from "ant-design-vue/es/input/Input";
import AModal from "ant-design-vue/es/modal/Modal";
import MessageCreateForm from './MessageCreateForm'
import ReservationCreateForm from './ReservationCreateForm'
import PageLayout from "../../layouts/PageLayout";
import moment from 'moment'

const DetailListItem = DetailList.Item;

export default {
	name: "UserProfile",
	components: {
		PageLayout,
		ADivider,
		DetailListItem,
		DetailList,
		AButton,
		AAvatar,
		ATooltip,
		ACard,
		AIcon,
		ATag,
		AForm,
		AFormItem,
		AInput,
		ARadioGroup,
		ARadio,
		AModal,
		MessageCreateForm,
		ReservationCreateForm
	},
	created() {
		this.loadUser ()
	},
	data() {
		return {
			userId: this.$route.params.id,
			userInfo: {},
			reservationVisible: false,
			messageVisible: false,
			facultyId: '',
			timeValidateStatus: '',
			timeValidateHelp: '',
		}
  	},
	methods: {
		loadUser () {
			var t = this
			this.$axios.get('/api/user/' + this.userId)
			.then(function (response) {
				t.userInfo = response.data
				t.facultyId = response.data.sid
			})
		},
		showReservationModal () {
			this.reservationVisible = true
		},
		handleReservationCancel  () {
			this.reservationVisible = false
		},
		handleReservationCreate  () {
			const form = this.$refs.reservationForm.form
			form.validateFields((err, values) => {
				if (err) {
					console.log("Form Error: " + err) 
				} else {
					console.log("Form Value: " + JSON.stringify(values))
					var formData = new FormData();
					formData.append("student_id", values.studentId);
					formData.append("faculty_id", values.facultyId);
					formData.append("start_time", moment(values.time[0]).format("YYYY-MM-DD HH:mm:ss"));
					formData.append("end_time", moment(values.time[1]).format("YYYY-MM-DD HH:mm:ss"));
					formData.append("content", values.content);

					this.$axios
						.post("/api/appointments", formData)
						.then(resp => {
								let res = resp.data;
								console.log("resp " + JSON.stringify(resp));
								if (resp.status == 201) {
									this.result = res
									this.$message.success("预约创建成功，请等待老师审核");
									this.$router.push('/appointments/' + this.result.id + '/success')
								} else {
									this.$message.error("预约创建失败");
									console.log("Error: " + JSON.stringify(res));
								}
							})
							.catch(err => {
								if (err.response.status == 400) {
									this.$message.error(err.response.data.message);
									this.timeValidateStatus = 'error'
									this.timeValidateHelp = err.response.data.message
								} else {
									this.$message.error("预约创建失败");
									console.log("Error: " + JSON.stringify(err));
								}
							});
				}
				form.resetFields()
				this.visible = false
			})
		},
		showMessageModal () {
			this.messageVisible = true
		},
		handleMessageCancel  () {
			this.messageVisible = false
		},
		handleMessageCreate  () {
			const form = this.$refs.messageForm.form
			form.validateFields((err, values) => {
				if (err) {
					console.log("Form Error: " + err) 
				} else {
					console.log(values.facultyId)
					var formData = new FormData();
					formData.append("receiver_id", values.facultyId);
					formData.append("content", values.content);
					this.$axios
						.post("/api/messages", formData)
						.then(resp => {
							let res = resp.data;
							if (resp.status == 201) {
								console.log(JSON.stringify(res))
								this.$router.push('/message/' + res.id)
							} else {
								this.$message.error("发送失败");
								console.log("Error: " + JSON.stringify(res));
							}
						})
						.catch(err => {
							this.$message.error("发送失败");
							console.log("Error: " + JSON.stringify(err));
						});
				}
				form.resetFields()
				this.visible = false
			})
		},
	},
	watch: {
		'$router' (to, from) {
			this.userId = to.params.id
			this.loadUser()
		}
  	},
	outputOptions: {
		type: Object,
		default() {
			return {
			width: 512,
			height: 512
			};
		}
	}
};
</script>

<style lang="less" scoped>
.intro-title {
	color: rgba(0, 0, 0, 0.85);
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
	transition: opacity 0.2s ease-in;
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
	opacity: 0.4;
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
	-webkit-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	}
	.mask-text {
	white-space: nowrap;
	}
}

.info-content {
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

.operations {
	position: absolute;
	float: right;
	top: 0;
	right: 0;
	a > button {
	height: 37px;
	}
	a:first-child {
		margin-right: 5px!important;
	}
}
</style>
