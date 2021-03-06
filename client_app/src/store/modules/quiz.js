import $axios from "../../plugins/axios";

const state = {
  quizList: [],
  quizDetail: {},
  quizQuestions: [],
  players: [],
  currentPlayerId: null,
};

const mutations = {
  SET_QUIZ_LIST: (state, list) => {
    state.quizList = list;
  },
  ADD_QUIZ: (state, quiz) => {
    state.quizList = [quiz, ...state.quizList];
  },
  REMOVE_QUIZ: (state, quiz) => {
    let newList = state.quizList.filter((q) => q.id != quiz.id);
    state.quizList = [...newList];
  },
  QUIZ_DETAIL: (state, id) => {
    state.quizDetail = state.quizList.find((q) => q.id == id);
  },
  UPDATE_DETAIL: (state, quiz) => {
    state.quizDetail = quiz;
  },
  UPDATE_LIST: (state, updates) => {
    let updatedList = state.quizList.map((q) =>
      q.id == updates.id ? updates : q
    );
    state.quizList = [...updatedList];
  },
  SET_QUIZ_QUESTIONS: (state, questions) => {
    state.quizQuestions = questions;
  },
  ADD_QUIZ_QUESTIONS: (state, questions) => {
    state.quizQuestions = questions;
  },
  REMOVE_QUESTION: (state, id) => {
    let newList = state.quizQuestions.filter((q) => q.id != id);
    state.quizQuestions = [...newList];
  },
  UPDATE_PIN: (state, data) => {
    state.quizList = state.quizList.map((q) =>
      q.id == data.id ? { ...q, pin: data.pin } : q
    );
  },
  ADD_PLAYERS: (state, player) => {
    let playerIds = state.players.map((p) => p.id);
    if (!playerIds.includes(player.id)) {
      state.players.push(player);
    }
  },
  REMOVE_PLAYERS: (state) => {
    state.players = [];
  },

  REPLACE_PLAYERS: (state, players) => {
    state.players = players;
  },

  UPDATE_SCORE: (state, { player_id, score }) => {
    state.players = state.players.map((p) =>
      p.id === player_id ? { ...p, score } : p
    );
  },

  SET_CURRENT_PLAYER: (state, player) => {
    state.currentPlayerId = player;
  },
};

const actions = {
  retrieveQuiz: ({ commit }) => {
    return new Promise((resolve, reject) => {
      $axios
        .get("/quizzes")
        .then((res) => {
          let quizzes = res.data.quizzes;
          commit("SET_QUIZ_LIST", quizzes);
          resolve(res.data.quizzes);
        })
        .catch((error) => {
          reject(error.response.data);
        });
    });
  },
  createQuiz: ({ commit }, quiz) => {
    let quizData = new FormData();
    quizData.append("name", quiz.name);
    quizData.append("description", quiz.description);
    quizData.append("image", quiz.image);

    return new Promise((resolve, reject) => {
      $axios
        .post("/quizzes", quizData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          let quiz = res.data.quiz;
          commit("ADD_QUIZ", quiz);
          resolve(res.data.quiz);
        })
        .catch((error) => {
          reject(error.response.data.errors.name);
        });
    });
  },
  deleteQuiz: ({ commit }, id) => {
    return new Promise((resolve, reject) => {
      $axios
        .delete("/quizzes/" + id)
        .then((res) => {
          commit("REMOVE_QUIZ", res.data.quiz);
          resolve(res.data.message);
        })
        .catch((error) => {
          reject(error.response.data);
        });
    });
  },
  updateQuiz: ({ commit }, quiz) => {
    let quizData = new FormData();
    quizData.append("name", quiz.data.name);
    quizData.append("description", quiz.data.description);
    quizData.append("image", quiz.data.image);

    return new Promise((resolve, reject) => {
      $axios
        .post("/quizzes/" + quiz.id, quizData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          let quiz = res.data.quiz;
          commit("UPDATE_DETAIL", quiz);
          commit("UPDATE_LIST", quiz);
          resolve(res.data.quiz);
        })
        .catch((error) => {
          reject(error.response.data.errors.name);
        });
    });
  },
  attachQuestions: ({ commit }, data) => {
    return new Promise((resolve, reject) => {
      $axios
        .post("/questions/attach/" + data.quizId, {
          questions: data.questions,
        })
        .then((res) => {
          let questions = res.data.data;
          commit("ADD_QUIZ_QUESTIONS", questions);
          resolve(res.data);
        })
        .catch((error) => {
          reject(error.response.data);
        });
    });
  },
  detachQuestion: ({ commit }, data) => {
    return new Promise((resolve, reject) => {
      $axios
        .post("/questions/detach/" + data.quizId, {
          questions: data.questionId,
        })
        .then((res) => {
          resolve(res.data);
          commit("REMOVE_QUESTION", data.questionId);
        })
        .catch((error) => {
          reject(error.response.data);
        });
    });
  },
  updatePin: ({ commit }, data) => {
    return new Promise((resolve, reject) => {
      $axios
        .post("/quizzes/" + data.id, {
          pin: data.pin,
        })
        .then((res) => {
          let data = res.data.quiz;
          commit("UPDATE_PIN", data);
          resolve(res);
        })
        .catch((error) => {
          reject(error.response.data);
        });
    });
  },
  joinQuiz: (context, data) => {
    return new Promise((resolve, reject) => {
      $axios
        .post("player", data)

        .then((res) => {
          resolve(res.data);
        })
        .catch((error) => {
          reject(error.response.data.errors.name);
        });
    });
  },

  changeCurrentQuestion: (context, { quizId, questionId, playerId }) => {
    return new Promise((resolve, reject) => {
      $axios
        .post("quizzes/" + quizId, {
          current_question: questionId,
          player_id: playerId,
        })
        .then((res) => {
          if (res.status === 200) resolve(res.data);
        })
        .catch((error) => reject(error.response.data));
    });
  },

  endQuiz: ({ commit }, quizId) => {
    return new Promise((resolve, reject) => {
      $axios
        .post("quizzes/end/" + quizId)
        .then((res) => {
          if (res.status == 200) {
            commit("REMOVE_PLAYERS");
            resolve(res.data);
          }
        })
        .catch((error) => reject(error.response.data));
    });
  },

  submitScore: (context, { playerId, score, optionId }) => {
    return new Promise((resolve, reject) => {
      $axios
        .post("/score", {
          player_id: playerId,
          score,
          optionId,
        })
        .then((res) => {
          if (res.status == 200) resolve(res.data);
        })
        .catch((error) => reject(error.response.data));
    });
  },
};

const getters = {
  currentPlayer: (state) =>
    state.players.find((p) => p.id === state.currentPlayerId),
};

export default {
  state,
  mutations,
  actions,
  getters,
};
