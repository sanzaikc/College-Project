<template>
	<div>
		<b-spinner
			v-if="isLoading"
			variant="primary"
			label="Spinning"
			style="position: fixed; top: 50%; left: 50%">
		</b-spinner>
		<div v-else>
			<h2>Users</h2>
			<table class="table table-hover border-top-0">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<th scope="col">Approved on</th>
						<th scope="col">Type</th>
						<th scope="col">Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(user, index) in userList" :key="user.id">
						<th scope="row" v-text="index + 1"></th>
						<td v-text="user.name"></td>
						<td>
							<b-badge
								:variant="isDisabled(user) ? 'secondary' : 'light'"
								v-text="
									isDisabled(user) ? 'Pending' : formatDate(user.updated_at)
								"
							></b-badge>
						</td>
						<td v-text="user.is_admin ? 'Admin' : 'Host'"></td>
						<td>
							<b-btn
								v-if="user.is_disabled"
								size="sm"
								:variant="user.is_disabled ? 'success' : 'light'"
								:disabled="isBusy"
								@click="updateStatus(user, 0)"
							>
								<b-spinner v-if="isBusy" small></b-spinner>
								<span v-else>Approve</span>
							</b-btn>
							<b-btn
								v-else
								size="sm"
								:variant="user.is_disabled ? 'light' : 'danger'"
								:disabled="isBusy"
								@click="updateStatus(user, 1)"
							>
								<b-spinner v-if="isBusy" small></b-spinner>
								<span v-else>Disable</span>
							</b-btn>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
import moment from "moment";
import { mapState } from "vuex";
export default {
	mounted() {
		this.$store.dispatch("retrieveUsers").finally(() => {
			this.isLoading = false;
		});
	},
	data() {
		return {
			isLoading: true,
			isBusy: false,
		};
	},
	methods: {
		isDisabled(user) {
			return user.is_disabled == 1 ? true : false;
		},
		formatDate(date) {
			return moment(date).format("DD-MMMM-YYYY");
		},
		updateStatus(user, status) {
			this.isBusy = true;
			this.$store
				.dispatch("updateStatus", { id: user.id, status: status })
				.then((res) => {
					this.isBusy = false;
					this.$toasted.show(
						res.is_disabled == 0
							? res.name + " is Approved"
							: res.name + " is Disabled"
					);
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
			userList: (state) => state.users.userList,
		}),
	},
};
</script>
