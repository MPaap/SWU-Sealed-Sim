import './bootstrap';

import { createApp, ref, onMounted, reactive, computed } from 'vue'

let pool = ref([]);
const leaders = ref([]);
const bases = ref([]);
const allCards = reactive([]);
const openCards = reactive([]);
const selectedCards = reactive([]);
const show = reactive([
    'Common', 'Special', 'Uncommon', 'Rare', 'Legendary'
]);

createApp({
    setup() {
        return {
            pool,
            leaders,
            bases,
            allCards,
            openCards,
            selectedCards,
            show,
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
        },

        aspectLess() {
            return allCards.filter(card => card.aspects.length === 0).length;
        },

        heroism() {
            return allCards.filter(card =>
                card.aspects.length === 1 &&
                card.aspects[0].name === "Heroism"
            ).length;
        },

        villainy() {
            return allCards.filter(card =>
                card.aspects.length === 1 &&
                card.aspects[0].name === "Villainy"
            ).length;
        },

        getAspect(arr) {
            if (arr.length === 1) {
                return allCards.filter(card => {
                    const aspectNames = card.aspects.map(a => a.name);

                    return (
                        aspectNames.includes(arr[0]) &&
                        !aspectNames.includes("Heroism") &&
                        !aspectNames.includes("Villainy")
                    );
                }).length;
            }

            return allCards.filter(card =>
                arr.every(req =>
                    card.aspects.some(a => a.name === req)
                )
            ).length;
        },

        exportToJson() {
            let array = {
                metadata: {
                    name: "SWUSEALEDBUILDER",
                },
                leader: {
                    id: "SEC_018",
                    count: 1,
                },
                base: {
                    id: "SEC_023",
                    count: 1,
                },
                deck: [],
            };

            selectedCards.forEach((card) => {
                array.deck.push({
                    id: 'SEC_' + (card.version.number < 100 ? '0' : '') + card.version.number,
                    count: 1,
                })
            })

            navigator.clipboard.writeText(JSON.stringify(array)).then(function() {
                console.log('Async: Copying to clipboard was successful!');
            }, function(err) {
                console.error('Async: Could not copy text: ', err);
            });
        },

        toggleShow(string) {
            if (show.includes(string)) {
                const index = show.indexOf(string);
                if (index !== -1) {
                    show.splice(index, 1);
                }
            } else {
                show.push(string);
            }
        }
    },
    computed: {
        sortedOpenCards() {
            return openCards
                .filter((card) => {
                    return show.includes(card.version.rarity);
                })
                .sort((a, b) => {
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
