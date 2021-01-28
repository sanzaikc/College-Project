<template>
  <div>
    <div class="d-flex justify-content-between align-items-center">
      <h3 v-text="quizDetail.name"></h3>
      <div class="d-flex">
        <div class="d-flex align-items-baseline">
          <h4>Pin:</h4>
          <h2 class="mx-3" style="color: steelblue" ref="pin">
            {{ quizDetail.pin }}
          </h2>
        </div>
        <div
          class="px-2 bg-light rounded-lg d-flex align-items-center"
          role="button"
          @click="copyPin"
        >
          <span
            v-text="copied ? 'Pin Copied' : 'Copy Pin'"
            :class="copied ? 'text-success' : ''"
          ></span>
          <b-icon
            :icon="copied ? 'clipboard-data' : 'clipboard'"
            :variant="copied ? 'success' : ''"
            scale="1.5"
            class="mx-2"
          ></b-icon>
        </div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-lg-3">
        <Scoreboard v-if="gameStarted" :quizId="quizDetail.id" />
        <div v-else>
          <h5>Participants</h5>
          <div v-if="players.length > 0">
            <h5
              v-for="(player, index) in players"
              :key="player.id"
              class="text-info"
            >
              {{ index + 1 + "." }} {{ player.name }}
            </h5>
          </div>
          <div v-else>
            <p>Waiting for players!</p>
          </div>
        </div>
      </div>
      <div class="col-lg-9">
        <HostView
          :turnList="turnList"
          :allQuestions="allQuestions"
          @onGameStart="gameStarted = true"
          @onGameFinish="endQuiz"
        />
      </div>
    </div>
  </div>
</template>

<script>
  import HostView from "@/components/hostQuiz/HostView";
  import Scoreboard from "@/components/Scoreboard";

  import { mapMutations, mapState } from "vuex";

  export default {
    components: {
      HostView,
      Scoreboard,
    },

    data() {
      return {
        copied: false,
        gameStarted: false,
        turnList: [],
        // start: false,
        // selectedQuestion: null,
      };
    },

    computed: {
      ...mapState({
        quizDetail: (state) => state.quiz.quizDetail,
        players: (state) => state.quiz.players,
      }),

      allQuestions() {
        return this.quizDetail.questions;
      },
    },

    created() {
      this.listenForPlayerJoining();
    },

    mounted() {
      this.turnList = this.players.map((p) => p.id);
      this.QUIZ_DETAIL(this.$route.params.id);
    },

    methods: {
      ...mapMutations(["QUIZ_DETAIL", "ADD_PLAYERS"]),

      listenForPlayerJoining() {
        window.Echo.channel("quizy" + this.$route.params.id).listen(
          "PlayerJoined",
          (e) => {
            e.player.score = 0;
            this.ADD_PLAYERS(e.player);
            this.$toasted.show(e.player.name + " joined!");
            this.turnList.push(e.player.id);
          }
        );
      },

      selectText(element) {
        var range;
        if (document.selection) {
          range = document.body.createTextRange();
          range.moveToElementText(element);
          range.select();
        } else if (window.getSelection) {
          range = document.createRange();
          range.selectNode(element);
          window.getSelection().removeAllRanges();
          window.getSelection().addRange(range);
        }
      },

      copyPin() {
        this.selectText(this.$refs.pin);
        try {
          if (document.execCommand("copy")) {
            this.copied = true;
            this.$toasted.show("Pin Copied");
          }
        } catch (err) {
          alert("Oops, unable to copy");
        }
        window.getSelection().removeAllRanges();
      },

      // startGame() {
      //   if (this.players.length > 0) {
      //     this.start = true;
      //   } else {
      //     alert("Not enough participants");
      //   }
      // },

      async endQuiz() {
        if (confirm("End this Quiz? ")) {
          try {
            let res = await this.$store.dispatch("endQuiz", this.quizDetail.id);
            if (res) {
              this.$toasted.show("Quiz terminated", {
                theme: "bubble",
              });
              this.$router.push({ name: "host.quiz" });
            }
          } catch (error) {
            console.log(error);
          }
        }
      },
    },
  };
</script>
