<template>
    <v-navigation-drawer
            v-model="drawer"
            app
            clipped
            color="grey lighten-4"
    >
        <v-list
                dense
                class="grey lighten-4"
        >
            <v-list-item @click="redirectHome">
                <v-list-item-action>
                    <v-icon>shopping_cart</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="grey--text">
                        Orders
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item @click="redirectTopSelling">
                <v-list-item-action>
                    <v-icon>trending_up</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="grey--text">
                        Top Selling
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item @click="redirectBoughtTogether">
                <v-list-item-action>
                    <v-icon>content_copy</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="grey--text">
                        Bought Together
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item @click="redirectTopSelling">
                <v-list-item-action>
                    <v-icon>lightbulb_outline</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="grey--text">
                        Account
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item>
                <v-list-item-action>
                    <v-icon>touch_app</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="grey--text">
                        Configure
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-divider inset></v-divider>
            <v-list-item @click="logout">
                <v-list-item-action>
                    <v-icon>logout</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="grey--text">
                        Logout
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import { mapState, mapActions, mapGetters } from 'vuex'
    export default {
        name: "Navigation",
        props: {
            source: String,
        },
        data: () => ({
            drawer: null,
        }),
        methods: {
            ...mapActions(['LogOut']),
            redirectHome() {
                this.$router.push({path: '/home'});
            },
            redirectTopSelling() {
                this.$router.push({path: '/top_selling'});
            },
            redirectBoughtTogether() {
                this.$router.push({path: '/bought_together'});
            },
            logout() {
                this.LogOut();
                this.$router.push({path: '/login'});
            }
        },
        filters: {
            price: function (value) {
                if(value)
                    return '$'+value.toFixed(2);
                return 0;
            }
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>
