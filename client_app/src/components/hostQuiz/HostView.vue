<template>
  <!-- <div class="row">
    <div class="p-4 border rounded col-md-8">
      <h2>{{ currentQuestion.body }}</h2>
      <hr />
      <h4
        v-for="(option, index) in currentQuestion.options"
        :key="index"
        class="text-secondary bg-light rounded-pill px-3 py-2 w-50"
      >
        {{ index + 1 + "." }} {{ option.body }}
      </h4>
      <div class="float-right w-25">
        <button
          v-if="!isLastQuestion"
          @click="next"
          class="btn btn-outline-primary btn-block"
        >
          Next Question
        </button>
        <button
          v-else
          @click="endQuiz"
          class="btn btn-outline-danger btn-block"
        >
          End Quiz
        </button>
      </div>
    </div>
    <div class="col-md-4">
      <Scoreboard :quizId="quizId" :players="players" />
    </div>
  </div> -->
  <div class="row">
    <div
      class="col-lg-8"
      style="max-height: calc(100vh - 180px); overflow-y: auto;"
    >
      <h5>Questions</h5>
      <div class="row">
        <div
          class="col-6 mb-3"
          v-for="(categoryQuestion, key) in categoryWiseQuestions"
          :key="key"
        >
          <h6>{{ categoryQuestion[0].category.name }}</h6>
          <b-list-group>
            <b-list-group-item
              :active="
                selectedQuestion && selectedQuestion.id === question.id
                  ? true
                  : false
              "
              class="py-2"
              v-for="question in categoryQuestion"
              :key="question.id"
            >
              <div v-text="question.body" />
            </b-list-group-item>
          </b-list-group>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <h5>Settings</h5>
      <b-button-group class="my-3 w-100">
        <b-button variant="danger" @click="endQuiz">End quiz</b-button>
        <a
          class="btn btn-primary"
          href="http://localhost:8080/audience-screen"
          target="blank"
          >Open audience screen</a
        >
      </b-button-group>
      <div class="my-5" v-if="selectedQuestion">
        <div v-text="selectedQuestion.body" />
        <b-badge
          pill
          v-for="option in selectedQuestion.options"
          :key="option.id"
          :variant="
            option.id === selectedQuestion.answer.option_id
              ? 'success'
              : 'light'
          "
        >
          {{ option.body }}
        </b-badge>
      </div>
      <b-button class="p-0" block variant="link" @click="chooseRandomQuestion"
        >Choose random question</b-button
      >
      <b-button class="p-0" block id="popover-target-1" variant="link">
        Choose random question from a category
      </b-button>
      <b-popover target="popover-target-1" triggers="hover" placement="bottom">
        <template #title>Available Categories</template>
        <p>General</p>
        <p>Science</p>
        <p>Sports</p>
      </b-popover>
      <b-button
        block
        class="my-3"
        variant="primary"
        @click="changeCurrentQuestion"
        >Show question
      </b-button>
    </div>
  </div>
</template>

<script>
  import { mapGetters, mapState } from "vuex";

  export default {
    props: {
      allQuestions: { type: Array },
    },

    data() {
      return {
        questionIndex: 0,
        quizStarted: this.start,
        selectedQuestion: null,
      };
    },

    computed: {
      ...mapState({ quizId: (state) => state.quiz.quizDetail.id }),
      ...mapGetters(["players"]),

      currentQuestion() {
        return this.allQuestions[this.questionIndex];
      },

      isLastQuestion() {
        return this.questionIndex == this.allQuestions.length - 1;
      },

      categoryWiseQuestions() {
        let questions = {};
        this.allQuestions.forEach((question) => {
          if (questions[question.category_id]) {
            questions[question.category_id] = [
              ...questions[question.category_id],
              question,
            ];
          } else {
            questions[question.category_id] = [question];
          }
        });
        return questions;
      },
    },

    mounted() {
      this.startQuiz();
    },

    methods: {
      startQuiz() {
        this.$emit("onGameStart");
        this.changeCurrentQuestion(this.allQuestions[this.questionIndex].id);
      },

      next() {
        this.questionIndex++;
        this.changeCurrentQuestion(this.allQuestions[this.questionIndex].id);
      },

      chooseRandomQuestion() {
        this.selectedQuestion = this.allQuestions[
          Math.floor(Math.random() * this.allQuestions.length)
        ];
        console.log(this.selectedQuestion);
      },

      // async changeCurrentQuestion(id) {
      //   try {
      //     let { status } = await this.$store.dispatch("changeCurrentQuestion", {
      //       quizId: this.quizId,
      //       questionId: id,
      //     });
      //     if (status === 200) {
      //       console.log("Success");
      //     }
      //   } catch (error) {
      //     console.log(error);
      //   }
      // },

      async changeCurrentQuestion() {
        try {
          let { status } = await this.$store.dispatch("changeCurrentQuestion", {
            quizId: this.quizId,
            questionId: this.selectedQuestion.id,
          });
          if (status === 200) {
            console.log("Success");
          }
        } catch (error) {
          console.log(error);
        }
      },

      endQuiz() {
        this.$emit("onGameFinish");
      },
    },
  };
</script>

<style></style>
