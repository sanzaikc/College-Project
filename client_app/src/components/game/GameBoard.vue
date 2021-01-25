<template>
  <div>
    <b-row class="my-5">
      <b-col cols="12" lg="2">
        <!-- scoreboard if quiz has been started -->
        <!-- else list of players -->
        <b-card v-if="quizStarted"> Scoreboard </b-card>
      </b-col>
      <b-col cols="12" lg="8">
        <!-- the question -->
        <div v-if="quizStarted">
          <display :question="question" />
        </div>

        <div v-else>
          <h2 class="mb-2">Please wait for the host to start the quiz</h2>
          <b-card v-if="!quizStarted">
            Participants
            <ul>
              <li v-for="player in players" :key="player.id">
                {{ player.name }} - {{ player.score }}
              </li>
            </ul>
          </b-card>
        </div>
      </b-col>

      <b-col cols="12" lg="2">
        <div v-if="quizStarted">
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

  export default {
    components: { Display },

    data() {
      return {
        quizStarted: true, //have to relay on better data for these variables
        quizEnded: false,
        time: 30,
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

      options() {
        return this.question.options;
      },

      correctAnswer() {
        return this.question.answer;
      },

      timesUp() {
        return !this.time > 0;
      },
    },

    created() {
      this.listenForPlayerJoining();

      this.listenForQuestionChange();

      this.listenForPlayerAnswer();

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
            this.quiz = { ...this.quiz, current_question: e.question };
            this.quizStarted = true;
            this.startTimer();
          }
        );
      },

      listenForQuizEnd() {
        window.Echo.channel("quizy" + this.quizId).listen("QuizEnded", (e) => {
          this.quizEnded = true;
          console.log(e);
        });
      },

      listenForPlayerAnswer() {},

      listenForPass() {},

      startTimer() {
        setTimeout(() => {
          if (this.time > 0) {
            --this.time;
            this.startTimer();
          }
        }, 1000);
      },
    },
  };
</script>

<style></style>
