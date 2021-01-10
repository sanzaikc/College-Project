<template>
	<div :class="'border-' + randColor" class="border rounded px-3 py-1">
		<div v-if="edit" class="d-flex align-items-center">
			<input v-model="updatedCategory" type="text" class="form-control h-25" />
			<button class="btn">
				<b-icon
					icon="check2-circle"
					variant="success"
					@click="update(category)">
				</b-icon>
			</button> |
			<button class="btn">
				<b-icon icon="x-circle" variant="danger" @click="edit = false"></b-icon>
			</button>
		</div>
		<div v-else class=" d-flex justify-content-between align-items-center">
			<span :class="`text-${randColor} font-weight-bold`" v-text="category.name"></span>
			<div class="ml-4">
				<button class="btn btn-sm">
					<b-icon icon="pencil" variant="info" @click="edit = true"></b-icon>
				</button> |
				<button class="btn btn-sm">
					<b-icon icon="trash" variant="danger" @click="del(category)"></b-icon>
				</button>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		category: { type: Object },
	},
	data() {
		return {
			variants: [
				"success",
				"info",
				"danger",
				"warning",
				"secondary",
				"primary",
			],
			edit: false,
			updatedCategory: this.category.name,
		};
	},
	methods: {
		del(category) {
			this.$emit("onDelete", category.id);
		},
		update(category) {
			this.$emit("onUpdate", category.id, this.updatedCategory);
			this.updatedCategory = "";
			this.edit = false;
		},
	},
	computed: {
		randColor() {
			return this.variants[Math.floor(Math.random() * this.variants.length)];
		},
	},
};
</script>
