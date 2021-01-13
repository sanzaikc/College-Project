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
    <div v-if="players">
      <div class="my-4" v-show="!start">
        <div>
          <h5>Participants:</h5>
          <h5
            v-for="(player, index) in players"
            :key="player.id"
            class="text-info"
          >
            {{ index + 1 + "." }} {{ player.name }}
          </h5>
        </div>
      </div>
      <button
        v-show="!start"
        class="btn btn-outline-primary btn-block w-25"
        style="position: fixed; bottom: 5%; left: 45%"
        @click="startGame"
      >
        Start Quiz
      </button>
    </div>
    <transition name="fade" mode="out-in">
      <div class="mt-5">
        <HostView
          v-if="start"
          :allQuestions="allQuestions"
          @onGameFinish="endQuiz"
        />
      </div>
    </transition>
  </div>
</template>

<script>
  import HostView from "@/components/hostQuiz/HostView";
  import { mapMutations, mapState } from "vuex";

  export default {
    components: {
      HostView,
    },

    data() {
      return {
        copied: false,
        start: false,
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

      startGame() {
        if (this.players.length > 0) {
          this.start = true;
        } else {
          alert("Not enough participants");
        }
      },

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
