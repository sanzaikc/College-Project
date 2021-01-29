<template>
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
              button
              :disabled="selectedQuestions.includes(question.id)"
              :active="
                selectedQuestion && selectedQuestion.id === question.id
                  ? true
                  : false
              "
              class="py-2"
              :class="{
                'bg-secondary text-white': selectedQuestions.includes(
                  question.id
                ),
              }"
              v-for="question in categoryQuestion"
              :key="question.id"
              @click="
                !selectedQuestions.includes(question.id) &&
                  setQuestion(question)
              "
            >
              <div v-text="question.body" />
            </b-list-group-item>
          </b-list-group>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <h5>Settings</h5>
      <b-button-group class="my-2 w-100">
        <b-button variant="danger" @click="endQuiz">End quiz</b-button>
        <a class="btn btn-primary" target="blank" @click="audienceScreen">
          Open audience screen
        </a>
      </b-button-group>
      <div class="my-5" v-if="selectedQuestion">
        <div class="mb-1">Selected Question</div>

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
      <b-button
        block
        class="my-3"
        variant="primary"
        @click="next"
        :disabled="!selectedQuestion"
        >Show question
      </b-button>
    </div>
  </div>
</template>

<script>
  import { mapState } from "vuex";
  // import { mapGetters, mapState } from "vuex";

  export default {
    props: {
      allQuestions: { type: Array },
      turnList: { type: Array },
    },

    data() {
      return {
        turnIndex: 0,
        questionIndex: -1,
        quizStarted: this.start,
        selectedQuestion: null,
        selectedQuestions: [],
      };
    },

    mounted() {
      this.selectedQuestion = this.allQuestions[0];
    },

    computed: {
      ...mapState({
        quizId: (state) => state.quiz.quizDetail.id,
        players: (state) => state.quiz.players,
      }),
      // ...mapGetters(["players"]),

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

    // mounted() {
    //   this.startQuiz();
    // },

    methods: {
      startQuiz() {
        this.$emit("onGameStart");
        // this.changeCurrentQuestion(this.allQuestions[this.questionIndex].id);
      },

      setQuestion(question) {
        this.selectedQuestion = question;
      },

      next() {
        this.selectedQuestions.push(this.selectedQuestion.id);
        if (this.questionIndex < 0) this.startQuiz();
        ++this.questionIndex;
        // this.changeCurrentQuestion(this.allQuestions[this.questionIndex].id);
        this.changeCurrentQuestion(this.selectedQuestion.id);
      },

      chooseRandomQuestion() {
        this.selectedQuestion = this.allQuestions[
          Math.floor(Math.random() * this.allQuestions.length)
        ];
        console.log(this.selectedQuestion);
      },

      async changeCurrentQuestion() {
        try {
          let turnId = this.turnList[this.turnIndex];
          this.$emit("input", turnId);
          let { status } = await this.$store.dispatch("changeCurrentQuestion", {
            quizId: this.quizId,
            questionId: this.selectedQuestion.id,
            playerId: turnId,
          });
          this.turnIndex = (this.turnIndex + 1) % this.turnList.length;
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

      audienceScreen() {
        let routeData = this.$router.resolve({
          name: "audience",
          params: { quizId: this.quizId },
        });
        window.open(routeData.href, "_blank");
      },
    },
  };
</script>

<style></style>
