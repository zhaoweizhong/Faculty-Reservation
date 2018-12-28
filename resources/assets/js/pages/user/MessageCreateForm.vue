<template>
    <a-modal
      :visible="messageVisible"
      title='发送消息'
      okText='发送'
      cancelText='取消'
      @cancel="() => { $emit('cancel') }"
      @ok="() => { $emit('create') }"
    >
      <a-form layout='vertical' :autoFormCreate="(form)=>{this.form = form, dataInitilize()}">
        <a-form-item :colon="false" label='教师' fieldDecoratorId="facultyId" hidden>
          <a-input id="receiver_id" disabled/>
        </a-form-item>
        <a-form-item
            :colon="false"
            label='消息内容'
            fieldDecoratorId="content"
            :fieldDecoratorOptions="{rules: [{ required: true, message: '请输入消息内容' }]}"
        >
          <a-textarea id="content" placeholder="消息内容..." :autosize="{ minRows: 4 }"/>
        </a-form-item>
      </a-form>
    </a-modal>
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
import ATextarea from "ant-design-vue/es/input/TextArea";
import { getCookie } from "tiny-cookie";

export default {
	name: "MessageCreateForm",
	components: {
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
        ATextarea
    },
    props: {
        messageVisible: {
            type: Boolean,
            default: () => {
                return false
            }
        },
        facultyId: {
            type: String,
            default: () => {
                return ''
            }
        },
    },
    methods: {
        dataInitilize () {
            this.form.getFieldDecorator("facultyId", { initialValue: this.facultyId });
        },
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

