<template>
	<div
		v-if="isLoading"
		class="vh-100 d-flex justify-content-center align-items-center"
	>
		<b-spinner variant="primary mr-2" type="grow" label="Spinning"></b-spinner>
		<b-spinner
			style="width: 3rem; height: 3rem;"
			variant="primary "
			type="grow"
			label="Spinning"
		></b-spinner>
		<b-spinner variant="primary ml-2" type="grow" label="Spinning"></b-spinner>
	</div>
	<div v-else class="d-flex flex-column vh-100">
		<navbar></navbar>
		<div class="container-fluid flex-grow-1">
			<transition name="slide-fade" mode="out-in">
				<router-view></router-view>
			</transition>
		</div>
	</div>
</template>

<script>
import Navbar from "./components/Navbar.vue";

import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

export default {
	name: "App",
	components: {
		Navbar,
	},
	data() {
		return {
			isLoading: true,
		};
	},
	mounted() {
		this.$store.dispatch("restoreToken").finally(() => {
			this.isLoading = false;
		});
		window.Echo = new Echo({
			broadcaster: process.env.VUE_APP_BROADCAST_DRIVER,
			key: process.env.VUE_APP_PUSHER_APP_KEY,
			cluster: process.env.VUE_APP_PUSHER_APP_CLUSTER,
			forceTLS: true
		});
	},
};
</script>

<style>
/*** TRANSITIONS ***/
.fade-enter {
	opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
	transition: opacity 0.2s ease-out;
}

.fade-leave-to {
	opacity: 0;
}

.slide-fade-enter {
	transform: translateX(10px);
	opacity: 0;
}

.slide-fade-enter-active,
.slide-fade-leave-active {
	transition: all 0.2s ease;
}

.slide-fade-leave-to {
	transform: translateX(-10px);
	opacity: 0;
}
</style>
