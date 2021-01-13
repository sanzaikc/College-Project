<template>
  <div>
    <h1 class="text-center">Q: {{ this.currentQuestion.body }}</h1>
    <hr />
    <div class="row d-flex justify-content-around">
      <h3
        v-for="{ id, body } in shuffledOptions"
        :key="id"
        @click="selectedAnswer(id)"
        role="button"
        class="col-5 my-3 py-3 border rounded-pill shadow-sm text-center"
        :class="selectedAnsId === id ? ansStatus : ''"
      >
        {{ body }}
      </h3>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      playerId: { type: Number },
      currentQuestion: { type: Object },
    },

    data() {
      return {
        selectedAnsId: null,
        ansStatus: "",
        playerScore: 0,
      };
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

      correctAns() {
        return this.currentQuestion.answer.option_id === this.selectedAnsId;
      },
    },

    methods: {
      selectedAnswer(id) {
        this.selectedAnsId = id;
        this.ansStatus = "selected";

        setTimeout(() => {
          if (this.correctAns) {
            this.ansStatus = "correct";
            this.$store.dispatch("submitScore", {
              playerId: this.playerId,
              score: ++this.playerScore,
            });
          } else {
            this.ansStatus = "wrong";
          }
        }, 500);
      },
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
