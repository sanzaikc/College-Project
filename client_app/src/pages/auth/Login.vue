<template>
	<div class="mx-auto mt-5 col-md-4 p-4 rounded-lg">
		<validation-observer ref="observer" v-slot="{ handleSubmit }">
			<b-form @submit.prevent="handleSubmit(onLogin)" class="bg-white p-4 rounded-lg shadow">
				<b-alert v-if="successMessage" variant="success" show dismissible fade>
					{{ successMessage }}
				</b-alert>

				<validation-provider name="Email"
					:rules="{ required: true, email: true }"
					v-slot="validationContext">
					<b-form-group id="input-group-2" label="Email" label-for="email">
						<b-form-input
							id="email"
							v-model="form.email"
							placeholder="Enter email"
							:state="getValidationState(validationContext)"
							aria-describedby="input-1-live-feedback">
						</b-form-input>
						<b-form-invalid-feedback id="input-1-live-feedback"> {{validationContext.errors[0] }}
						</b-form-invalid-feedback>
					</b-form-group>
				</validation-provider>

				<validation-provider name="Password"
					:rules="{ required: true, min: 6 }"
					v-slot="validationContext">
					<b-form-group id="input-group-3">
						<div class="d-flex justify-content-between align-items-center">
							<label for="password">Password</label>
							<small
								class="text-primary mb-2"
								role="button"
								@click="showPassword = !showPassword"
								v-text="showPassword ? 'Hide password' : 'Show password'">
							</small>
						</div>
						<b-form-input
							id="password"
							v-model="form.password"
							placeholder="Enter Password"
							:type="showPassword ? 'text' : 'password'"
							:state="getValidationState(validationContext)"
							aria-describedby="input-2-live-feedback"
						></b-form-input>
						<b-form-invalid-feedback id="input-2-live-feedback">{{
							validationContext.errors[0]
						}}</b-form-invalid-feedback>
					</b-form-group>
				</validation-provider>

				<div class="d-flex flex-column align-items-start">
					<!-- <router-link :to="{}"
						tag="b-link"
						class="text-center mb-3">Forgot password?
					</router-link> -->
					<b-button type="submit" variant="outline-primary w-100" :disabled="isLoading">
						{{ isLoading ? "Logging in" : "Login" }}
						<b-spinner
							v-if="isLoading"
							small
							type="grow"
							class="ml-2">
						</b-spinner>
					</b-button>
				</div>
				<hr>
				<router-link :to="{ name: 'register' }"
					class="btn btn-outline-info w-100">Sign up to Quizy
				</router-link>
			</b-form>
		</validation-observer>
	</div>
</template>

<script>
export default {
	mounted() {
		document.title = "Login";
	},
	props: {
		success: { type: String },
	},
	data() {
		return {
			showPassword: false,
			isLoading: false,
			form: {
				email: "",
				password: "",
			},
		};
	},
	methods: {
		getValidationState({ dirty, validated, valid = null }) {
			return dirty || validated ? valid : null;
		},
		onLogin() {
			this.isLoading = true;
			this.$store
				.dispatch("login", this.form)
				.then((res) => {
					if (res) {
						if (this.$store.state.auth.currentUser.is_admin)
							this.$router.push({ name: "admin" });
						else this.$router.push({ name: "host.home" });
					}
				})
				.catch((error) => {
					this.isLoading = false;
					this.form.password = "";
					this.$toasted.show(error, {
						theme: "bubble",
					});
				});
		},
	},
	computed: {
		successMessage() {
			return this.success;
		},
	},
};
</script>
