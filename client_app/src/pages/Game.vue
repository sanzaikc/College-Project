<template>
  <div class="container h-75 d-flex align-items-center">
    <div v-if="quizHasStarted" class="p-4 rounded w-100">
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
</template>

<script>
  import PlayerView from "@/components/game/PlayerView";

  export default {
    components: {
      PlayerView,
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
      };
    },

    created() {
      this.listenForPlayerJoining();
      this.listenForQuestionChange();
    },

    methods: {
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
    },
  };
</script>
