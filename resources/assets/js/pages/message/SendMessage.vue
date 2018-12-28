<template>
	<a-card :body-style="{padding: '24px 32px'}" :bordered="false">
		<a-form
			@submit="handleSubmit"
      		:autoFormCreate="(form)=>{this.form = form, dataInitilize()}"
		>
			<a-form-item :colon="false" label="收件人" :labelCol="{span: 7}" :wrapperCol="{span: 10}" fieldDecoratorId="receiver_id" :fieldDecoratorOptions="{rules: [{ required: true, message: '请搜索或输入收件人' }]}">
				<a-select
					showSearch
					:value="value"
					placeholder="搜索用户..."
					style="width: 100%"
					:defaultActiveFirstOption="false"
					:filterOption="false"
					:showArrow="false"
					@search="fetchUser"
					@change="handleChange"
					:notFoundContent="fetching ? undefined : null"
					id="receiver_id"
				>
					<a-spin v-if="fetching" slot="notFoundContent" size="small"/>
					<a-select-option v-for="d in data" :key="d.value">{{d.text}}</a-select-option>
				</a-select>
			</a-form-item>
			<a-form-item :colon="false" label="内容" class="content-label" :labelCol="{span: 7}" :wrapperCol="{span: 10}" fieldDecoratorId="content" :fieldDecoratorOptions="{rules: [{ required: true, message: '请输入消息内容' }]}">
				<a-textarea placeholder="消息内容" id="content" :autosize="{ minRows: 8 }" />
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

import jsonp from 'fetch-jsonp';
import querystring from 'querystring';
import debounce from 'lodash/debounce';

const ASelectOption = ASelect.Option;
export default {
	name: "SendMessage",
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
		ASpin
	},
	data() {
		this.lastFetchId = 0;
    	this.fetchUser = debounce(this.fetchUser, 800);
		return {
			value: 1,
			data: [],
			value: [],
			fetching: false,
			userId: this.$route.params.sid,
			isSelectDisabled: this.userId?true:false
		};
	},
	methods: {
		fetchUser (value) {
			this.lastFetchId += 1;
			const fetchId = this.lastFetchId;
			this.data = []
			this.fetching = true
			var formData = new FormData();
			formData.append("keyword", value);
			this.$axios
				.post("/api/users/search", formData)
				.then(resp => {
					if (fetchId !== this.lastFetchId) { // for fetch callback order
						return;
					}
					let res = resp.data;
					if (resp.status == 200) {
						const data = res.data.map(user => ({
							text: user.name,
							value: user.sid,
						}));
						this.data = data
						this.fetching = false
					} else {
						console.log("Error: " + JSON.stringify(res));
					}
				})
				.catch(err => {
					console.log("Error: " + JSON.stringify(err));
				});
		},
		dataInitilize () {
			if (this.userId) {
				this.form.getFieldDecorator("receiver_id", { initialValue: this.userId});
			}
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
					console.log("No Error! Do Submit!")
					var formData = new FormData();
					formData.append("receiver_id", values.receiver_id);
					formData.append("content", values.content);
					this.$axios
						.post("/api/messages", formData)
						.then(resp => {
							let res = resp.data;
							if (resp.status == 201) {
								this.$message.success("发送成功");
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
			});
		},
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
