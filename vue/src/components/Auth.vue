<template>
    <div>
        <h1 v-show="errors !== ''">
            {{ errors }}
        </h1>
        <div class="form">
            <header>Login</header>
            <form @submit.prevent="postLogin()">
                <input type="text" placeholder="Username" v-model="login.username">
                <input type="password" placeholder="Password" v-model="login.password">
                <input type="submit" value="Login">
            </form>
        </div>
        <div class="form">
            <header>Register</header>
            <form @submit.prevent="postRegister()">
                <input type="text" placeholder="First Name" v-model="register.first_name">
                <input type="text" placeholder="Last Name" v-model="register.last_name">
                <input type="text" placeholder="Username" v-model="register.username">
                <input type="password" placeholder="Password" v-model="register.password">
                <input type="password" placeholder="Confirm Password" v-model="confirm">
                <input type="submit" value="Register">
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        mounted(){
            axios.defaults.baseURL = 'http://localhost:8000/v1/auth';

            if(localStorage.getItem('token')){
                this.$router.push('/home');
            }

        },

        data(){
            return{
                register:{
                    username:'',
                    first_name:'',
                    last_name:'',
                    password:'',
                },
                login:{
                    username:'',
                    password:''
                },

                confirm:'',

                errors: ''
            }
        },

        methods:{
            postRegister(){
                if(this.register.password == this.confirm){
                    axios.post('/register', this.register)
                        .then(res => {
                            localStorage.setItem('token', res.data.token);
                            this.$router.push('/home');
                        })
                        .catch(err => {
                            this.errors = err.response.data.message;
                            console.log(this.errors);
                        });
                }
                else{
                    alert('Wrong Password Confirmation');
                }
            },

            postLogin(){
                axios.post('/login', this.login)
                    .then(res => {
                        localStorage.setItem('token', res.data.token);
                        this.$router.push('/home');
                    })
                    .catch(err => {
                        this.errors = err.response.data.message;
                        console.log(this.errors);
                    });
            }
        }
    }
</script>

<style scoped>

</style>