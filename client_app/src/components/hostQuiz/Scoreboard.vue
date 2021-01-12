<template>
  <b-card no-body header="Scoreboard">
    <b-list-group flush>
      <b-list-group-item
        v-for="(player, index) in players"
        :key="index"
        class="d-flex justify-content-between align-items-center"
      >
        <p class="m-0">
          <b-badge variant="success" class="py-1 mr-2" pill>
            {{ playerPosition(player) }}
          </b-badge>
          {{ player.name }}
        </p>
        30
      </b-list-group-item>
    </b-list-group>
  </b-card>
</template>

<script>
  export default {
    props: {
      quizId: { type: Number },
      players: { type: Array },
    },

    created() {
      this.listenForScoreChange();
    },

    data() {
      return {};
    },

    computed: {},

    methods: {
      listenForScoreChange() {
        window.Echo.channel("quizy" + this.quizId).listen(
          "ScoreChanged",
          (e) => {
            console.log(e);
          }
        );
      },

      playerPosition(player) {
        console.log(player);
      },
    },
  };
</script>

<style></style>
