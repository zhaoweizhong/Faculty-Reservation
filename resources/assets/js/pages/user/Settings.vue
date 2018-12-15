<template>
<page-layout title="个人设置">
	<a-card style="width:100%;" :bordered="false" class="settings">
		<div class="main">
			<div class="col-left">
				<a-menu
				style="width: 224px;float: left;"
				:defaultSelectedKeys="['1']"
				mode="inline"
				@click="handleClick"
				>
					<a-menu-item key="1">
						<a-icon type="profile"/>个人资料
					</a-menu-item>
					<a-menu-item key="2">
						<a-icon type="lock"/>安全设置
					</a-menu-item>
					<a-menu-item key="3">
						<a-icon type="setting"/>其他设置
					</a-menu-item>
				</a-menu>
			</div>

			<div v-if="this.current == '1'" class="col-right">
				<div class="title">
					<span>个人资料</span>
				</div>
				<div class="profile-content">
					<a-form
					@submit="profileSubmit"
					:autoFormCreate="(form)=>{this.form = form, dataInitilize()}"
					>
					<div class="ant-row">
						<div class="left ant-col-8">
							<a-form-item
							:labelCol="{ span: 4 }"
							:wrapperCol="{ span: 24 }"
							label="姓名"
							:colon="false"
							fieldDecoratorId="name"
							:fieldDecoratorOptions="{rules: [{ required: true, message: '请输入姓名' }]}"
							>
								<a-input placeholder="姓名" disabled/>
							</a-form-item>
							<a-form-item
							:labelCol="{ span: 4 }"
							:wrapperCol="{ span: 24 }"
							label="邮箱"
							:colon="false"
							fieldDecoratorId="email"
							:fieldDecoratorOptions="{rules: [{ required: true, message: '请输入邮箱' }]}"
							>
								<a-input placeholder="邮箱" disabled/>
							</a-form-item>
							<a-form-item
							:labelCol="{ span: 4 }"
							:wrapperCol="{ span: 24 }"
							label="院系"
							:colon="false"
							fieldDecoratorId="department"
							:fieldDecoratorOptions="{rules: [{ required: true, message: '请输入院系' }]}"
							>
								<a-input placeholder="院系" disabled/>
							</a-form-item>
							<a-form-item
							:labelCol="{ span: 4 }"
							:wrapperCol="{ span: 24 }"
							label="专业"
							:colon="false"
							fieldDecoratorId="major"
							:fieldDecoratorOptions="{rules: [{ required: true, message: '请输入专业' }]}"
							>
								<a-input placeholder="专业" disabled/>
							</a-form-item>
							<a-form-item
							:labelCol="{ span: 4 }"
							:wrapperCol="{ span: 24 }"
							label="GPA"
							:colon="false"
							fieldDecoratorId="gpa"
							:fieldDecoratorOptions="{rules: [{ required: true, message: '请输入GPA' }]}"
							>
								<a-input placeholder="GPA" disabled/>
							</a-form-item>
							<a-form-item
							:labelCol="{ span: 8 }"
							:wrapperCol="{ span: 24 }"
							label="兴趣方向"
							:colon="false"
							fieldDecoratorId="interested_fields"
							:fieldDecoratorOptions="{rules: [{ required: true, message: '请输入兴趣方向' }]}"
							>
								<a-textarea
								placeholder="兴趣方向..."
								id="interested_fields"
								:autosize="{ minRows: 2 }"
								/>
							</a-form-item>
						</div>
						<div class="right ant-col-16">
							<a-form-item
							:labelCol="{ span: 3 }"
							:wrapperCol="{ span: 24 }"
							label="个人介绍"
							:colon="false"
							fieldDecoratorId="intro"
							:fieldDecoratorOptions="{rules: [{ required: true, message: '请输入个人介绍' }]}"
							>
								<a-textarea placeholder="个人介绍..." id="intro" :autosize="{ minRows: 21.5 }"/>
							</a-form-item>
						</div>
					</div>
					<div class="ant-row">
						<a-form-item>
							<a-button type="primary" htmlType="submit" size="large">提交</a-button>
						</a-form-item>
					</div>
					</a-form>
				</div>
			</div>
			<div v-else-if="this.current == '2'" class="col-right">
				<div class="title">
					<span>修改密码</span>
				</div>
			</div>
			<div v-else class="col-right">
				<div class="title">
					<span>其他设置</span>
				</div>
			</div>
		</div>
	</a-card>
</page-layout>
</template>

<script>
import ACard from "ant-design-vue/es/card/Card";
import AForm from "ant-design-vue/es/form/Form";
import AFormItem from "ant-design-vue/es/form/FormItem";
import AInput from "ant-design-vue/es/input/Input";
import ATextarea from "ant-design-vue/es/input/TextArea";
import AButton from "ant-design-vue/es/button/button";
import ATag from "ant-design-vue/es/tag/Tag";
import ATooltip from "ant-design-vue/es/tooltip/Tooltip";
import AIcon from "ant-design-vue/es/icon/icon";
import DetailList from "../../components/tool/DetailList";
import ADivider from "ant-design-vue/es/divider/index";
import PageLayout from "../../layouts/PageLayout";
import AMenu from "ant-design-vue/es/menu/index";
import message from "ant-design-vue/es/message";
import { getCookie, setCookie } from "tiny-cookie";
import store from "../../store";
import { rejects } from "assert";

const AMenuItem = AMenu.Item;
const DetailListItem = DetailList.Item;

export default {
	name: "BasicDetail",
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
			current: "1"
		};
	},
	methods: {
		dataInitilize() {
			var cookieUser = JSON.parse(getCookie("user"));
			this.form.getFieldDecorator("name", { initialValue: cookieUser.name });
			this.form.getFieldDecorator("email", { initialValue: cookieUser.email });
			this.form.getFieldDecorator("department", {
				initialValue: cookieUser.department
			});
			this.form.getFieldDecorator("major", { initialValue: cookieUser.major });
			this.form.getFieldDecorator("gpa", { initialValue: cookieUser.gpa });
			this.form.getFieldDecorator("interested_fields", {
				initialValue: cookieUser.interested_fields
			});
			this.form.getFieldDecorator("intro", { initialValue: cookieUser.intro });
		},
		handleClick(e) {
			this.current = e.key;
		},
		profileSubmit(e) {
			e.preventDefault();
			this.form.validateFields((err, values) => {
				if (!err) {
					this.$axios
						.patch("/api/user", {
							interested_fields: values.interested_fields,
							intro: values.intro
						})
						.then(function(response) {
							if (response.status == 200) {
								store.state.account.user = response.data;
								setCookie("user", JSON.stringify(response.data));
								message.success("修改成功");
							}
						})
						.catch(error => {
							console.log(JSON.stringify(error));
						});
				}
			});
		}
	}
};
</script>

<style lang="less">
.title {
	color: rgba(0, 0, 0, 0.85);
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
			color: rgba(0, 0, 0, 0.85);
			font-size: 20px;
			font-weight: 500;
			line-height: 28px;
			margin-bottom: 12px;
		}
	}
}

.profile-content {
	.left {
		max-width: 448px;
		min-width: 224px;
		float: left;
		text-align: left;
		.ant-form-item {
			margin-bottom: 16px;
		}
		.ant-form-item-label {
			text-align: left;
			line-height: 2;
		}
	}
	.right {
		padding-left: 40px;
		position: relative;
		.ant-form-item-label {
			text-align: left;
			line-height: 2;
		}
		.ant-form-item-control-wrapper {
			margin-top: 3px;
		}
	}
}
</style>
