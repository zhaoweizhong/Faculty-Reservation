<template>
<page-layout title="个人中心">
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
                <avatar-cropper
                  :upload-handler="handleUpload"
                  trigger="#mask" />
            </div>
            <div class="info-content">
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
        <detail-list title="个人资料">
            <detail-list-item term="学号">{{ currUser.sid }}</detail-list-item>
            <detail-list-item term="邮箱">{{ currUser.email }}</detail-list-item>
            <detail-list-item term="院系">{{ currUser.department }}</detail-list-item>
            <detail-list-item term="专业">{{ currUser.major }}</detail-list-item>
            <detail-list-item term="GPA">{{ currUser.gpa }}</detail-list-item>
            <detail-list-item term="兴趣方向">{{ currUser.interested_fields }}</detail-list-item>
        </detail-list>
        <a-divider style="margin-bottom: 32px" />
        <div class="title">个人介绍</div>
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
import PageLayout from "../../layouts/PageLayout";
import AvatarCropper from "vue-avatar-cropper";

const DetailListItem = DetailList.Item;

export default {
  name: "BasicDetail",
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
    AvatarCropper
  },
  computed: {
    currUser() {
      return this.$store.state.account.user;
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
  },
  mounted() {
    $("#avatar").hover(
      function() {
        $("#mask").removeClass("mask-hidden");
      },
      function() {
        $("#mask").addClass("mask-hidden");
      }
    );
  },
  methods: {
    handleUpload(result) {
      result.getCroppedCanvas(this.outputOptions).toBlob(blob => {
        var formData = new FormData();
        formData.append("image", blob);
        let config = {
          headers: { "Content-Type": "multipart/form-data" }
        };
        this.$axios
          .post("/api/images", formData, config)
          .then(resp => {
            let res = resp.data;
            console.log("resp " + resp);
            if (res.status_code == 201) {
              this.$store.state.account.user.avatar_url = res.url;
              this.$axios
                .patch("/api/user", {
                  avatar_url: res.url
                })
                .then(response => {
                  console.log("response " + response);
                  let result = response.data;
                  if (response.status == 201) {
                    this.$message.success("头像设置成功");
                  }
                })
                .catch(error => {
                  this.$message.error("头像设置失败");
                  console.log(
                    "User Patch Error: " + error.response.data.message
                  );
                });
            } else {
              this.$message.error("头像设置失败");
              console.log("Upload Error: " + JSON.stringify(res));
            }
          })
          .catch(err => {
            this.$message.error("头像设置失败");
            console.log("Upload Error: " + JSON.stringify(err));
          });
      });
    }
  }
};
</script>

<style lang="less" scoped>
.title {
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
