<template>
	<div>
		<b-spinner
			v-if="isLoading"
			variant="primary"
			label="Spinning"
			style="position: fixed; top: 50%; left: 50%"
		></b-spinner>
		<div v-else>
			<div class="d-flex justify-content-between align-items-center">
				<fab
					main-icon="menu"
					bg-color="#007bff"
					:actions="fabActions"
					@show="showSidebar"
				>
				</fab>
				<quiz-sidebar :text="'Creat'" v-model="isBusy" @onSubmit="createQuiz"></quiz-sidebar>
			</div>

			<div v-if="quizzes.length > 0" class="row">
				<quiz-card
					v-for="quiz in quizzes"
					:key="quiz.id"
					:quiz="quiz"
				></quiz-card>
			</div>

			<div v-else>
				No quizzes by you, yet!
			</div>

		</div>
	</div>
</template>
<script>
import fab from "vue-fab";
import { mapState } from "vuex";
import QuizCard from "../../../components/quiz/QuizCard";
import QuizSidebar from "../../../components/quiz/QuizSidebar.vue";
export default {
	mounted() {
		document.title = "Quiz";
		this.$store.dispatch("retrieveQuiz")
			.then((res) => {
				if (res) this.isLoading = false;
				this.$store.dispatch('retrieveCategories');
			})
			.catch((error) => this.$toasted.show(error, {
				theme: 'bubble',
			}));
	},
	data() {
		return {
			isLoading: true,
			isBusy: false,
			fabActions: [
				{
					name: "show",
					icon: "add",
				},
			],
		};
	},
	methods: {
		showSidebar() {
			document.getElementById("sidebar").click();
		},
		createQuiz(quiz) {
			this.isBusy = true;
			this.$store
				.dispatch("createQuiz", quiz)
				.then((res) => {
					this.isBusy = false;
					this.$toasted.show("Quiz '" + res.name + "' created.");
					document.getElementById("sidebar").click();
				})
				.catch((error) => {
					this.isBusy = false;
					this.$toasted.show(error, {
						theme: "bubble",
					});
				});
		},
	},
	computed: {
		...mapState({
			quizzes: (state) => state.quiz.quizList,
		}),
	},
	components: {
		fab,
		QuizCard,
		QuizSidebar,
	},
};
</script>
