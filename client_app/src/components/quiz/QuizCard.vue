<template>
	<b-col cols="12" md="4" sm="6" lg="3">
		<b-card
			:title="quiz.name"
			:img-src="
				quiz.image_url
					? quiz.image_url
					: require('../../assets/images/quizDefault.jpg')"
			img-height="150"
			img-top

			style="height: 100%;">

			<div style="display: flex; flex-direction: column;  height: 70%; justify-content: space-between">
				<b-card-text v-text="quiz.description" class="line-clamp"></b-card-text>

				<div class="d-flex justify-content-between" >
					<router-link :to="{ name: 'quizDetail', params: { id: quiz.id } }"
						class="col-md-4 btn btn-outline-primary btn-sm">View
					</router-link>

					<button v-if="quiz.questions" class="col-md-4 btn btn-outline-info btn-sm" @click="host(quiz)">
						<b-spinner v-if="isBusy"
								small
								type="grow">
						</b-spinner>
						<span v-else> Host </span>
					</button>
				</div>
			</div>
		</b-card>
	</b-col>
</template>

<script>
export default {
	props: {
		quiz: {
			type: Object,
		},
	},
	data(){
		return{
			isBusy: false,
		}
	},
	methods: {
		host(quiz){
			let pin = Math.floor(Math.random() * 90000) + 10000;
			if(!quiz.questions.length <= 0){
				this.isBusy = true;
				this.$store.dispatch('updatePin', {id: quiz.id, pin: pin})
					.then(res => {
						if(res) this.$router.push({ name: 'quiz.start', params:{ id: quiz.id } });
						this.isBusy = false;
					})
					.catch(error => {
						this.$toasted.show(error, {
							theme: "bubble",
						})
						this.isBusy = false;
				});
			}else {
				alert('Oops, questions aren\'t sufficeint!');
			}

		}
	}
};
</script>

<style scoped>
.line-clamp {
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
}
</style>
