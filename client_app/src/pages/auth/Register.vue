<template>
	<div class="mx-auto mt-5 col-md-4 p-4 rounded">
		<validation-observer ref="observer" v-slot="{ handleSubmit }">
			<b-form @submit.prevent="handleSubmit(onRegister)"  class="bg-white p-4 rounded-lg shadow">
				<validation-provider name="Name"
					:rules="{ required: true, min: 5 }"
					v-slot="validationContext">
					<b-form-group id="input-group-1" label="Name" label-for="name">
						<b-form-input
							id="name"
							v-model="form.name"
							type="text"
							placeholder="Enter name"
							:state="getValidationState(validationContext)"
							aria-describedby="input-1-live-feedback">
						</b-form-input>
						<b-form-invalid-feedback id="input-1-live-feedback">{{ validationContext.errors[0]}}
						</b-form-invalid-feedback>
					</b-form-group>
				</validation-provider>

				<validation-provider name="Email"
					:rules="{ required: true, email: true }"
					v-slot="validationContext">
					<b-form-group id="input-group-2" label="Email" label-for="email">
						<b-form-input
							id="email"
							v-model="form.email"
							placeholder="Enter email"
							:state="getValidationState(validationContext)"
							aria-describedby="input-2-live-feedback"
						></b-form-input>
						<b-form-invalid-feedback id="input-2-live-feedback">{{ validationContext.errors[0] }}
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
							aria-describedby="input-3-live-feedback"
						></b-form-input>
						<b-form-invalid-feedback id="input-3-live-feedback">{{
							validationContext.errors[0]
						}}</b-form-invalid-feedback>
					</b-form-group>
				</validation-provider>

				<b-button
					type="submit"
					variant="outline-primary"
					class="w-100"
					:disabled="isLoading">
					{{ isLoading ? "Registering" : "Register" }}
					<b-spinner
						v-if="isLoading"
						small
						type="grow"
						class="ml-2"
					></b-spinner>
				</b-button>
			</b-form>
		</validation-observer>
	</div>
</template>

<script>
export default {
	mounted() {
		document.title = "Register Account";
	},
	data() {
		return {
			showPassword: false,
			isLoading: false,
			form: {
				name: "",
				email: "",
				password: "",
			},
		};
	},
	methods: {
		getValidationState({ dirty, validated, valid = null }) {
			return dirty || validated ? valid : null;
		},
		onRegister() {
			this.isLoading = true;
			this.$store
				.dispatch("register", this.form)
				.then(() => {
					this.isLoading = false;
					this.$router.push({
						name: "login",
						params: {
							success: "User registered successfully, now login from here!",
						},
					});
				})
				.catch((error) => {
					this.isLoading = false;
					this.form.email = "";
					this.$toasted.show(error, {
						theme: "bubble",
					});
				});
		},
	},
};
</script>
