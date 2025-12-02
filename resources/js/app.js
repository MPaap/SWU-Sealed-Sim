import './bootstrap';

import { createApp, ref, onMounted, reactive, computed } from 'vue'

let pool = ref([]);
const leaders = ref([]);
const bases = ref([]);
const allCards = reactive([]);
const openCards = reactive([]);
const selectedCards = reactive([]);
const show = reactive({
    rarity: ['Common', 'Special', 'Uncommon', 'Rare', 'Legendary'],
    aspect: ['Villainy', 'Heroism']
});
let selectedLeader = ref();
let selectedBase = ref('SEC_019');

createApp({
    setup() {
        return {
            selectedLeader,
            selectedBase,
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
        selectLeader(number) {
            selectedLeader.value = number;
        },

        moveToSelected(uuid) {
            let key = openCards.findIndex(card => card.tmp_id === uuid);

            selectedCards.push(openCards[key])

            openCards.splice(key, 1);
        },

        moveToOpen(uuid) {
            let key = selectedCards.findIndex(card => card.tmp_id === uuid);

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
                    id: "SEC_" + this.padNumber(selectedLeader.value),
                    count: 1,
                },
                base: {
                    id: selectedBase.value,
                    count: 1,
                },
                deck: [],
            };

            selectedCards.forEach((card) => {
                let id = 'SEC_' + this.padNumber(card.version.number);

                let key = array.deck.findIndex(item => item.id === id);

                if (key > 0) {
                    array.deck[key].count ++;
                } else {
                    array.deck.push({
                        id: id,
                        count: 1,
                    })
                }
            })

            navigator.clipboard.writeText(JSON.stringify(array)).then(function() {
                console.log('Async: Copying to clipboard was successful!');
            }, function(err) {
                console.error('Async: Could not copy text: ', err);
            });
        },

        toggleShow(string, key) {
            if (show[key].includes(string)) {
                const index = show[key].indexOf(string);
                if (index !== -1) {
                    show[key].splice(index, 1);
                }
            } else {
                show[key].push(string);
            }
        },

        padNumber(n) {
            if (n < 10) return "00" + n;
            if (n < 100) return "0" + n;
            return String(n);
        }
    },
    computed: {
        sortedOpenCards() {
            return openCards
                .filter((card) => {
                    return show.rarity.includes(card.version.rarity);
                })
                .filter((card) => {
                    if (show.aspect.length === 2) {
                        return true;
                    }

                    const hasFilter = card.aspects.some(aspect => aspect.name === show.aspect[0]);
                    const hasHeroism = card.aspects.some(aspect => aspect.name === "Heroism");
                    const hasVillainy = card.aspects.some(aspect => aspect.name === "Villainy");

                    if (show.aspect.length === 1) {
                        return hasFilter || (!hasHeroism && !hasVillainy);
                    }

                    if (show.aspect.length === 0) {
                        return ! card.aspects.some(aspect => aspect.name === "Heroism" || aspect.name === "Villainy")
                    }
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
                    if (selectedLeader.value === undefined) {
                        selectedLeader.value = (card.version.number)
                    }
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
