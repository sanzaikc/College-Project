import $axios from "../../plugins/axios";
import Cookies from 'js-cookie'

const state = {
    token: null,
    currentUser:  null,
};
const mutations = {
	RETRIEVE_TOKEN: (state, token) => {
		state.token = token;
		Cookies.set("accessToken", token, { expires: 7 });
	},
	DESTROY_TOKEN: (state) => {
		state.token = null;
		Cookies.remove("accessToken")
	},
	SET_CURRENT_USER: (state, user) => {
		state.currentUser = user;
		Cookies.set("currentUser", user, { expires: 7 })
	},
	REMOVE_USER: (state) => {
		state.currentUser = null;
		Cookies.remove("currentUser");
	},
};
const actions = {
	restoreToken: (context) => {
		let token = Cookies.get("accessToken");
		if (token) {
			return new Promise((resolve, reject) => {
				$axios
					.get("/users/me")
					.then((res) => {
						let user = res.data.user;
						context.commit("SET_CURRENT_USER", user);

						resolve(res);
					})
					.catch((error) => {
						context.commit("DESTROY_TOKEN");
						context.commit("REMOVE_USER");
						reject(error);
					});
			});
		}
	},
	login: ({ commit }, credentials) => {
		return new Promise((resolve, reject) => {
			$axios
				.post("/login", credentials)
				.then((res) => {
					let token = res.data.token;
					commit("RETRIEVE_TOKEN", token);

					let user = res.data.user;
					commit("SET_CURRENT_USER", user);

					resolve(res);
				})
				.catch((error) => {
					reject(error.response.data.message);
				});
		});
	},
	register: (context,credentials) => {
		return new Promise((resolve, reject) => {
			$axios
				.post("/register", credentials)
				.then((res) => {
					resolve(res);
				})
				.catch((error) => {
					reject(error.response.data.errors.email[0]);
				});
		});
	},
	logout: (context) => {
		if (context.getters.loggedIn) {
			return new Promise((resolve, reject) => {
				$axios
					.post("/logout")
					.then((res) => {
						context.commit("DESTROY_TOKEN");
						context.commit("REMOVE_USER");
						resolve(res);
					})
					.catch((error) => {
						context.commit("DESTROY_TOKEN");
						context.commit("REMOVE_USER");
						reject(error.response.data.message);
					});
			});
		}
	},
};
const getters = {
	token: state => state.token,

	loggedIn: (state) => {
		return state.token != null;
	},
	isAdmin: state => {
		return state.currentUser.is_admin;
	},
	isDisabled: state => {
		return state.currentUser && state.currentUser.is_disabled;
	}
};

export default {
	state,
	mutations,
	actions,
	getters,
};
