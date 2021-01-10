import $axios from "../../plugins/axios";

const state = {
	questionList: [],
};
const mutations = {
	SET_QUESTION_LIST: (state, list) => {
		state.questionList = list;
	},
	ADD_QUESTION: (state, question) => {
		state.questionList = [question, ...state.questionList];
	},
	REMOVE_QUESTION: (state, question) => {
		let newList = state.questionList.filter((q) => q.id != question.id);
		state.questionList = [...newList];
	},
	UPDATE_LIST: (state, updates) => {
		let updatedList = state.questionList.map((q) =>
			q.id == updates.id ? updates : q
		);
		state.questionList = [...updatedList];
	},
};
const actions = {
	retriveQuestions: ({ commit }) => {
		return new Promise((resolve, reject) => {
			$axios
				.get("/questions")
				.then((res) => {
					commit("SET_QUESTION_LIST", res.data.questions);
					resolve(res.data);
				})
				.catch((error) => {
					reject(error.response.data);
				});
		});
	},
	createQuestion: ({ commit }, question) => {
		let questionData = new FormData();
		questionData.append("body", question.body);
		questionData.append("category_id", question.category_id);

		for (var i = 0; i < question.options.length; i++) {
			questionData.append(`options[${i}]['body']`, question.options[i].body);
			questionData.append(
				`options[${i}]['correct']`,
				question.options[i].correct
			);
		}
		questionData.append("image", question.image);

		return new Promise((resolve, reject) => {
			$axios
				.post("/questions", questionData, {
					headers: {
						"Content-Type": "multipart/form-data",
					},
				})
				.then((res) => {
					let question = res.data.question;
					commit("ADD_QUESTION", question);
					resolve(res.data.question);
				})
				.catch((error) => {
					console.log(error.response.data.errors.body[0]);
					reject(error.response.data);
				});
		});
	},
	deleteQuestion: ({ commit }, id) => {
		return new Promise((resolve, reject) => {
			$axios
				.delete("/questions/" + id)
				.then((res) => {
					commit("REMOVE_QUESTION", res.data.question);
					resolve(res.data.message);
				})
				.catch((error) => {
					reject(error.response.data);
				});
		});
	},
	updateQuestion: ({ commit }, question) => {
		let questionData = new FormData();
		questionData.append("body", question.data.body);
		questionData.append("category_id", question.data.category_id);

		for (var i = 0; i < question.data.options.length; i++) {
			questionData.append(`options[${i}]['id']`, question.data.options[i].id);
			questionData.append(
				`options[${i}]['body']`,
				question.data.options[i].body
			);
			questionData.append(
				`options[${i}]['correct']`,
				question.data.options[i].correct
			);
		}
		questionData.append("image", question.data.image);

		return new Promise((resolve, reject) => {
			$axios
				.post("/questions/" + question.id, questionData, {
					headers: {
						"Content-Type": "multipart/form-data",
					},
				})
				.then((res) => {
					let question = res.data.question;
					commit("UPDATE_LIST", question);
					resolve(res.data.question);
				})
				.catch((error) => {
					console.log(error.response.data.errors.body[0]);
					reject(error.response.data);
				});
		});
	},
};
const getters = {};

export default {
	state,
	mutations,
	actions,
	getters,
};
