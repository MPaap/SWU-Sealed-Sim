import './bootstrap';

import { createApp, ref, onMounted, reactive, computed } from 'vue'

let pool = ref([]);
const leaders = ref([]);
const bases = ref([]);
const allCards = reactive([]);
const openCards = reactive([]);
const selectedCards = reactive([]);

createApp({
    setup() {
        return {
            pool,
            leaders,
            bases,
            allCards,
            openCards,
            selectedCards,
        }
    },
    methods: {
        moveToSelected(key) {
            selectedCards.push(openCards[key])

            openCards.splice(key, 1);
        },

        moveToOpen(key) {
            openCards.push(selectedCards[key])

            selectedCards.splice(key, 1);
        }
    },
    computed: {
        sortedOpenCards() {
            return openCards.sort((a, b) => {
                return a.version.number - b.version.number;
            });
        },

        sortedSelectedCards() {
            return selectedCards.sort((a, b) => {
                return a.cost - b.cost;
            });
        },

        commons() {
            return allCards.filter(card => card.version.rarity === 'Common').length;
        },

        uncommons() {
            return allCards.filter(card => card.version.rarity === 'Uncommon').length;
        },

        specials() {
            return allCards.filter(card => card.version.rarity === 'Special').length;
        },

        rares() {
            return allCards.filter(card => card.version.rarity === 'Rare').length;
        },

        legendaries() {
            return allCards.filter(card => card.version.rarity === 'Legendary').length;
        },
    },
    watch: {

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
                openCards.push(card)
                allCards.push(card)
            }
        });
    });
});
