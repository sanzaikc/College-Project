import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersistence from 'vuex-persist'

import auth from './modules/auth'
import users from './modules/users'
import categories from './modules/categories'
import quiz from './modules/quiz'
import questions from './modules/questions'

Vue.use(Vuex);
const vuexLocal = new VuexPersistence({
    storage: window.localStorage
});

export default new Vuex.Store({
    modules: {
        auth,
        users,
        categories,
        quiz,
        questions
    },
    plugins: [vuexLocal.plugin],
});