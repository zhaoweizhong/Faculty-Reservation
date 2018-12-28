<template>
    <a-modal
      :visible="reservationVisible"
      title='创建预约'
      okText='创建'
      cancelText='取消'
      @cancel="() => { $emit('cancel') }"
      @ok="() => { $emit('create') }"
    >
      <a-form layout='vertical' :autoFormCreate="(form)=>{this.form = form, dataInitilize()}">
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
import ADatePicker from 'ant-design-vue/es/date-picker'
import ATextarea from "ant-design-vue/es/input/TextArea";
import { getCookie } from "tiny-cookie";

const ARangePicker = ADatePicker.RangePicker

export default {
	name: "ReservationCreateForm",
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
        ARangePicker,
        ATextarea
    },
    props: {
        reservationVisible: {
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
        timeValidateStatus: {
            type: String,
            default: () => {
                return ''
            }
        },
        timeValidateHelp: {
            type: String,
            default: () => {
                return ''
            }
        }
    },
    data() {
        return {
            studentId: JSON.parse(getCookie("user")).sid,
        }
    },
    methods: {
        dataInitilize () {
            this.form.getFieldDecorator("facultyId", { initialValue: this.facultyId });
            this.form.getFieldDecorator("studentId", { initialValue: this.studentId });
        },
        onTimeChange(value, dateString) {
        },
        onTimeOk(value) {
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

