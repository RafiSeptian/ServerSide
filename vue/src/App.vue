<template>
  <div id="app">
    <div class="header">
      <div>
        <a href="">Home</a>
      </div>
      <div class="right">
        <a href="" @click.prevent="logout()">Logout</a>
      </div>
    </div>
    <router-view></router-view>
  </div>
</template>

<script>

export default {
    methods(){

    },

    data(){
        return{

        }
    },

    methods:{
        logout(){
            axios.get('/auth/logout?token=' + token)
                .then(res => {
                    this.$router.push('/');
                    localStorage.removeItem('token');
                })
                .catch(err => {
                    console.log(err);
                })
        }
    }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>
