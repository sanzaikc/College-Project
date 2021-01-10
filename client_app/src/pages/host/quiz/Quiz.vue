<template>
  <div>
    <transition name="slide-fade" mode="out-in">
        <router-view></router-view>
    </transition>
  </div>
</template>

<script>
export default {
  data(){
    return{
        id: this.$route.params.id,
    }
  },
  watch: {
      '$route'(to){
          this.id = to.params.id;
      }
  },  
}
</script>