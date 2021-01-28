<template>
  <div>
    <div class="question-card">
      <div class="question-genre">
        {{ question.category.name }}
      </div>
      <div class="question">
        {{ question.body }}
      </div>

      <div v-if="image" class="question-image">
        <img :src="image" alt="" height="200" />
      </div>
    </div>

    <div class="options">
      <div
        class="option"
        v-for="{ id, body } in options"
        :key="id"
        @click="handleSelection(id)"
        :class="id === selectedAns ? optionColor : ''"
      >
        {{ body }}
      </div>
    </div>

    <div v-if="isPlayerTurn" class="actions my-2">
      <b-button class="action-button" :disabled="timesUp" @click="submitAnswer">
        Confrim
      </b-button>
      <b-button class="action-button" :disabled="timesUp">
        Pass
      </b-button>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Display",

    props: {
      question: { type: Object },
      turnOf: { type: Number },
      timesUp: { type: Boolean },
    },

    data() {
      return {
        selectedAns: null,
        hasAnswered: false,
        optionColor: "active",

        correctSound: new Audio(require("@/assets/sounds/correct.mp3")),
        wrongSound: new Audio(require("@/assets/sounds/wrong.mp3")),
      };
    },

    computed: {
      quizId() {
        return this.$route.params.quizId;
      },

      options() {
        return this.question.options;
      },

      correctAnswer() {
        return this.question.answer;
      },

      isCorrectAnswer() {
        return this.correctAnswer.option_id == this.selectedAns;
      },

      image() {
        return this.question.image_url;
      },

      playerId() {
        return this.$route.params.playerId;
      },

      isPlayerTurn() {
        return (
          !this.hasAnswered && this.playerId && this.turnOf == this.playerId
        );
      },
    },

    watch: {
      question: {
        handler: function(nv) {
          if (nv) this.reset();
        },
      },
    },

    created() {
      this.listenForPlayerAnswer();
    },

    methods: {
      reset() {
        this.hasAnswered = false;
        this.optionColor = "active";
      },

      handleSelection(selectedId) {
        if (this.isPlayerTurn) this.selectedAns = selectedId;
      },

      submitAnswer() {
        if (this.selectedAns && !this.timesUp) {
          this.hasAnswered = true;
          this.$store.dispatch("submitScore", {
            playerId: this.playerId,
            score: this.isCorrectAnswer ? 5 : 0,
            optionId: this.selectedAns,
          });
        } else {
          alert("Select Answer first");
        }
      },

      listenForPlayerAnswer() {
        window.Echo.channel("quizy" + this.quizId).listen(
          "PlayerAnswered",
          (e) => {
            let { scores: updatedScores, optionId: optionSelected } = e;
            this.checkAnswer(optionSelected);
            this.$emit("onScoreUpdate", updatedScores);
            this.$emit("onAnswerSubmit");
          }
        );
      },

      checkAnswer(optionSelected) {
        this.selectedAns = optionSelected;
        if (optionSelected == this.correctAnswer.option_id) {
          this.optionColor = "correct";
          this.turnOf == this.playerId && this.correctSound.play();
        } else {
          this.optionColor = "wrong";
          this.turnOf == this.playerId && this.wrongSound.play();
        }
      },
    },
  };
</script>

<style>
  .question-card {
    position: relative;
    background-color: white;
    border-radius: 0.25rem;
    border: 3px solid rgba(192, 116, 192, 0.11);
    min-height: 150px;
    display: grid;
    place-content: center;
  }

  .question-genre {
    display: inline-block;
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translate(-50%, 17%);
    background: purple;
    color: white;
    border-radius: 2rem;
    box-shadow: 0 2px 10px lightgray;
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
  }

  .question {
    padding: 1.5rem 2rem;
    font-size: 1.25rem;
    font-weight: 500;
  }

  .question-image {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
  }

  .question-image img {
    height: 200px;
    border-radius: 0.25rem;
    object-fit: contain;
  }

  .options {
    margin-top: 1rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 1rem;
  }

  .option {
    padding: 0.75rem;
    border-radius: 0.25rem;
    border: 2px solid rgb(235, 189, 235);
    text-align: center;
    font-weight: 600;
    cursor: pointer;
  }

  .option:hover {
    background-color: rgba(202, 130, 202, 0.295);
    color: purple;
  }

  .active {
    background-color: rgba(202, 130, 202, 0.295);
    color: purple;
  }

  .correct,
  .correct:hover {
    background-color: rgb(42, 156, 42);
    color: white;
  }

  .wrong,
  .wrong:hover {
    background-color: red;
    color: white;
  }

  .actions {
    display: flex;
    justify-content: space-between;
    padding-top: 2rem;
  }

  .action-button {
    background-color: purple;
    display: inline-block;
    padding: 0.5rem 1.75rem;
    border-radius: 2rem;
    color: white;
    font-weight: 500;
    cursor: pointer;
  }

  .action-button:hover {
    background-color: rgba(206, 117, 206, 0.479);
    color: purple;
  }

  .action-button:disabled {
    background: lightgrey;
  }
</style>
