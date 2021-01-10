<template>
  <div>
    <button id="sidebar" v-b-toggle.sidebar-backdrop hidden></button>
    <b-sidebar
      id="sidebar-backdrop"
      :title="text + 'e Question'"
      backdrop
      shadow
      :lazy="true"
      width="400px"
    >
      <b-form class="p-3" @submit.prevent="onSubmit">
        <b-form-textarea
          id="textarea"
          v-model="question.body"
          placeholder="Question"
          rows="3"
          max-rows="3"
          class="my-3"
          required
        ></b-form-textarea>

        <b-form-group id="input-group-3" label="Category" label-for="category">
          <b-form-select
            id="category"
            v-model="question.category_id"
            :options="categories"
            required
          ></b-form-select>
        </b-form-group>

        <b-form-group
          v-for="(option, key) in question.options"
          :key="key"
          :id="'option' + key"
          :label="key === 0 ? 'Answer' : 'Option ' + key"
          :label-for="'option' + key">
          <b-form-input
            id="answer"
            v-model="question.options[key].body"
            required
            :placeholder="key === 0 ? 'Correct answer' : 'Option'"
          ></b-form-input>
        </b-form-group>

        <b-form-group>
          <div
            class="d-flex justify-content-between align-items-center"
            @click="show = !show"
            style="cursor: pointer"
          >
            <label for="image">Image (optional)</label>
            <b-icon :icon="show ? 'chevron-compact-up' : 'chevron-compact-down'"></b-icon>
          </div>
          <div v-if="show">
            <b-form-file
              id="image"
              v-model="question.image"
              :state="Boolean(question.image)"
              accept=".jpg, .png, .gif"
              placeholder="Choose a file or drop it here..."
              ref="file-input"
              @change="onFileChange"
            ></b-form-file>
            <div class="my-2 d-flex">
              <img
                v-if="question.image"
                :src="url"
                alt
                height="150"
                class="mx-auto rounded-lg border border-success"
              />
            </div>
          </div>
        </b-form-group>

        <div class="d-flex justify-content-between">
          <b-button type="submit" variant="outline-primary" class="w-75" :disabled="value">
            {{ value ? text+'ing' : text+'e' }}
            <b-spinner v-if="value" small type="grow" class="ml-2"></b-spinner>
          </b-button>
          <b-button type="reset" variant="outline-danger" @click="reset">Reset</b-button>
        </div>
      </b-form>
    </b-sidebar>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  props: {
    text: {
      type: String,
    },
    value: {
      type: Boolean,
    },
    qData: {
      type: Object,
    },
  },
  data() {
    return {
      show: false,
      question: {
        body: "",
        category_id: null,
        image: null,
        options: [
          {
            body: "",
            correct: true,
          },
          {
            body: "",
            correct: false,
          },
          {
            body: "",
            correct: false,
          },
          {
            body: "",
            correct: false,
          },
        ],
      },
      url: "",
    };
  },
  watch: {
    qData: function (newVal) {
      if (newVal !== null) {
        this.question = {
          body: newVal.body,
          category_id: newVal.category_id,
          image: newVal.image,
          options: [
            ...newVal.options.map((option, i) => ({
              id: option.id,
              body: option.body,
              correct: i === 0 ? true : false,
            })),
          ],
        };
      } else {
        this.question = {
          body: "",
          category_id: null,
          image: null,
          options: [
            {
              body: "",
              correct: true,
            },
            {
              body: "",
              correct: false,
            },
            {
              body: "",
              correct: false,
            },
            {
              body: "",
              correct: false,
            },
          ],
        };
      } 
    },
  },
  methods: {
    onFileChange(e) {
      const file = e.target.files[0];
      this.url = URL.createObjectURL(file ? file : "");
    },
    reset() {
      if(this.$refs["file-input"]) this.$refs["file-input"].reset();
      this.question.category_id = null;
    },
    onSubmit() {
        if (!this.qData) {
        this.$emit("onSubmit", this.question);
      } else {
        this.$emit("onUpdate", { id: this.qData.id, data: this.question });
      }
    },
  },
  computed: {
    ...mapGetters(["categories"]),
  },
};
</script>