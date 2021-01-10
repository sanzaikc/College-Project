<template>
    <div>
        <h1 class="count">{{ animatedNumber }}</h1 >
    </div>
</template>

<script>
import gsap from 'gsap';
export default {
    mounted(){
        this.number = this.num;
    },
    props:{
        num: { type: Number }
    },
    data(){
        return{
            number: 0,
            tweenedNumber: 0
        }
    },
    computed: {
        animatedNumber: function() {
            return this.tweenedNumber.toFixed(0);
        }
    },
    watch: {
        number: function(newValue) {
            gsap.to(this.$data, { duration: 1, tweenedNumber: newValue });
        }
    }
}
</script>

<style scoped>
    .count{
        font-size : 10rem;
        color: lightsteelblue;
    }
</style>