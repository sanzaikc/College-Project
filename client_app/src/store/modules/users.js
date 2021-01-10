import $axios from "../../plugins/axios";

const state = {
    userList: [],
};
const mutations = {
    SET_USERS: (state, users) => {
        state.userList = users;
    },
    UPDATE_LIST: (state, user) => {
        let updatedList = state.userList.map(q => q.id != user.id ? q : user);
        state.userList = updatedList;
    }

};
const actions = {
    retrieveUsers: ({commit}) => {
        return new Promise((resolve, reject)=>{
            $axios.get('/users')
                    .then(res => {
                        let users = res.data.users;
                        commit("SET_USERS", users);
                        resolve(res);
                    })
                    .catch(error => {
                        console.log(error.response.data);
                        reject(error);
                    })
        });
    },
    updateStatus: ({commit}, user) => {
        return new Promise((resolve, reject)=>{
            $axios.put('/users/update/' + user.id, {
                is_disabled: user.status
            })
                .then(res => {
                    let user = res.data.user;
                    commit('UPDATE_LIST', user);
                    commit('SET_CURRENT_USER', user, { root: true });
                    resolve(res.data.user);
                })
                .catch(error => reject(error.response.data));
        });
    },
};
const getters = {
};

export default {
    state,
    mutations,
    actions,
    getters
}
