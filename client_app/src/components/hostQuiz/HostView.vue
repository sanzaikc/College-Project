<template>
  <div class="row">
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
      <b-card no-body header="Scoreboard">
        <b-list-group flush>
          <b-list-group-item
            v-for="(player, index) in players"
            :key="index"
            class="d-flex justify-content-between align-items-center"
          >
            <p class="m-0">
              <b-badge variant="success" class="py-1 mr-2" pill>1st</b-badge>
              {{ player.name }}
            </p>
            30
          </b-list-group-item>
        </b-list-group>
      </b-card>
    </div>
  </div>
</template>

<script>
  import { mapState } from "vuex";
  export default {
    props: {
      allQuestions: { type: Array },
      players: { type: Array },
    },

    data() {
      return {
        questionIndex: 0,
        quizStarted: this.start,
      };
    },

    computed: {
      ...mapState({ quizId: (state) => state.quiz.quizDetail.id }),
      currentQuestion() {
        return this.allQuestions[this.questionIndex];
      },

      isLastQuestion() {
        return this.questionIndex == this.allQuestions.length - 1;
      },
    },

    mounted() {
      this.startQuiz();
    },

    methods: {
      startQuiz() {
        this.changeCurrentQuestion(this.allQuestions[this.questionIndex].id);
      },
      next() {
        this.questionIndex++;
        this.changeCurrentQuestion(this.allQuestions[this.questionIndex].id);
      },

      async changeCurrentQuestion(id) {
        try {
          let { status } = await this.$store.dispatch("changeCurrentQuestion", {
            quizId: this.quizId,
            questionId: id,
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
