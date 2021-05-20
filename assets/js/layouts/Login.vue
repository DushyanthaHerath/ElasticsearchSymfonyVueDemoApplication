<template>
    <v-layout align-center="align-center" justify-center="justify-center">
        <v-flex class="login-form text-xs-center">
            <div class="display-1 mb-3 d-flex align-center">
                Elasticsearch Demo Application
            </div>
            <v-card light="light">
                <v-card-text>
                    <div class="subheading">
                        <template v-if="options.isLoggingIn">Log in to your customer portal</template>
                    </div>
                    <v-form>
                        <v-text-field v-if="!options.isLoggingIn" v-model="user.name" light="light" prepend-icon="person" label="Name"></v-text-field>
                        <v-text-field v-model="user.email" light="light" prepend-icon="email" label="Email" type="email"></v-text-field>
                        <v-text-field v-model="user.password" light="light" prepend-icon="lock" label="Password" type="password"></v-text-field>
                        <v-checkbox v-if="options.isLoggingIn" v-model="options.shouldStayLoggedIn" light="light" label="Stay logged in?" hide-details="hide-details"></v-checkbox>
                        <v-btn v-if="options.isLoggingIn" @click.prevent="submit" block="block" type="submit">Login</v-btn>
                    </v-form>
                </v-card-text>
                <p v-if="showError" id="error">Username or Password is incorrect</p>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import { mapActions } from "vuex";
    export default {
        name: "Login",
        components: {},
        data() {
            return {
                user: {
                    // email: 'admin@example.com',
                    // password: 'admin',
                    // name: 'John Doe',
                },
                showError: false,
                options: {
                    isLoggingIn: true,
                    shouldStayLoggedIn: true,
                },
            };
        },
        methods: {
            ...mapActions(["LogIn"]),
            async submit() {
                const User = new FormData();
                User.append("username", this.user.email);
                User.append("password", this.user.password);
                User.append('client_id', process.env.CLIENT_ID); // TODO:: Move these stuff to proxy in backend
                User.append('client_secret', process.env.CLIENT_SECRET);
                User.append('grant_type', 'password');
                try {
                    await this.LogIn(User);
                    this.$router.push("/home");
                    this.showError = false
                } catch (error) {
                    this.showError = true
                }
            },
        },
    };
</script>

<style lang="scss" scoped>
    .login-form {
        min-width: 500px;
    }
</style>