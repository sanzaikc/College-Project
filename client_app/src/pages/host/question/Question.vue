<template>
    <div>
         <b-spinner v-if="isLoading"
			variant="primary"
			label="Spinning"
			style="position: fixed; top: 50%; left: 50%">
        </b-spinner>

        <div v-else>
            <fab
                main-icon="menu"
                bg-color="#007bff"
                :actions="fabActions"
                @show="createSidebar">
            </fab>
            <sidebar
                v-model="isBusy"
                :text=" sideBarTitle "
                :qData="data"
                @onSubmit="create"
                @onUpdate="update" >
            </sidebar>

            <div v-if="questions.length > 0">
                <transition-group name="slide-fade" mode="out-in">
                    <question-card
                        v-for="(question, index) in questions"
                        :key="question.id"
                        :question="question"
                        :index="index"
                        :attach="false"
                        @edit="editSidebar">
                    </question-card>
                </transition-group>
            </div>
            <div v-else>No questions by you, yet!</div>
        </div>
    </div>

</template>

<script>
import { mapState } from 'vuex';
import fab from "vue-fab";
import QuestionCard from '../../../components/question/QuesCard.vue';
import Sidebar from '../../../components/question/QuesSidebar.vue';
export default {
    mounted(){
        document.title="Questions"
        this.$store.dispatch('retriveQuestions')
            .then(res => {
                if(res) this.isLoading = false;
                this.$store.dispatch('retrieveCategories')
            });
    },
    data(){
        return{
            isLoading: true,
            isBusy: false,
            sideBarTitle : '',
            data: {},
            fabActions: [
				{
					name: "show",
					icon: "add",
				},
			],
        }
    },
    methods: {
        createSidebar() {
            this.sideBarTitle = "Creat"
            this.data = null;
			document.getElementById("sidebar").click();
        },
        editSidebar(data){
            this.sideBarTitle = "Updat";
            this.data = data
            document.getElementById("sidebar").click();
        },
        create(question){
            this.isBusy = true;
            this.$store.dispatch('createQuestion',question)
                .then(res => {
                    if(res) this.$toasted.show("Question added successfully");
                    this.isBusy = false;
                    document.getElementById("sidebar").click();
                })
                .catch(error => {
                        if(error.errors.body[0] =="The body has already been taken."){
                            this.$toasted.show("Question already exists!", {
                            theme: 'bubble',
                        });
                        this.isBusy = false;
                    }
                });
        },
        update(question){
            this.isBusy = true;
            this.$store.dispatch('updateQuestion',question)
                .then(res => {
                    if(res) {
                        this.$toasted.show("Question Updated successfully");
                        this.isBusy = false;
                        document.getElementById("sidebar").click();
                    }
                })
                .catch(error => {
                    this.$toasted.show(error, {
                        theme: 'bubble',
                    });
                    this.isBusy = false;
                });
        },
    },
    computed:{
        ...mapState({
            'questions': state => state.questions.questionList,
        }),
    },
    components: {
        fab,
        QuestionCard,
        Sidebar,
    }
}
</script>
