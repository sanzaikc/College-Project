<template>
  <div>
    <div class="row d-flex justify-content-between mb-5">
      <h4>{{ player.name }}</h4>
      <h4 v-if="player.score">Score: {{ player.score }}</h4>
    </div>
    <div v-if="currentQuestion">
      <h3 class="text-center">{{ currentQuestion.body }}</h3>
      <hr />
      <img v-if="currentQuestion.image" :src="currentQuestion.image_url" style="height: 200px; width: 100%; object-fit: contain" />
      <div class="row d-flex justify-content-around">
        <h5
          v-for="{ id, body } in shuffledOptions"
          :key="id"
          @click="turnId === playerId && !alreadyAnswered && selectedAnswer(id)"
          role="button"
          class="col-5 my-3 py-3 border rounded-pill shadow-sm text-center"
          :class="selectedAnsId === id ? ansStatus : ''"
        >
          {{ body }}
        </h5>
      </div>
      <div class="d-flex justify-content-between">
        <b-button
          v-if="turnId === playerId"
          @click="pass"
          :disabled="alreadyAnswered"
        >
          Pass
        </b-button>
        <b-button
          v-if="turnId === playerId"
          variant="primary"
          :disabled="disabled"
          @click="submit"
        >
          Confirm
        </b-button>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from "vuex";
  export default {
    props: {
      playerId: { type: Number },
      currentQuestion: { type: Object },
      turnId: { type: Number },
    },

    data() {
      return {
        selectedAnsId: null,
        ansStatus: "",
        incrementScoreBy: 5,
        disabled: true,
        alreadyAnswered: false,
      };
    },

    watch: {
      currentQuestion: {
        immediate: true,
        handler: function(newValue) {
          if (newValue) {
            this.alreadyAnswered = false;
          }
        },
      },
    },

    computed: {
      ...mapGetters({
        player: "currentPlayer",
      }),

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

      correctAns() {
        return this.currentQuestion.answer.option_id === this.selectedAnsId;
      },
    },

    mounted() {
      this.listenForScoreChange();
    },

    methods: {
      listenForScoreChange() {
        window.Echo.channel("quizy" + this.player.quiz_id).listen(
          "ScoreChanged",
          (e) => {
            let { scores } = e;
            scores.forEach(({ player_id, score }) => {
              this.$store.commit("UPDATE_SCORE", { player_id, score });
            });
          }
        );
      },

      selectedAnswer(id) {
        this.disabled = false;
        this.selectedAnsId = id;
        this.ansStatus = "selected";
      },

      submit() {
        this.alreadyAnswered = true;
        this.disabled = true;
        var correct = new Audio(require("@/assets/sounds/correct.mp3"));
        var wrong = new Audio(require("@/assets/sounds/wrong.mp3"));
        setTimeout(() => {
          if (this.correctAns) {
            correct.play();
            this.ansStatus = "correct";
            this.$store.dispatch("submitScore", {
              playerId: this.playerId,
              score: this.incrementScoreBy,
            });
          } else {
            wrong.play();
            this.ansStatus = "wrong";
          }
        }, 1000);
      },

      pass() {
        console.log('passing')
      }
    },
  };
</script>

<style scoped>
  .selected {
    color: steelblue;
    background-color: #dbf3fc;
  }

  .correct {
    background: lightgreen;
    color: white;
  }

  .wrong {
    background-color: red;
    color: white;
  }
</style>
