import $axios from "../../plugins/axios";

export default {
    state: {
        categoryList: [],
    },
    mutations: {
        SET_CATEGORIES: (state, categories) => {
            state.categoryList = categories;
        },
        ADD_CATEGORY: (state, category) => {
            state.categoryList = [category, ...state.categoryList];
        },
        REMOVE_CATEGORY :(state, category) => {
            let updatedList = state.categoryList.filter(q => q.id != category.id);
            state.categoryList = [...updatedList];
        },
        UPDATE_LIST: (state,updates) => {
            let updatedList = state.categoryList.map( q => q.id == updates.id ? updates : q );
            state.categoryList = [...updatedList];
        },
    },
    actions: {
        retrieveCategories: ({commit}) => {
            return new Promise((resolve, reject) => {
                $axios.get("/categories")
                .then(res=> {
                    let categories = res.data.categories;
                    commit('SET_CATEGORIES', categories);
                    resolve(res);
                })
                .catch(error => {
                    console.log(error.response.data.errors.name);
                    reject(error);
                })
            });
        },
        createCategory: ({commit}, category) => {
            return new Promise((resolve, reject)=>{
                $axios.post("/categories", category)
                .then(res => {
                    let newCategory = res.data.category;
                    commit('ADD_CATEGORY', newCategory);
                    resolve(res.data.category);
                })
                .catch(error => {
                    reject(error.response.data.errors.name);
                })
            });
        },
        deleteCategory: ({commit}, id) => {
            return new Promise((resolve, reject)=>{
                if(confirm("Are you sure you want to delete?")){
                    $axios.delete("/categories/"+ id + "/1")
                    .then(res => {
                        let cate = res.data.category;
                        commit("REMOVE_CATEGORY",cate);
                        resolve(res.data.category);
                    })
                    .catch(error => {
                        reject(error.response.data.errors.name);
                    })
                }
            });
        },
        updateCategory: ({commit}, payload) => {
            return new Promise((resolve, reject)=>{
                $axios.put("/categories/" + payload.id + "/",  {
                    name: payload.category,
                    })
                    .then(res => {
                        let updates = res.data;
                        commit('UPDATE_LIST', updates );
                        resolve(res.data);
                    })
                    .catch(error => {
                        reject(error.response.data.errors.name);
                    });
            });
        }

    },
    getters: {
        categories: state => {
            let categories = state.categoryList.map((cat) => ({
                value: cat.id,
                text: cat.name,
            }));
            let placeholder = {
                value: null,
                text: "Select a category",
                disabled: true,
            };
            categories.unshift(placeholder);
            return categories;
        }
    }
}
