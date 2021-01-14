<template>
  <div class="container h-75">
    <div class="row mt-5 justify-content-center" v-if="quizHasEnded">
      <div class="col-lg-4">
        <scoreboard :quizId="player.quiz_id" />
      </div>
    </div>
    <div class="row mt-5 justify-content-center" v-else>
      <div class="col-lg-8" v-if="quizHasStarted">
        <PlayerView :currentQuestion="currentQuestion" :playerId="player.id" />
      </div>
      <div v-else>
        <h2>Hi, {{ player.name }}</h2>
        Wait till the host starts the quiz.
        <div v-if="players.length > 0">
          <h5>Other Participants:</h5>
          <h5
            v-for="(player, index) in players"
            :key="player.id"
            class="text-info"
          >
            {{ index + 1 + "." }} {{ player.name }}
          </h5>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import PlayerView from "@/components/game/PlayerView";
  import Scoreboard from '../components/hostQuiz/Scoreboard.vue';
  import { mapMutations } from "vuex";

  export default {
    components: {
      PlayerView,
      Scoreboard,
    },

    props: {
      player: {
        type: Object,
      },
    },

    data() {
      return {
        quizHasStarted: false,
        currentQuestion: "",
        players: [],
        quizHasEnded: false,
      };
    },

    created() {
      this.listenForPlayerJoining();
      this.listenForQuestionChange();
      this.listenForQuizEnd();
    },

    methods: {
      ...mapMutations(["REPLACE_PLAYERS"]),

      listenForPlayerJoining() {
        window.Echo.channel("quizy" + this.player.quiz_id).listen(
          "PlayerJoined",
          (e) => {
            this.players.push(e.player);
            // this.$toasted.show(e.player.name+" joined!");
          }
        );
      },

      listenForQuestionChange() {
        window.Echo.channel("Quizy" + this.player.quiz_id).listen(
          "QuestionChanged",
          (e) => {
            this.currentQuestion = e.question;
            this.quizHasStarted = true;
          }
        );
      },

      listenForQuizEnd() {
        window.Echo.channel("quizy" + this.player.quiz_id).listen(
          "QuizEnded",
          (e) => {
            this.quizHasEnded = true;
            this.REPLACE_PLAYERS(e.quiz.players.map(player => ({...player, score: player.score.score })));
          }
        );
      },
    },
  };
</script>
