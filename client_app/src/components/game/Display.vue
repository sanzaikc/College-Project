<template>
  <div>
    <div class="question-card">
      <div class="question-genre">
        General Knowledge
      </div>
      <div class="question">
        {{ question.body }}
      </div>
    </div>

    <div class="options">
      <div class="option" v-for="{ id, body } in options" :key="id">
        {{ body }}
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      question: { type: Object },
    },

    computed: {
      options() {
        return this.question.options;
      },

      correctAnswer() {
        return this.question.answer;
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
  }

  .question-genre {
    display: inline-block;
    position: absolute;
    top: -17%;
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
    padding-top: 2.5rem;
    text-align: center;
    font-weight: 500;
    font-size: 1.25rem;
  }

  .options {
    margin-top: 1rem;
    display: grid;
    grid-template-columns: auto auto;
    grid-gap: 1rem;
  }

  .option {
    padding: 0.75rem;
    border-radius: 0.25rem;
    border: 2px solid rgb(235, 189, 235);
    text-align: center;
    font-size: 1.25rem;
    font-weight: 600;
    cursor: pointer;
  }

  .option:hover {
    background-color: rgba(202, 130, 202, 0.295);
    color: purple;
  }

  .buttons {
    border-radius: 2rem;
    box-shadow: 0 2px 10px lightgray;
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
  }
</style>
