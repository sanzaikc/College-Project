<template>
  <div class="container h-75 d-flex align-items-center">
    <div v-if="quizHasStarted" class="p-4 rounded w-100">
      <h1 class="text-center">Q: {{ this.currentQuestion.body }}</h1>
      <hr />
      <div class="row d-flex justify-content-around">
        <h3
          v-for="option in shuffledOptions"
          :key="option.id"
          @click="selectedAns(option.id)"
          role="button"
          class="col-5 my-3 py-3 border rounded-pill shadow-sm text-center"
          :class="selected == option.id ? 'selected' : ''"
        >
          {{ option.body }}
        </h3>
      </div>
    </div>
    <div v-else>
      <h2>Hi, {{ player.name }}</h2>
      Wait till the host starts the quiz.
      <h5 v-show="players">Other Participants:</h5>
      <h5 v-for="(player, index) in players" :key="player.id" class="text-info">
        {{ index + 1 + "." }} {{ player.name }}
      </h5>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      player: {
        type: Object,
      },
    },

    data() {
      return {
        quizHasStarted: false,
        currentQuestion: "",
        selected: "",
        score: 0,
        players: [],
      };
    },

    mounted() {
      window.Echo.channel("quizy" + this.player.quiz_id).listen(
        "PlayerJoined",
        (e) => {
          this.players.push(e.player);
          // this.$toasted.show(e.player.name+" joined!");
        }
      );
      this.listenForQuestionChange();
    },

    methods: {
      listenForQuestionChange() {
        window.Echo.channel("Quizy" + this.player.quiz_id).listen(
          "QuestionChanged",
          (e) => {
            this.currentQuestion = e.question;
            this.quizHasStarted = true;
          }
        );
      },
      selectedAns(id) {
        this.selected = id;
        if (this.currentQuestion.answer.option_id == id) {
          this.score++;
        }
      },
    },

    computed: {
      shuffledOptions() {
        var array = this.currentQuestion.options;
        var currentIndex = array.length,
          temporaryValue,
          randomIndex;

        // While there remain elements to shuffle...
        while (0 !== currentIndex) {
          // Pick a remaining element...
          randomIndex = Math.floor(Math.random() * currentIndex);
          currentIndex -= 1;

          // And swap it with the current element.
          temporaryValue = array[currentIndex];
          array[currentIndex] = array[randomIndex];
          array[randomIndex] = temporaryValue;
        }

        return array;
      },
    },
  };
</script>

<style scoped>
  .selected {
    color: steelblue;
    background-color: #dbf3fc;
  }
</style>
