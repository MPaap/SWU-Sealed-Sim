import './bootstrap';
import { createApp, ref } from 'vue'

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faFileArrowDown, faStar } from '@fortawesome/free-solid-svg-icons'

library.add(faFileArrowDown)
library.add(faStar)

import { useFloating, autoUpdate, offset, flip, shift } from '@floating-ui/vue';

createApp({

    components: { FontAwesomeIcon },

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
            //
        };
    },

    mounted() {
        //
    },

    methods: {
        copyBySelector(selector) {
            const element = document.querySelector(selector);

            if (!element) return console.log("Element niet gevonden");

            // Pak de tekst (werkt voor zowel textarea/input als gewone html elementen)
            const textToCopy = element.value || element.innerText;

            navigator.clipboard.writeText(textToCopy)
                .then(() => {
                    toast.success("Deck copied to clipboard", {
                        autoClose: 5000,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                })
                .catch(err => {
                    toast.error(err, {
                        autoClose: 5000,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                });
        },

        addRating(event, card_id, set) {
            const selectedValue = event.target.value;

            axios.post('/rating/' + set, {
                card_id: card_id,
                rating: selectedValue
            })
            .then(() => {
                toast.success("Rating updated", {
                    autoClose: 3000,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
            })
            .catch(error => {
                toast.error("Error updating rating", {
                    autoClose: 3000,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
                // Reset de select naar de vorige waarde bij een fout
                event.target.value = event.target.getAttribute('data-previous-value');
            });
        }
    },

    computed: {
        //
    },
}).mount('#app');
