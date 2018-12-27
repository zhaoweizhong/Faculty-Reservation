<template>
	<a-card :body-style="{padding: '24px 32px'}" :bordered="false">
		<a-form
			@submit="handleSubmit"
      		:autoFormCreate="(form)=>{this.form = form, dataInitilize()}"
		>
			<a-form-item :colon="false" label='学生' fieldDecoratorId="studentId" hidden>
				<a-input id="student_id" disabled/>
			</a-form-item>
			<a-form-item :colon="false" label='教师' fieldDecoratorId="facultyId" hidden>
				<a-input id="faculty_id" disabled/>
			</a-form-item>
			<a-form-item
				:colon="false"
				label='预约时间'
				fieldDecoratorId="time"
				:validateStatus="timeValidateStatus"
				:help="timeValidateHelp"
				:fieldDecoratorOptions="{rules: [{ required: true, message: '请选择预约时间' }]}"
			>
				<a-range-picker
					:showTime="{ format: 'HH:mm' }"
					format="YYYY-MM-DD HH:mm"
					:placeholder="['Start Time', 'End Time']"
					@change="onTimeChange"
					@ok="onTimeOk"
				/>
			</a-form-item>
			<a-form-item 
				:colon="false" 
				label='预约内容' 
				fieldDecoratorId="content" 
				:fieldDecoratorOptions="{rules: [{ required: true, message: '请输入预约内容' }]}"
			>
				<a-textarea id="content" placeholder="预约内容..." :autosize="{ minRows: 4 }"/>
			</a-form-item>
			<a-form-item class="send-button" :wrapperCol="{span: 10, offset: 7}">
				<a-button type="primary" htmlType="submit" size="large">发送</a-button>
			</a-form-item>
		</a-form>
	</a-card>
</template>

<script>
import ACard from "ant-design-vue/es/card/Card";
import AForm from "ant-design-vue/es/form/Form";
import AFormItem from "ant-design-vue/es/form/FormItem";
import AInput from "ant-design-vue/es/input/Input";
import ATextarea from "ant-design-vue/es/input/TextArea";
import AInputNumber from "ant-design-vue/es/input-number/index";
import ARadioGroup from "ant-design-vue/es/radio/Group";
import ARadio from "ant-design-vue/es/radio/Radio";
import ASelect from "ant-design-vue/es/select/index";
import AButton from "ant-design-vue/es/button/button";
import ASpin from 'ant-design-vue/es/spin/Spin'
import ADatePicker from 'ant-design-vue/es/date-picker'
import { getCookie } from "tiny-cookie";
import moment from 'moment'

import jsonp from 'fetch-jsonp';
import querystring from 'querystring';
import debounce from 'lodash/debounce';

const ARangePicker = ADatePicker.RangePicker
const ASelectOption = ASelect.Option;

export default {
	name: "CreateAppointment",
	components: {
		AButton,
		ASelectOption,
		ASelect,
		ARadio,
		ARadioGroup,
		AInputNumber,
		ATextarea,
		AInput,
		AFormItem,
		AForm,
		ACard,
		ARangePicker
	},
	data() {
		return {
			studentId: JSON.parse(getCookie("user")).sid,
			facultyId: this.$route.params.sid,
			timeValidateStatus: '',
			timeValidateHelp: '',
		};
	},
	methods: {
		dataInitilize () {
			this.form.getFieldDecorator("facultyId", { initialValue: this.facultyId });
            this.form.getFieldDecorator("studentId", { initialValue: this.studentId });
		},
		handleChange (value) {
			Object.assign(this, {
				value,
				data: [],
				fetching: false,
			})
		},
		handleSubmit (e) {
			console.log("Submit!")
			e.preventDefault();
			var thisform = this.form
			this.form.validateFields((err, values) => {
				if (!err) {
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
								console.log(JSON.stringify(this.result))
								this.$message.success("预约创建成功，请等待老师审核");
								thisform.resetFields() //TODO: 跳转预约详情
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
			});
		},
		onTimeChange(value, dateString) {
        },
        onTimeOk(value) {
        }
	},
};
</script>

<style lang="less" scoped>
</style>

<style lang="less">
.content-label > .ant-form-item-label {
	line-height: 31px!important;
}
.send-button > .ant-col-10 {
	text-align: center;
}
</style>
