<template>
    <div>
        <div class="container">
            <div class="board-container">
                <div class="board-wrapper" v-for="board in boards" :key="board.id">
                    <div class="board">
                        <span v-show="id !== board.id" @click.prevent="editBoard(board)">
                            {{ board.name }}
                        </span>
                        <input type="text" v-show="id == board.id" value="test" @keypress.enter="multiAction(board.id)" placeholder="Are you sure want to delete?" autofocus v-model="editName">
                    </div>
                </div>
                <div class="board-wrapper"><div class="board new-board">
                    <span v-show="!create" @click="createBoard()">
                        Create new board...
                    </span>
                    <input type="text" placeholder="New Board Name" autofocus v-model="name" v-show="create" @keypress.enter="postBoard()">
                </div></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted(){
            axios.defaults.baseURL = 'http://localhost:8000/v1';

            if(!localStorage.getItem('token')){
                this.$router.push('/');
            }

            Fire.$on('refresh', () => {
                this.getBoards();
            });

            this.getBoards();
        },

        data(){
            return{
                boards:[],
                name:'',
                id:'',
                create: false,
                editName:'',
            }
        },

        methods:{
            getBoards(){
                axios.get('/board?token=' + token)
                    .then(res => {
                        this.boards = res.data;
                        console.log(res.data);
                    })
                    .catch(err => {
                        console.log(err.response.data.messages);
                    })
            },

            createBoard(){
                this.create = true;
            },

            postBoard(){
                axios.post('/board?token=' + token , {
                    name:this.name
                })
                    .then(res => {
                        Fire.$emit('refresh');
                        window.alert(res.data.message);
                        this.name = '';
                    })
                    .catch(err => {
                        window.alert(err.response.data.message);
                    })
            },

            editBoard(board){
                this.editName = board.name;
                this.id = board.id;
            },

            multiAction(id){
                if(this.editName !== ''){
                    axios.put(`/board/${id}?token=` + token, {
                        name: this.editName
                    })
                        .then(res => {
                            window.alert(res.data.message);
                            this.editName = '';
                            this.id = '';
                            Fire.$on('refresh');
                        })
                        .catch(err => {
                            window.alert(err.response.data.message);
                        });
                }
                else{
                    axios.delete(`/board/${id}?token=` + token)
                        .then(res => {
                            window.alert(res.data.message);
                            Fire.$emit('refresh');
                        })
                        .catch(err => {
                            window.alert(err.data.message);
                        });
                }
            }
        }
    }
</script>

<style scoped>
    .container{
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        justify-content: center;
    }

    .board-container{
        flex: 1 1 100%;
        margin: 20px;
        display: flex;
        flex-flow: row wrap;
        justify-content: space-around;
        align-items: flex-start;
    }

    .board-container::after {
        content: "";
        flex: auto;
    }

    .board-wrapper{
        display:flex;
        flex: 0 0 25%;
        box-sizing: border-box;
        padding: 0.5em;
        cursor: pointer;
    }

    .board{
        flex: 1;
        padding: 1em;
        background: #248ea9;
        color: #ffe;
        font-weight: bold;
    }

    .new-board{
        background: #fafdcb;
        color: #248ea9;
    }

    .board input{
        width: 100%;
    }
</style>