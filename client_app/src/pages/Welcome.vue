<template>
  <div class="row h-100 d-flex align-items-center">
    <div class="col-md-6 bg-white h-100 d-flex align-items-center">
      <div class="ml-4">
        <p class="display-4">Welcome to Quizy,</p>
        <h3 class="text-secondary">
          Are you bored sitting in your house thinking about when the pandemic
          is over?
        </h3>
        <h3 class="text-secondary">
          Well, get ready to play <span>#exciting</span>,
          <span>#immersive</span> and <span>#fun quiz</span> .
        </h3>
      </div>
    </div>
    <div class="col-md-6">
      <div class=" col-md-8 mx-auto p-4">
        <h3>Join a Quiz</h3>
        <b-form @submit.prevent="join" class="bg-white p-4 rounded-lg shadow">
          <b-form-group id="input-group-2" label="Name" label-for="name">
            <b-form-input
              id="name"
              type="text"
              v-model="data.name"
              required
              placeholder="Enter name"
            >
            </b-form-input>
          </b-form-group>
          <b-form-group id="input-group-2" label="Pin" label-for="pin">
            <b-form-input
              id="pin"
              type="text"
              v-model="data.pin"
              required
              placeholder="Enter pin"
            >
            </b-form-input>
          </b-form-group>
          <b-button type="submit" variant="outline-primary w-100">
            {{ isBusy ? "Joining" : "Join Quiz" }}
            <b-spinner v-if="isBusy" small type="grow" class="ml-2">
            </b-spinner>
          </b-button>
          <hr />
          <router-link
            :to="{ name: 'login' }"
            class="btn btn-outline-info w-100"
            >Sign in to Host Quiz
          </router-link>
        </b-form>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        isBusy: false,
        data: {
          name: "",
          pin: "",
        },
      };
    },

    mounted() {
      document.title = "Welcome to Quizy";
    },

    methods: {
      join() {
        this.isBusy = true;
        this.$store
          .dispatch("joinQuiz", this.data)
          .then((res) => {
            if (res) {
              if (res.message) {
                this.$toasted.show(res.message, {
                  theme: "bubble",
                });
                this.data.pin = "";
                this.isBusy = false;
              } else {
                let {
                  player: { id: playerId, quiz_id: quizId },
                  player,
                } = res;

                this.$router.push({
                  name: "play",
                  params: { quizId, playerId, player },
                });
                this.isBusy = false;
              }
            }
          })
          .catch((error) => {
            this.isBusy = false;
            this.$toasted.show(error, {
              theme: "bubble",
            }),
              (this.data = {
                name: "",
                pin: "",
              });
          });
      },
    },
  };
</script>

<style scoped>
  span {
    color: steelblue;
  }
</style>
