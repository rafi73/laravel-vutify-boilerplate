<template>
    <div>
        <div>
            <!-- <vue-topprogress ref="topProgress"></vue-topprogress> -->
        </div>
        
        <title>Vutify</title>
        <div v-if="isAdmin">
            <admin-component></admin-component>
        </div>
        <div v-else-if="isLogin">
            <login-component></login-component>
        </div>
    </div>
</template>


<script>
    import admin_component from './AdminComponent.vue'
    import login_component from './LoginComponent.vue'

    export default {
        components: {
            admin_component, login_component
        },
        data: function () {
            return {
                timer: null
            }
        },
        computed: {
            authenticated: function () {
                return this.$store.getters.isAuthenticated
            },
            isAdmin: function () {
                return this.$route.meta.requiresAuth === true
            },
            meta: function () {
                return this.$store.state.pageMetaStore
            },
            isLogin: function () {
                return this.$route.meta.requiresAuth === false
            },
            isHome: function () {
                return this.$route.meta === 'home'
            }
            
        },
        
        
        created() {
        },
        methods: {
            refreshToken(){
                NProgress.start()
                axios.get(`/api/auth/refresh`,{
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                })
                .then(response => {
                    console.log(response)
                    const token = response.data.access_token
                    localStorage.setItem('token', token)
                    NProgress.done()
                })
                .catch(error => {
                    if (error.response) {
                        console.log(error.response)
                        NProgress.done()
                        ErrorHandler.handle(error.response.status, this)
                    }
                })
            },
            logout(){
                this.$swal({
                    title: "警告!",
                    text: 'セッションの有効時間が切れました。作業を続けるにはもう一度ログインしてください。',
                    type: "warning",
                    confirmButtonText: "OK"
                })

                axios.get(`/api/auth/logout`,{
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                })
                .then(response => {
                    console.log(response)
                    NProgress.done()
                    localStorage.removeItem("token")
                    localStorage.removeItem("login")
                    window.location.href = '/login'
                    this.$store.commit('changeUser', null)
                    window.location.href = '/login'
                })
                .catch(error => {
                    if (error.response) {
                        console.log(error.response)
                    }
                })
            },
            fire: function() {
                console.log('Timer End')
                clearInterval(this.timer)
                this.logout()
            },
        }
    }
</script>