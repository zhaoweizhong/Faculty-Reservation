require("./bootstrap");
window.Vue = require("vue");

import App from "./App";
import router from "./router/lazy";
import "ant-design-vue/dist/antd.css";
import Viser from "viser-vue";
import axios from "axios";
import message from "ant-design-vue/es/message";
import "./mock";
import store from "./store";
import {getCookie} from "tiny-cookie";

Vue.prototype.$axios = axios;
Vue.prototype.router = router;
Vue.prototype.$message = message;
Vue.prototype.$store = store;
Vue.config.productionTip = false;
Vue.use(Viser);

axios.interceptors.response.use(
	function (response) {
		return response;
	},
	function (error) {
		const originalRequest = error.config;
		if (error.response.status === 401 && !originalRequest._retry) {
			originalRequest._retry = true;
			axios.defaults.headers.common["Authorization"] =
				"Bearer " + getCookie("token");
			return axios.put("/api/authorizations/current").then(res => {
				store.commit("account/refreshToken", res.data.access_token);
				originalRequest.headers.Authorization =
					"Bearer " + res.data.access_token;
				return axios(originalRequest);
			});
		}
		if (error.response.status === 500 && error.response.data.message.indexOf('blacklist')) {
			store.commit("account/logout")
			router.push('/login')
			return Promise.reject(error);
		}
		return Promise.reject(error);
	}
);

new Vue({
	el: "#app",
	router,
	store,
	components: {
		App
	},
	template: "<App/>"
});
