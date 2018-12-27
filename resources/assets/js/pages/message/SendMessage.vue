<template>
	<a-card :body-style="{padding: '24px 32px'}" :bordered="false">
		<a-form
			@submit="handleSearch"
      		:autoFormCreate="(form)=>{this.form = form}"
		>
			<a-form-item label="主题" :labelCol="{span: 7}" :wrapperCol="{span: 10}">
				<a-input placeholder="消息主题"/>
			</a-form-item>
			<a-form-item label="收件人" :labelCol="{span: 7}" :wrapperCol="{span: 10}">
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
				>
					<a-spin v-if="fetching" slot="notFoundContent" size="small"/>
					<a-select-option v-for="d in data" :key="d.value">{{d.text}}</a-select-option>
				</a-select>
			</a-form-item>
			<a-form-item label="内容" class="content-label" :labelCol="{span: 7}" :wrapperCol="{span: 10}">
				<a-textarea placeholder="消息内容" :autosize="{ minRows: 8 }" />
			</a-form-item>
			<a-form-item class="send-button" :wrapperCol="{span: 10, offset: 7}">
				<a-button type="primary" size="large">发送</a-button>
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
			desc:
			"表单页用于向用户收集或验证信息，基础表单常见于数据项较少的表单场景。",
			value: 1,
			data: [],
			value: [],
			fetching: false,
		};
	},
	methods: {
		fetchUser (value) {
			this.lastFetchId += 1;
			const fetchId = this.lastFetchId;
			this.data = []
			this.fetching = true
			fetch('https://randomuser.me/api/?results=5')
				.then(response => response.json())
				.then((body) => {
				if (fetchId !== this.lastFetchId) { // for fetch callback order
					return;
				}
				const data = body.results.map(user => ({
					text: `${user.name.first} ${user.name.last}`,
					value: user.login.username,
				}));
				this.data = data
				this.fetching = false
				});
    	},
		handleChange (value) {
			Object.assign(this, {
				value,
				data: [],
				fetching: false,
			})
		},
		handleSearch () {
			console.log("Do Search!")
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
