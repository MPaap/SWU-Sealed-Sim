import './bootstrap';

import { createApp, ref, onMounted } from 'vue'

let pool = ref([]);
let leaders = ref([]);
let bases = ref([]);
let openCards = ref([]);
let selectedCards = ref([]);

createApp({
    setup() {
        return {
            pool,
            leaders,
            bases,
            openCards,
            selectedCards,
        }
    }
}).mount('#app')

axios.get('/pool/SEC').then((response) => {
    pool = response.data;

    pool.forEach((pack) => {
        pack.forEach((card) => {
            switch (true) {
                case card.type === 'Leader':
                    leaders.value.push(card)
                    break;
                case card.type === 'Base':
                    bases.value.push(card)
                    break;
                default:
                openCards.value.push(card)
            }
        });
    });
});
