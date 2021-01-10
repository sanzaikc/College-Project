<template>
	<div class="row h-100">
		<div class="col-md-2 bg-light">
			<b-nav vertical class="w-100 text py-3">
				<router-link
					:to="{ name: 'host.home' }"
					tag="b-nav-item"
					active-class="activeTab"
				>
					<b-icon
						icon="house"
						variant="dark"
						@click="edit = false"
						class="mr-2"
					></b-icon
					>Home
				</router-link>
				<router-link 
					:to="{ name: 'host.quiz' }"
					tag="b-nav-item"
					active-class="activeTab"
					:disable="disabled"
				>
					<b-icon
						icon="book"
						variant="dark"
						@click="edit = false"
						class="mr-2"
					></b-icon
					>Quizzes
				</router-link>
				<router-link 
					:to="{ name: 'host.question' }" 
					tag="b-nav-item"
					active-class="activeTab"
					:disable="disabled"
					>
					<b-icon
						icon="question"
						variant="dark"
						@click="edit = false"
						class="mr-2"
					></b-icon
					>Questions
				</router-link>
			</b-nav>
		</div>
		<div class="col-md-10 pl-4 py-3 bg-white">
			<transition name="slide-fade" mode="out-in">
				<router-view></router-view>
			</transition>
		</div>
	</div>
</template>

<script>
import { mapState } from 'vuex'
export default {
	mounted() {
		document.title = "Host";
	},
	computed: {
		...mapState({
			'disabled': (state) => state.auth.currentUser.is_disabled,
		}),
	}

};
</script>

<style scoped>
.activeTab,
a {
	background-color: #dbf3fc;
	border-radius: 2rem;
	font-size: 1rem;
	font-weight: bold;
}
</style>
