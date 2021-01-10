<template>
	<b-navbar
		toggleable="lg"
		type="light"
		class="border-bottom bg-white px-4"
		sticky>

		<router-link to="/"
			tag="b-navbar-brand" 
			role="button"
			class="d-flex align-items-center">
				<b-icon icon="egg-fried" scale="1.5"></b-icon>
				<h4 class="ml-2">Quizy</h4>
		</router-link>

		<b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
		<b-collapse id="nav-collapse" is-nav>
			<!-- Right aligned nav items -->
			<b-navbar-nav class="ml-auto" v-if="routeName != 'game'">
				<router-link
					v-if="!loggedIn"
					:to="{ name: 'login' }"
					tag="b-nav-item"
					active-class="active font-bold">Login
				</router-link>

				<router-link
					v-if="!loggedIn"
					:to="{ name: 'register' }"
					tag="b-nav-item"
					active-class="active font-bold">Register
				</router-link>

				<b-nav-item-dropdown right v-if="loggedIn">
					<!-- Using 'button-content' slot -->
					<template v-slot:button-content>
						<span> {{ user.name }} </span>
					</template>
					<b-dropdown-item> {{ user.email }} </b-dropdown-item>
					<router-link :to="{ name: 'logout' }" tag="b-dropdown-item">Logout
					</router-link>
				</b-nav-item-dropdown>
			</b-navbar-nav>
		</b-collapse>
	</b-navbar>
</template>

<script>
import { mapState, mapGetters } from "vuex";
export default {
	computed: {
		...mapState({
			user: (state) => state.auth.currentUser,
		}),
		...mapGetters(["loggedIn", "isAdmin"]),
		routeName(){
			return this.$route.name;
		}
	},
};
</script>

<style scoped>
.font-bold {
	font-weight: 500;
}
</style>
