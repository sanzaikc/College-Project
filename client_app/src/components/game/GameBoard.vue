<template>
  <div>
    <b-row class="my-4">
      <b-col cols="12" lg="3">
        <!-- scoreboard if quiz has been started -->
        <!-- else list of players -->
        <div v-if="quizHasStarted">
          <scoreboard :scores="scores" :turnOf="playerTurnId" />
        </div>
      </b-col>
      <b-col cols="12" lg="6">
        <!-- the question -->
        <div v-if="quizHasStarted">
          <Display
            :question="question"
            :turnOf="playerTurnId"
            @onScoreUpdate="updateScores"
          />
        </div>

        <div v-else>
          <h2 class="mb-2">Please wait for the host to start the quiz</h2>
          <b-card v-if="!quizHasStarted">
            Participants
            <ul>
              <li v-for="player in players" :key="player.id">
                {{ player.name }} - {{ player.score }}
              </li>
            </ul>
          </b-card>
        </div>
      </b-col>

      <b-col cols="12" lg="3">
        <div v-if="quizHasStarted">
          <div v-if="!timesUp" style="font-size: 5rem">{{ time }}s</div>
          <div v-else>Time is up</div>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
  import $axios from "@/plugins/axios";
  import Display from "./Display.vue";
  import Scoreboard from "../hostQuiz/Scoreboard.vue";

  export default {
    components: { Display, Scoreboard },

    data() {
      return {
        quiz: null,
        quizEnded: false,
        time: 30,
        scores: [],
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

      quizHasStarted() {
        if (this.quiz) return !!this.quiz.current_question;
        return false;
      },

      question() {
        if (this.quiz && this.quiz.current_question)
          return this.quiz.current_question;
        return {};
      },

      options() {
        return this.question && this.question.options;
      },

      playerTurnId() {
        return this.quiz.player_id;
      },

      correctAnswer() {
        return this.question && this.question.answer;
      },

      timesUp() {
        return !this.time > 0;
      },
    },

    watch: {
      players: {
        immediate: true,
        handler: function(nv) {
          if (nv) {
            this.initiateScores();
          }
        },
      },
    },

    created() {
      this.listenForPlayerJoining();

      this.listenForQuestionChange();

      // this.listenForPlayerAnswer();

      this.listenForPass();

      this.listenForQuizEnd();
    },

    mounted() {
      // request for quiz details; this request's response will return players, player's score, current question
      this.fetchQuizDetails();
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
        window.Echo.channel("quizy" + this.quizId).listen(
          "PlayerJoined",
          (e) => {
            this.quiz = { ...this.quiz, players: e.players };

            this.$toasted.show(e.player.name + " joined!");
          }
        );
      },

      listenForQuestionChange() {
        window.Echo.channel("quizy" + this.quizId).listen(
          "QuestionChanged",
          (e) => {
            this.quiz = {
              ...this.quiz,
              current_question: e.question,
              player_id: e.quiz.player_id,
              players: e.players,
            };
            this.time = 30;
            // this.startTimer();
          }
        );
      },

      listenForQuizEnd() {
        window.Echo.channel("quizy" + this.quizId).listen("QuizEnded", (e) => {
          if (e) {
            this.quizEnded = true;
          }
        });
      },

      updateScores(updatedScores) {
        this.scores = this.scores.map((score) => {
          let updatedScore = updatedScores.find(
            (us) => us.player_id == score.id
          );
          return updatedScore ? { ...score, score: updatedScore.score } : score;
        });
      },

      // listenForPlayerAnswer() {
      //   window.Echo.channel("quizy" + this.quizId).listen(
      //     "PlayerAnswered",
      //     (e) => {
      //       console.log(e);
      //       let updatedScores = e.scores;
      //       this.scores = this.scores.map((score) => {
      //         let updatedScore = updatedScores.find(
      //           (us) => us.player_id == score.id
      //         );
      //         return updatedScore
      //           ? { ...score, score: updatedScore.score }
      //           : score;
      //       });
      //     }
      //   );
      // },

      listenForPass() {},

      startTimer() {
        setTimeout(() => {
          if (this.time > 0) {
            --this.time;
            this.startTimer();
          }
        }, 1000);
      },

      initiateScores() {
        this.scores = this.players.map((player) => ({
          id: player.id,
          name: player.name,
          score: player.score ? player.score.score : 0,
        }));
      },
    },
  };
</script>

<style></style>
