<template>
  <div>
    <div class="leaderboard-card">
      <div class="leaderboard-heading">
        Leaderboard
      </div>

      <transition-group class="scores" name="flip-list" tag="ul">
        <li
          name="flip-list"
          v-for="(score, index) in sortedArray"
          tag="li"
          :key="score.id"
          class="player"
          :class="turnOf === score.id ? 'playerTurn' : ''"
        >
          <div class="status">
            <div>
              {{ score.name }}
            </div>
            <div
              v-if="index < 3 && score.score > 0"
              class="badge"
              :style="{ background: `${playerRank(index).color}` }"
            >
              {{ playerRank(index).rank }}
            </div>
          </div>
          <div class="score">
            {{ score.score }}
          </div>
        </li>
      </transition-group>
    </div>
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

      leading() {
        return this.sortedArray[0];
      },
    },

    methods: {
      playerRank(index) {
        let ranks = [
          {
            index: 0,
            rank: "1st",
            color: "green",
          },
          {
            index: 1,
            rank: "2nd",
            color: "skyblue",
          },
          {
            index: 2,
            rank: "3rd",
            color: "orange",
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

  .current {
    border-left: 2rem solid steelblue;
  }

  .leaderboard-card {
    background: white;
    border-radius: 0.25rem;

    box-shadow: 0 2px 6px rgb(224, 210, 210);
  }

  .scores {
    list-style-type: none;
    padding: 0;
    font-size: 1.1rem;
  }

  .score {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .player {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    border-left: 5px solid white;
  }

  .status {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-grow: 1;
    font-weight: 600;
    padding: 0 0.5rem;
  }

  .badge {
    color: white;
    font-size: 0.75rem;
    border-radius: 2rem;
    padding: 0.35rem 0.5rem;
    width: 40px;
    margin-right: 1rem;
  }

  .score {
    font-size: 1.25rem;
    font-weight: 500;
    width: 30px;
  }

  .playerTurn {
    border-left: 5px solid steelblue;
  }

  .leaderboard-heading {
    padding: 0.5rem 1.25rem;
    border-bottom: 1px solid lightgray;
    font-size: 1.2rem;
    font-weight: 600;
  }
</style>
