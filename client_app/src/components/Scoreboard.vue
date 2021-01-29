<template>
  <div>
    <b-card no-body header="Scoreboard" class="w-100">
      <transition-group class="list-group list-group-flush" name="flip-list">
        <b-list-group-item
          :active="turnOf === score.id"
          v-for="(score, index) in sortedArray"
          :key="score.id"
          class="d-flex justify-content-between align-items-center"
        >
          <span class="m-0">
            <b-badge
              v-if="index < 3 && score.score > 0"
              :variant="playerRank(index).color"
              class="py-1 mr-2"
              pill
            >
              {{ playerRank(index).rank }}
            </b-badge>
            {{ score.name }}
          </span>
          <b>{{ score.score }}</b>
        </b-list-group-item>
      </transition-group>
    </b-card>
  </div>
</template>

<script>
  export default {
    props: {
      scores: { type: Array },
      turnOf: { type: Number },
    },

    computed: {
      showRanking() {
        return this.scores.filter((p) => p.score > 0);
      },
      sortedArray: function() {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        return this.scores.sort((a, b) => (a.score > b.score ? -1 : 1));
      },
    },

    methods: {
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
