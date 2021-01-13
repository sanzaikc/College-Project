/* eslint-disable vue/no-side-effects-in-computed-properties */
<template>
  <b-card no-body header="Scoreboard">
    <b-list-group flush>
      <b-list-group-item
        v-for="(player, index) in players"
        :key="index"
        class="d-flex justify-content-between align-items-center"
      >
        <p class="m-0">
          <b-badge v-if="showRanking" variant="success" class="py-1 mr-2" pill>
            1st
          </b-badge>
          {{ player.name }}
        </p>
        {{ player.score }}
      </b-list-group-item>
    </b-list-group>
  </b-card>
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
        function compare(a, b) {
          if (a.score < b.score) return -1;
          if (a.score > b.score) return 1;
          return 0;
        }

        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        return this.players.sort(compare);
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
    },
  };
</script>

<style></style>
