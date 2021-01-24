<template>
  <div>
    <div>
      <!-- scoreboard if quiz has been started -->
      <!-- else list of players -->
      <pre>
        {{players.length}}
      </pre>
    </div>
    <div>
      <!-- the question -->
      <pre>
        {{question.body}}
      </pre>
    </div>
    <div>
      <!-- Timer -->
    </div>
  </div>
</template>

<script>
import $axios from "@/plugins/axios";

export default {
  data() {
    return {
      quiz: null,
    };
  },

  computed: {
    quizId() {
      return this.$route.params.quizId;
    },

    players() {
      if (this.quiz) {
        return this.quiz.players;
      }
      return [];
    },

    scores() {
      if (this.quiz)
        return this.players.map((player) => ({
          id: player.id,
          name: player.name,
          score: player.score ? player.score.score : 0,
        }));
      return [];
    },
    question() {
      if (this.quiz) return this.quiz.current_question;
      return {};
    },
  },

  mounted() {
    // request for quiz details; this request's response will players, players' score, current question
    this.fetchQuizDetails();

    this.listenForPlayerJoining();
    this.listenForQuestionChange();

    this.listenForPlayerAnswer();
    this.listenForPass();

    this.listenForQuizEnd();
  },

  methods: {
    fetchQuizDetails() {
      $axios
        .get(`/getQuizDetails/${this.quizId}`)
        .then((res) => {
          if (res.status === 200) {
            this.quiz = res.data.quiz;
          }
        })
        .catch((err) => console.log(err))
        .finally(() => console.log("at last"));
    },

    listenForPlayerJoining() {
      window.Echo.channel("quizy" + this.quizId).listen("PlayerJoined", (e) => {
        this.quiz = { ...this.quiz, players: e.players };

        this.$toasted.show(e.player.name + " joined!");
      });
    },

    listenForPlayerAnswer() {},

    listenForPass() {},

    listenForQuestionChange() {
      window.Echo.channel("quizy" + this.quizId).listen(
        "QuestionChanged",
        (e) => {
          this.quiz = { ...this.quiz, current_question: e.question };
          // this.quizStarted = true;
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
