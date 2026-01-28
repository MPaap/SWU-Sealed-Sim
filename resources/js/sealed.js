import './bootstrap';
import { createApp, ref } from 'vue'
import BarChart from './components/BarChart.vue'

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faFilter, faShare, faRefresh, faSort, faFileArrowDown, faFloppyDisk, faCaretDown } from '@fortawesome/free-solid-svg-icons'

library.add(faFilter)
library.add(faShare)
library.add(faRefresh)
library.add(faSort)
library.add(faFileArrowDown)
library.add(faFloppyDisk)
library.add(faCaretDown)

import { useFloating, autoUpdate, offset, flip, shift } from '@floating-ui/vue';

createApp({

    components: { BarChart, FontAwesomeIcon },

    setup() {
        const reference = ref(null);
        const floating = ref(null);
        const isVisible = ref(false);
        const activeCard = ref(null);

        const { floatingStyles } = useFloating(reference, floating, {
            whileElementsMounted: autoUpdate,
            placement: 'right',
            middleware: [offset(10), flip(), shift()],
        });

        const showTooltip = (event, card) => {
            reference.value = event.currentTarget;
            activeCard.value = card;
            isVisible.value = true;
        };

        const hideTooltip = () => {
            isVisible.value = false;
        };

        // 2. Everything returned here is available in your HTML
        return {
            reference,
            floating,
            isVisible,
            activeCard,
            floatingStyles,
            showTooltip,
            hideTooltip
        };
    },

    data() {
        return {
            seed: null,
            set: {},
            pool: [],
            leaders: [],
            bases: [],
            allCards: [],
            openCards: [],
            selectedCards: [],
            selectedLeader: null,
            selectedBase: null,
            tab: 'deck',
            sort_by: 'number',
            show: {
                rarity: ['Common', 'Special', 'Uncommon', 'Rare', 'Legendary'],
                aspect: ['Villainy', 'Heroism', 'Vigilance', 'Command', 'Aggression', 'Cunning']
            },
            basesSelect: false,
        };
    },

    mounted() {
        toast.info("Loading cards", {
            autoClose: 5000,
            position: toast.POSITION.BOTTOM_RIGHT,
        });

        const seed = new URLSearchParams(location.search).get('seed') ?? null;

        let url = '/pool/'+ this.setCode;
        if (seed) {
            url += '?seed=' + seed;
        }

        axios.get(url).then((response) => {
            this.seed = response.data.seed;
            this.set = response.data.set;
            this.pool = response.data.packs;

            this.pool.forEach((pack) => {
                pack.forEach((card) => {
                    switch (card.type) {
                        case 'Leader':
                            this.leaders.push(card);

                            if (this.selectedLeader === null) {
                                this.selectedLeader = card;
                            }
                            break;

                        case 'Base':
                            this.bases.push(card);
                            this.selectedBase = card;

                            this.openCards.push(card);
                            this.allCards.push(card);

                            break;

                        default:
                            this.openCards.push(card);
                            this.allCards.push(card);
                    }
                });
            });

            response.data.default_bases.forEach((card) => {
                this.bases.push(card);

                if (this.selectedBase === null) {
                    this.selectedBase = card;
                }
            });

            toast.success("Deck loaded, have fun!", {
                autoClose: 5000,
                position: toast.POSITION.BOTTOM_RIGHT,
            });
        });
    },

    methods: {
        getExportCode(number){
            return this.setCode + "_" + this.padNumber(number)
        },

        switchTab(name) {
            this.tab = name;
        },

        selectLeader(card) {
            this.selectedLeader = card;
        },

        selectBase(card) {
            this.selectedBase = card;
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

        shareSeed()
        {
            const url = new URL(window.location.href);
            url.search = '';

            const cleanUrl = url.toString();

            navigator.clipboard.writeText(cleanUrl + '?seed=' + this.seed);

            toast.success("Link copied to clipboard", {
                autoClose: 5000,
                position: toast.POSITION.BOTTOM_RIGHT,
            });
        },

        exportToJson() {
            let data = {
                metadata: { name: "swusealed.com - " + this.setCode +" - " + this.seed },
                leader: {
                    id: this.getExportCode(this.selectedLeader.normal_version.number),
                    count: 1,
                },
                base: {
                    id: this.getExportCode(this.selectedBase.normal_version.number),
                    count: 1,
                },
                deck: [],
            };

            this.selectedCards.forEach((card) => {
                let id = this.getExportCode(card.normal_version.number);
                let key = data.deck.findIndex(item => item.id === id);

                if (key > -1) {
                    data.deck[key].count++;
                } else {
                    data.deck.push({ id: id, count: 1 });
                }
            });

            navigator.clipboard.writeText(JSON.stringify(data));

            toast.success("Deck copied to clipboard", {
                autoClose: 5000,
                position: toast.POSITION.BOTTOM_RIGHT,
            });
        },

        saveDeck() {
            axios.post('/pool/' + this.setCode + '/' + this.seed, {
                leader: this.getExportCode(this.selectedLeader.version.number),
                base: this.getExportCode(this.selectedBase.normal_version.number),
                deck: this.selectedCards
            }).then(response => {
                toast.success("Deck has been saved", {
                    autoClose: 5000,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
            }).catch(error => {
                Object.keys(error.response.data.errors).forEach(key => {
                    toast.error(error.response.data.errors[key], {
                        autoClose: 5000,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                });
            })
        },

        padNumber(n) {
            if (n < 10) return "00" + n;
            if (n < 100) return "0" + n;
            return String(n);
        },

        countCardsWithAspect(aspectName) {
            return this.sortedOpenCards.filter(card =>
                card.aspects.some(a => a.name === aspectName)
            ).length;
        },

        countCardsWithRarity(rarity) {
            return this.sortedOpenCards.filter(card => card.version.rarity === rarity).length;
        },
    },

    computed: {
        sortedOpenCards() {
            let cards = this.openCards
                .filter(card => this.show.rarity.includes(card.version.rarity))
                .filter(card => {
                    const cardAspects = card.aspects.map(a => a.name);

                    // Card must have ALL its aspects inside the filter array
                    const allAspectsAllowed = cardAspects.every(a =>
                        this.show.aspect.includes(a)
                    );

                    return allAspectsAllowed;
                })

            cards.sort((a, b) => a.normal_version.number - b.normal_version.number);

            if (this.sort_by === 'cost') {
                cards.sort((a, b) => a.cost - b.cost);
            }

            return cards;
        },

        sortedSelectedCards() {
            return this.selectedCards.sort((a, b) => a.cost - b.cost);
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
                Unit: 0,
                Event: 0,
                Upgrade: 0,
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
        setCode() {
            const path = window.location.pathname;
            const segments = path.split('/').filter(Boolean);

            return segments[1];
        }
    }

}).mount('#app');
