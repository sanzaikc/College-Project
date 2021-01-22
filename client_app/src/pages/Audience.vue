<template>
  <div>
    <div
      v-if="!quizHasEnded"
      class="d-flex justify-content-between align-items-start mt-5"
    >
      <div class="col-lg-3">
        <div v-if="!quizStarted">
          <h5>Participants</h5>
          <div v-if="players.length > 0">
            <h5
              v-for="(player, index) in players"
              :key="player.id"
              class="text-info"
            >
              {{ index + 1 + "." }} {{ player.name }}
            </h5>
          </div>
          <div v-else>
            <p>Waiting for players!</p>
          </div>
        </div>
        <Scoreboard v-else :quizId="quizId" />
      </div>

      <div class="col-lg-9">
        <h3 v-if="!quizStarted">
          Please wait while the host starts the quiz
        </h3>
        <div v-else>
          <h1 class="text-center">Q: {{ this.currentQuestion.body }}</h1>
          <hr />
      <img v-if="currentQuestion.image" :src="currentQuestion.image_url" style="height: 200px; width: 100%; object-fit: contain" />

          <div class="row d-flex justify-content-around">
            <h3
              v-for="{ id, body } in currentQuestion.options"
              :key="id"
              role="button"
              class="col-5 my-3 py-3 border rounded-pill shadow-sm text-center"
            >
              {{ body }}
            </h3>
          </div>
        </div>
      </div>
    </div>
    <h5 v-else class="text-center">
      Quiz has end
    </h5>
  </div>
</template>

<script>
  import { mapState } from "vuex";
  import Scoreboard from "../components/hostQuiz/Scoreboard.vue";
  export default {
    components: { Scoreboard },
    props: {
      quizId: { type: Number },
    },

    data() {
      return {
        quizStarted: false,
        quizHasEnded: false,
        currentQuestion: "",
      };
    },

    computed: {
      ...mapState({
        players: (state) => state.quiz.players,
      }),
    },

    mounted() {
      this.listenForPlayerJoining();
      this.listenForQuestionChange();
      this.listenForQuizEnd();
    },

    methods: {
      listenForPlayerJoining() {
        window.Echo.channel("quizy" + this.quizId).listen(
          "PlayerJoined",
          (e) => {
            e.player.score = 0;
            this.$store.commit("ADD_PLAYERS", e.player);
            this.$toasted.show(e.player.name + " joined!");
          }
        );
      },
      listenForQuestionChange() {
        window.Echo.channel("Quizy" + this.quizId).listen(
          "QuestionChanged",
          (e) => {
            this.currentQuestion = e.question;
            this.quizStarted = true;
          }
        );
      },
      listenForQuizEnd() {
        window.Echo.channel("quizy" + this.quizId).listen("QuizEnded", (e) => {
          this.quizHasEnded = true;
          this.REPLACE_PLAYERS(
            e.quiz.players.map((player) => ({
              ...player,
              score: player.score.score,
            }))
          );
        });
      },
    },
  };
</script>

<style></style>
