<template>
    <div>
        <h2>{{ getGreetings }}</h2>
    </div>
</template>

<script>
import { mapState } from 'vuex';
export default {
    computed:{
        ...mapState({
            'userName': state => state.auth.currentUser.name
        }),
        getGreetings(){
            var greeting = ""
            var d =new Date();
            let time = d.getHours();

            if (time < 12) greeting = "Good Morning";
            else if (time < 18) greeting = "Good Afternoon";
            else greeting = "Good Evening"

            return greeting + ", " +this.userName;
        }
    }
}
</script>

<style>

</style>
