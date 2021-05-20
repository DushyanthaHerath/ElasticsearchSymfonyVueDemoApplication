const cart = {
    namespaced: true,
    state: {
        cart: {
            grand_total: 0,
            items: []
        }
    },
    mutations: {
        setCart(state, cart) {
            state.cart = cart;
        }
    },
    actions: {
        addToCart({commit}, item) {
            var data = new FormData();
            data.set('book_id', item.id);
            data.set('qty', 1)
            this._vm.axios.post('/api/cart/add', data)
                .then((response) => {
                    commit('setCart', response.data.result);
                }, (error) => {
                    console.log(error);
                });
        },
        clearCart({commit}) {
            this._vm.axios.put('/api/cart/clear')
                .then((response) => {
                    commit('setCart', response.data.result);
                }, (error) => {
                    console.log(error);
                });
        },
        get({commit}) {
            this._vm.axios.get('/api/cart/')
                .then((response) => {
                    commit('setCart', response.data.result);
                }, (error) => {
                    console.log(error);
                });
        }
    },
    getters: {
        getCart(state) {
            return state.cart;
        }
    }
};

export default cart;
