<template>
    <button class="btn btn-default"
            v-text="text"
            v-on:click="follow"
            v-bind:class="{'btn-success':followed}"
    >

    </button>
</template>

<script>
    export default {
        props:['question','user'],
        mounted() {
            this.$http.post('/api/question/follower',{'question':this.question,'user':this.user}).then(response =>{
                //onsole.log(response.data);
                this.followed = response.data.followed
            })
        },
        data(){
            return {
                followed:false
            }
        },
        computed:{
            text(){
                return this.followed ? '取消关注' : '关注该问题'
            }
        },
        methods:{
            follow(){
                this.$http.post('/api/question/follow',{'question':this.question,'user':this.user}).then(response =>{
                    //onsole.log(response.data);
                    this.followed = response.data.followed
                })
            }
        }
    }
</script>
