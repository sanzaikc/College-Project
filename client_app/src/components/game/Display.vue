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
        :ref="id"
        :key="id"
        @click="handleSelection(id)"
        :class="
          id === selectedAns
            ? optionColor
            : id === correctAnswer.option_id && showCorrectAnswer
            ? 'correct'
            : ''
        "
      >
        {{ body }}
      </div>
    </div>

    <div v-if="isPlayerTurn" class="actions my-2">
      <b-button class="action-button" :disabled="timesUp" @click="submitAnswer">
        Confirm
      </b-button>
    </div>
    <div v-if="isAudience" class="actions my-2">
      <b-button
        class="action-button"
        :disabled="disableButton && !timesUp"
        @click="showCorrectAnswer = true"
      >
        Show Answer
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
        optionColor: !this.isAudience ? "active" : "",
        showCorrectAnswer: false,
        disableButton: true,

        correctSound: new Audio(require("@/assets/sounds/correct.mp3")),
        wrongSound: new Audio(require("@/assets/sounds/wrong.mp3")),
        timesUpSound: new Audio(require("@/assets/sounds/airhorn.mp3")),
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

      isAudience() {
        return this.$route.path.includes("audience");
      },
    },

    watch: {
      question: {
        handler: function(nv) {
          if (nv) this.reset();
        },
      },

      showCorrectAnswer: {
        handler: function(nv) {
          if (nv) this.correctSound.play();
        },
      },

      timesUp: {
        handler: function(nv) {
          if (nv) this.timesUpSound.play();
        },
      },
    },

    created() {
      this.listenForPlayerAnswer();
    },

    methods: {
      reset() {
        this.hasAnswered = false;
        this.optionColor = !this.isAudience ? "active" : "";
        this.showCorrectAnswer = false;
        this.disableButton = true;
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
          this.showCorrectAnswer = true;
          this.optionColor = "correct";
          this.turnOf == this.playerId && this.correctSound.play();
        } else {
          this.disableButton = false;
          if (!this.isAudience) this.optionColor = "wrong";
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
    top: -25px;
    left: 50%;
    transform: translate(-50%, 17%);
    background: steelblue;
    color: white;
    border-radius: 2rem;
    box-shadow: 0 2px 10px lightgray;
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
    border: 2px solid steelblue;
    text-align: center;
    font-weight: 600;
    cursor: pointer;
  }

  .option:hover {
    background-color: rgba(135, 207, 235, 0.342);
  }

  .active {
    background-color: rgba(135, 207, 235, 0.342);
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
    background-color: steelblue;
    display: inline-block;
    padding: 0.5rem 1.75rem;
    border-radius: 2rem;
    color: white;
    font-weight: 500;
    cursor: pointer;
  }

  .action-button:hover {
    background-color: rgba(135, 207, 235, 0.342);
    color: black;
  }

  .action-button:disabled {
    background: lightgrey;
  }
</style>
