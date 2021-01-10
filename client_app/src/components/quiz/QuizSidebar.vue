<template>
  <div>
    <button id="sidebar" v-b-toggle.sidebar-backdrop hidden> </button>
    <b-sidebar
        id="sidebar-backdrop"
        :title="text + 'e Quiz'"
        backdrop
        shadow
        :lazy="true"
        width="400px">

          <div class="px-3 py-2 border-top">

            <validation-observer ref="observer" v-slot="{ handleSubmit }">
              <b-form @submit.prevent="handleSubmit(onSubmit)">
                <validation-provider
                  name="Name"
                  :rules="{ required: true, min: 3}"
                  v-slot="validationContext">
                  <b-form-group id="input-group-1" label="Name" label-for="name">
                    <b-form-input
                      id="name"
                      v-model="quiz.name"
                      type="text"
                      placeholder="Enter name"
                      :state="getValidationState(validationContext)"
                      aria-describedby="input-1-live-feedback"
                    ></b-form-input>
                    <b-form-invalid-feedback
                      id="input-1-live-feedback"
                    >{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>

                <validation-provider
                  name="Description"
                  :rules="{ required: true, min: 10}"
                  v-slot="validationContext">
                  <b-form-group id="input-group-2" label="Description" label-for="description">
                    <b-form-textarea
                      id="description"
                      v-model="quiz.description"
                      placeholder="Enter description for the quiz"
                      rows="3"
                      max-rows="6"
                      :state="getValidationState(validationContext)"
                      aria-describedby="input-2-live-feedback"
                    >></b-form-textarea>
                    <b-form-invalid-feedback
                      id="input-2-live-feedback"
                    >{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>

                <b-form-group id="input-group-2" label="Image (optional)" label-for="image">
                  <b-form-file
                    id="image"
                    v-model="quiz.image"
                    :state="Boolean(quiz.image)"
                    placeholder="Choose a file or drop it here..."
                    accept=".jpg, .png, .gif"
                    @change="onFileChange"
                  ></b-form-file>
                </b-form-group>

                <div class="my-3 d-flex">
                  <img
                    v-if="quiz.image"
                    :src="url"
                    alt
                    height="150"
                    class="mx-auto rounded-lg border border-success"
                  />
                </div>

                <b-button
                  type="submit"
                  variant="outline-primary"
                  class="w-100"
                  :disabled="value">
                  {{ value ? text+'ing' : text+'e' }}
                  <b-spinner v-if="value" small type="grow" class="ml-2"></b-spinner>
                </b-button>
              </b-form>
            </validation-observer>
          </div>
      </b-sidebar>
  </div>
</template>

<script>
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
        default: null
      }
    },
    watch : {
      qData: function(newVal){
        if(this.qData != null){
          this.quiz = {
            name: newVal.name,
            description: newVal.description,
            image: null,
          };
        }
      }
    },
    data(){
        return{
            quiz: {
                name: '',
                description: '',
                image: null,
            },
            url: "",
        }
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onFileChange(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file ? file : "");
        },
        onSubmit(){
          this.$emit('onSubmit', this.quiz);
        }   
    },
}
</script>