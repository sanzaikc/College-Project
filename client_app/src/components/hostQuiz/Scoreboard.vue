<template>
  <div>
    <b-card no-body header="Scoreboard" class="w-100">
      <transition-group tag="b-list-group" name="flip-list" flush>
        <b-list-group-item
          v-for="(player, index) in sortedArray"
          :key="player.id"
          class="d-flex justify-content-between align-items-center"
        >
          <p class="m-0">
            <b-badge
              v-if="index < 3"
              :variant="playerRank(index).color"
              class="py-1 mr-2"
              pill
            >
              {{ playerRank(index).rank }}
            </b-badge>
            {{ player.name }}
          </p>
          <b>{{ player.score }}</b>
        </b-list-group-item>
      </transition-group>
    </b-card>
  </div>
</template>

<script>
  import { mapState } from "vuex";
  export default {
    props: {
      quizId: { type: Number },
    },

    data() {
      return {};
    },

    computed: {
      ...mapState({
        players: (state) => state.quiz.players,
      }),

      showRanking() {
        return this.players.filter((p) => p.score > 0);
      },

      sortedArray: function() {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        return this.players.sort((a, b) => (a.score > b.score ? -1 : 1));
      },
    },

    mounted() {
      this.listenForScoreChange();
    },

    methods: {
      listenForScoreChange() {
        window.Echo.channel("quizy" + this.quizId).listen(
          "ScoreChanged",
          (e) => {
            let { scores } = e;
            scores.forEach(({ player_id, score }) => {
              this.$store.commit("UPDATE_SCORE", { player_id, score });
            });
          }
        );
      },

      playerRank(index) {
        let ranks = [
          {
            index: 0,
            rank: "1st",
            color: "success",
          },
          {
            index: 1,
            rank: "2nd",
            color: "info",
          },
          {
            index: 2,
            rank: "3rd",
            color: "warning",
          },
        ];

        return ranks[index];
      },
    },
  };
</script>

<style>
  .flip-list-move {
    transition: transform 1s;
  }
</style>
