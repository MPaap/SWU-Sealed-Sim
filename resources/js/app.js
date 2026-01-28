import './bootstrap';
import { createApp, ref } from 'vue'

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faFileArrowDown } from '@fortawesome/free-solid-svg-icons'

library.add(faFileArrowDown)

createApp({

    components: { FontAwesomeIcon },

    setup() {
        //
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
        }
    },

    computed: {
        //
    }
}).mount('#app');
