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
            </v-row>


            <v-col cols="12">

                <v-card
                        class="mt-12 mx-auto"
                >
                    <v-card-title>
                        Orders
                        <v-spacer></v-spacer>
                        <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                                @keyup="loadData"
                        ></v-text-field>
                    </v-card-title>
                    <v-data-table
                            :headers="headers"
                            :items="dataset"
                            @update:page="updatePage"
                            :options.sync="options"
                            :server-items-length="pageCount"
                            :loading="false"
                            class="elevation-1"

                            :single-expand="singleExpand"
                            :expanded.sync="expanded"
                            show-expand
                    >
                        <template v-slot:top>
                            <v-toolbar flat>
                                <v-spacer></v-spacer>
                                <v-switch
                                        v-model="singleExpand"
                                        label="Single expand"
                                        class="mt-2"
                                ></v-switch>
                            </v-toolbar>
                        </template>
                        <template v-slot:item.country="{ item }">
                            <span>
                                {{ item.country }}
                            </span>
                        </template>
                        <template v-slot:item.city="{ item }">
                            <span>
                                {{ item.city || '-' }}
                            </span>
                        </template>
                        <template v-slot:item.sku="{ item }">
                            <v-chip
                                    :key="sku"
                                    v-for="sku in item.sku.slice(0, 2)"
                                    outlined
                                    pill
                                    :small="true"
                            >
                                <v-icon left :small="true">
                                    mdi-server-plus
                                </v-icon>
                                {{ sku }}
                            </v-chip>
                        </template>
                        <template v-slot:item.category="{ item }">
                            <span>{{ item.category }}</span>
                        </template>
                        <template v-slot:item.order_date="{ item }">
                            <span>
                                {{ formatDate(item.order_date) }}
                            </span>
                        </template>
                        <template v-slot:expanded-item="{ headers, item }">
                            <td :colspan="headers.length">
                                <v-chip
                                        :key="sku"
                                        v-for="sku in item.sku"
                                        outlined
                                        pill
                                        :small="true"
                                >
                                    <v-icon left :small="true">
                                        mdi-server-plus
                                    </v-icon>
                                    {{ sku }}
                                </v-chip>
                            </td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
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
              headers: [
                  {
                      text: 'Country',
                      align: 'start',
                      sortable: false,
                      value: 'country',
                  },
                  {
                      text: 'City',
                      align: 'start',
                      sortable: false,
                      value: 'city',
                  },
                  {
                      text: 'Date',
                      align: 'start',
                      sortable: false,
                      value: 'order_date',
                  },
                  {
                      text: 'Amount',
                      align: 'start',
                      sortable: false,
                      value: 'total_amount',
                  },
                  {
                      text: 'SKU',
                      align: 'start',
                      sortable: false,
                      value: 'sku',
                  },
                  {
                      text: 'Type',
                      align: 'start',
                      sortable: false,
                      value: 'type',
                  },
                  {
                      text: 'Category',
                      align: 'start',
                      sortable: false,
                      value: 'category',
                  },
                  {
                      text: 'Customer',
                      align: 'start',
                      sortable: false,
                      value: 'customer',
                  }
              ],
              page: 1,
              pageCount: 1,
              itemsPerPage: 15,
              dataset: [],
              search: '',
              total: 0,
              options: {},
              expanded: [],
              singleExpand: false,
          }
        },
        methods: {
            updatePage(page) {
                this.page = page;
            },
            listFields(){
                axios.get('/api/order/field').then(response => {

                });
            },
            loadData() {
                let self = this;
                axios.get('/api/order/bought_together?search='+this.search+'&start_date='+this.startDate+'&end_date='+this.endDate+'&page='+this.page+'&per_page='+this.itemsPerPage).then(response => {
                    self.dataset = response.data.result.dataset;
                    self.pageCount = Math.ceil(response.data.result.total/self.itemsPerPage);
                    console.log(this.pageCount);
                    self.page = parseInt(response.data.result.page);
                    self.total = response.data.result.total;
                });
            },
            countryName(code) {
                try {
                    let region = new Intl.DisplayNames(['en'], {type: 'region'});
                    return region.of(code);
                } catch (e) {
                    return code;
                }
            },
            formatDate(date) {
                return moment(date).format('lll');
            }
        },
        watch: {
            options: {
                handler (values) {
                    this.page = values.page;
                    this.loadData()
                },
                deep: true,
            },
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
            search: {
                handler () {
                    this.loadData()
                },
            }
        },
        mounted() {
            console.log('isAuthenticated', this.isAuthenticated);
            if(!this.isAuthenticated){
                this.$router.push({ name: 'login'});
            }
            this.listFields();
            this.loadData();
        },
    }
</script>

<style scoped>

</style>
