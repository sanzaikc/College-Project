<template>
	<div class="row h-100">
		<div class="col-md-2 bg-light">
			<b-nav vertical class="w-100 text py-3">
				<router-link :to="{ name: 'admin.users' }"
					tag="b-nav-item"
					active-class="activeTab">
					<b-icon
						icon="person"
						variant="dark"
						@click="edit = false"
						class="mr-2"
					></b-icon>Users
				</router-link>
				<router-link :to="{ name: 'admin.categories' }"
					tag="b-nav-item"
					active-class="activeTab">
					<b-icon
						icon="folder"
						variant="dark"
						@click="edit = false"
						class="mr-2">
					</b-icon>Categories
				</router-link>
				<!-- <router-link :to="{ name: 'admin.users' }" 
					tag="b-nav-item">
					<b-icon
						icon="book"
						variant="dark"
						@click="edit = false"
						class="mr-2">
					</b-icon>Quiz
				</router-link> -->
			</b-nav>
		</div>
		<div class="col-md-10 pl-4 py-3 bg-white">
			<transition name="slide-fade" mode="out-in">
				<keep-alive>
					<router-view></router-view>
				</keep-alive>
			</transition>
		</div>
	</div>
</template>

<script>
export default {
	mounted() {
		document.title = "Admin";
	},
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
