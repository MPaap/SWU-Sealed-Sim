import './bootstrap';
import { createApp } from 'vue'
import BarChart from './components/BarChart.vue'

createApp({

    components: { BarChart },

    data() {
        return {
            pool: [],
            leaders: [],
            bases: [],
            allCards: [],
            openCards: [],
            selectedCards: [],
            selectedLeader: null,
            selectedBase: 'SEC_019',
            tab: 'deck',
            show: {
                rarity: ['Common', 'Special', 'Uncommon', 'Rare', 'Legendary'],
                aspect: ['Villainy', 'Heroism']
            },
        };
    },

    mounted() {
        axios.get('/pool/SEC').then((response) => {
            this.pool = response.data;

            this.pool.forEach((pack) => {
                pack.forEach((card) => {
                    switch (card.type) {
                        case 'Leader':
                            if (this.selectedLeader === null) {
                                this.selectedLeader = card.version.number;
                            }
                            this.leaders.push(card);
                            break;

                        case 'Base':
                            this.bases.push(card);
                            break;

                        default:
                            this.openCards.push(card);
                            this.allCards.push(card);
                    }
                });
            });
        });
    },

    methods: {

        switchTab(name) {
            this.tab = name;
        },

        selectLeader(number) {
            this.selectedLeader = number;
        },

        moveToSelected(uuid) {
            let key = this.openCards.findIndex(card => card.tmp_id === uuid);

            this.selectedCards.push(this.openCards[key]);
            this.openCards.splice(key, 1);
        },

        moveToOpen(uuid) {
            let key = this.selectedCards.findIndex(card => card.tmp_id === uuid);

            this.openCards.push(this.selectedCards[key]);
            this.selectedCards.splice(key, 1);
        },

        aspectLess() {
            return this.allCards.filter(card => card.aspects.length === 0).length;
        },

        heroism() {
            return this.allCards.filter(card =>
                card.aspects.length === 1 &&
                card.aspects[0].name === "Heroism"
            ).length;
        },

        villainy() {
            return this.allCards.filter(card =>
                card.aspects.length === 1 &&
                card.aspects[0].name === "Villainy"
            ).length;
        },

        getAspect(arr) {
            if (arr.length === 1) {
                return this.allCards.filter(card => {
                    const aspectNames = card.aspects.map(a => a.name);
                    return (
                        aspectNames.includes(arr[0]) &&
                        !aspectNames.includes("Heroism") &&
                        !aspectNames.includes("Villainy")
                    );
                }).length;
            }

            return this.allCards.filter(card =>
                arr.every(req =>
                    card.aspects.some(a => a.name === req)
                )
            ).length;
        },

        toggleShow(string, key) {
            if (this.show[key].includes(string)) {
                const index = this.show[key].indexOf(string);
                if (index !== -1) {
                    this.show[key].splice(index, 1);
                }
            } else {
                this.show[key].push(string);
            }
        },

        exportToJson() {
            let array = {
                metadata: { name: "SWUSEALEDBUILDER" },
                leader: {
                    id: "SEC_" + this.padNumber(this.selectedLeader),
                    count: 1,
                },
                base: {
                    id: this.selectedBase,
                    count: 1,
                },
                deck: [],
            };

            this.selectedCards.forEach((card) => {
                let id = 'SEC_' + this.padNumber(card.version.number);
                let key = array.deck.findIndex(item => item.id === id);

                if (key > -1) {
                    array.deck[key].count++;
                } else {
                    array.deck.push({ id: id, count: 1 });
                }
            });

            navigator.clipboard.writeText(JSON.stringify(array));
        },

        padNumber(n) {
            if (n < 10) return "00" + n;
            if (n < 100) return "0" + n;
            return String(n);
        }
    },

    computed: {
        sortedOpenCards() {
            return this.openCards
                .filter(card => this.show.rarity.includes(card.version.rarity))
                .filter(card => {
                    if (this.show.aspect.length === 2) return true;

                    const hasFilter = card.aspects.some(a => a.name === this.show.aspect[0]);
                    const hasHeroism = card.aspects.some(a => a.name === "Heroism");
                    const hasVillainy = card.aspects.some(a => a.name === "Villainy");

                    if (this.show.aspect.length === 1) {
                        return hasFilter || (!hasHeroism && !hasVillainy);
                    }

                    if (this.show.aspect.length === 0) {
                        return !card.aspects.some(a =>
                            a.name === "Heroism" || a.name === "Villainy"
                        );
                    }
                })
                .sort((a, b) => a.version.number - b.version.number);
        },

        sortedSelectedCards() {
            return this.selectedCards.sort((a, b) => a.cost - b.cost);
        },

        commons() {
            return this.allCards.filter(card => card.version.rarity === 'Common').length;
        },
        uncommons() {
            return this.allCards.filter(card => card.version.rarity === 'Uncommon').length;
        },
        specials() {
            return this.allCards.filter(card => card.version.rarity === 'Special').length;
        },
        rares() {
            return this.allCards.filter(card => card.version.rarity === 'Rare').length;
        },
        legendaries() {
            return this.allCards.filter(card => card.version.rarity === 'Legendary').length;
        },
        costCounts() {
            const counts = Array(9).fill(0);
            this.selectedCards.forEach(card => {
                const cost = card.cost;
                if (cost >= 8) {
                    counts[8]++;
                } else {
                    counts[cost]++;
                }
            });
            return counts;
        },
        costLabels() {
            return ['0','1','2','3','4','5','6','7','8+'];
        },
        typeCounts() {
            const counts = {
                unit: 0,
                event: 0,
                upgrade: 0,
            };

            this.selectedCards.forEach(card => {
                if (counts[card.type]) {
                    counts[card.type]++;
                } else {
                    counts[card.type] = 1;
                }
            });

            // Convert to array for easier iteration
            return Object.entries(counts)
                .map(([name, count]) => ({ name, count }))
                .sort((a, b) => a.name.localeCompare(b.name)); // optional: sort alphabetically
        },
        arenaCounts() {
            const counts = {
                ground: 0,
                space: 0,
            };

            this.selectedCards.forEach(card => {
                card.arenas.forEach(arena => {
                    if (counts[arena.name]) {
                        counts[arena.name]++;
                    } else {
                        counts[arena.name] = 1;
                    }
                });
            });

            // Convert to array for easier iteration
            return Object.entries(counts)
                .map(([name, count]) => ({ name, count }))
                .sort((a, b) => a.name.localeCompare(b.name)); // optional: sort alphabetically
        },
        traitCounts() {
            const counts = {};

            this.selectedCards.forEach(card => {
                card.traits.forEach(trait => {
                    if (counts[trait.name]) {
                        counts[trait.name]++;
                    } else {
                        counts[trait.name] = 1;
                    }
                });
            });

            // Convert to array for easier iteration
            return Object.entries(counts)
                .map(([name, count]) => ({ name, count }))
                .sort((a, b) => a.name.localeCompare(b.name)); // optional: sort alphabetically
        },
    }

}).mount('#app');
