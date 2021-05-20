<template>
    <v-col cols="12">
    <v-row>
            <v-row
                >
                <v-col
                        cols="3"
                        sm="4"
                >
                    <v-menu
                            ref="menu"
                            v-model="menu"
                            :close-on-content-click="false"
                            :return-value.sync="startDate"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                    v-model="startDate"
                                    label="Start Date"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                                v-model="startDate"
                                no-title
                                scrollable
                        >
                            <v-spacer></v-spacer>
                            <v-btn
                                    text
                                    color="primary"
                                    @click="menu = false"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.menu.save(startDate)"
                            >
                                OK
                            </v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-col>
                <v-col
                        cols="3"
                        sm="4"
                >
                    <v-menu
                            ref="menu2"
                            v-model="menu2"
                            :close-on-content-click="false"
                            :return-value.sync="endDate"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                    v-model="endDate"
                                    label="End Date"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                                v-model="endDate"
                                no-title
                                scrollable
                        >
                            <v-spacer></v-spacer>
                            <v-btn
                                    text
                                    color="primary"
                                    @click="menu2 = false"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.menu2.save(endDate)"
                            >
                                OK
                            </v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-col>
                <v-col cols="6">

                <v-card
                        class="mt-6 mx-auto"
                >
                    <v-card-title>
                        Top Selling SKU's
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <template v-for="item in dataset">
                                <v-list-item
                                        :key="item.key"
                                >
                                    <v-list-item-title v-text="item.key"></v-list-item-title>
                                    <v-list-item-avatar>
                                        <v-chip
                                                color="red"
                                                text-color="white"
                                        >
                                            {{item.doc_count}}
                                        </v-chip>
                                    </v-list-item-avatar>
                                </v-list-item>
                            </template>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
            </v-row>
        </v-row>
    </v-col>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";

    export default {
        name: "Home",
        computed: {
            ...mapGetters(['isAuthenticated']),
        },
        data() {
          return {
              startDate: '',
              endDate: '',
              menu: false,
              modal: false,
              menu2: false,
              dataset:[],
              options: {}
          }
        },
        methods: {
            loadData() {
                axios.get('/api/order/top_selling?start_date='+this.startDate+'&end_date='+this.endDate).then(response => {
                    this.dataset = response.data.result;
                });
            },
        },
        watch: {
            startDate: {
                handler () {
                    this.loadData()
                },
            },
            endDate: {
                handler () {
                    this.loadData()
                },
            },
        },
        mounted() {
            console.log('isAuthenticated', this.isAuthenticated);
            if(!this.isAuthenticated){
                this.$router.push({ name: 'login'});
            }
            this.loadData();
        },
    }
</script>

<style scoped>

</style>
