<template>
    <div  class="px-3 py-2 mb-2 border rounded-lg shadow-sm">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div @click="showAns = !showAns" style="cursor: pointer" class="w-100 d-flex">
                <h4>{{ index + 1  + '.'}} </h4>
                <h4 class="ml-2" >{{ question.body }} </h4>
            </div>

            <b-dropdown v-if="!attach" size="sm" dropleft  variant="link" toggle-class="text-decoration-none" no-caret>
                <template v-slot:button-content>
                    <b-icon 
                        icon="three-dots-vertical"
                        role="button">
                    </b-icon>
                </template>
                 <b-dropdown-item @click="showEdit(question)">
                        <b-icon 
                            icon="pencil" 
                            scale="0.80"
                            animation="throb">
                        </b-icon> Edit
                        </b-dropdown-item>
                        <b-dropdown-item @click="del(question.id)">
                            <b-icon 
                                icon="trash" 
                                variant="danger"
                                scale="0.80"
                                animation="throb">
                            </b-icon> Delete
                </b-dropdown-item>
            </b-dropdown>
            
            <b-icon v-else 
                icon="backspace" 
                role="button" 
                @click="detachQuestion(question.id)">
            </b-icon>
        </div>
        <div v-if="!showAns" class="ml-3">
           <span class="px-2 border rounded-pill text-secondary" v-text="this.categoryName"> </span>
        </div>
        <transition name="fade" mode="out-in">
            <div v-if="showAns" class="d-flex justify-content-between">
                <ul class="list list-unstyled w-75 ml-3">
                    <li 
                        v-for="option in question.options" 
                        :key="option.id" 
                        class="px-2 rounded font-weight-bold mb-2 w-100" 
                        :class="option.id == question.answer.option_id ? 'text-success' : ''" 
                        v-text="option.body">
                    </li>
                </ul>
                <div v-if="question.image_url" class="w-50 text-center">
                    <p class="mb-0">This question contains an image</p>
                    <img :src="question.image_url" class="rounded" height="100px">
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    props: {
        question: {
            type: Object,
        },
        index: {
            type: Number,
        },
        attach :{
            type: Boolean,
        }
    },
    data(){
        return{
            showAns: false,
        }
    },
    methods: {
        showEdit(question){
           this.$emit('edit', question);
        },
        detachQuestion(id){
            this.$emit('detach', id);
        },
        del(id){
            if(confirm('Are you sure you want to delete?')){
                this.$store.dispatch('deleteQuestion', id)
                .then(res => {
                    if(res){
                        this.$toasted.show("Question deleted", {
                            theme: 'bubble'
                        })
                    } 
                })
                .catch(error => {
                    this.$toasted.show(error, {
                        theme: 'bubble'
                    })
                });      
            }
        }
    },
    computed: {
        ...mapGetters([
            'categories'
        ]),
        categoryName(){
            let category = this.categories.find(q => q.value === this.question.category_id);
            return category.text;
        }
    },
}
</script>

<style scoped>
</style>