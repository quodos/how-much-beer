<script>
    import find from 'lodash/find';
    import moment from 'moment';
    import IOdometer from 'vue-odometer';
    import 'odometer/themes/odometer-theme-default.css';
    import data from '../_data.json';

    export default {
        components: { IOdometer },

        data: () => ({
            numberOfBeers: 0,
            countryCode: null,
            noDataAvailable: false,
        }),

        methods: {
            calculateBeersDrank(perCapitaPerYear) {
                const perDay = perCapitaPerYear / 365;
                return Math.ceil(perDay * moment().dayOfYear());
            }
        },

        mounted() {
            fetch('http://ip-api.com/json')
                .then(response => {
                    return response.json();
                })
                .then(json => {
                    this.countryCode = json.countryCode;
                    const country = find(data.countries, ['code', this.countryCode]);

                    if (typeof country === 'undefined') {
                        this.noDataAvailable = true;
                        return;
                    }

                    setInterval(() => {
                        this.numberOfBeers = this.calculateBeersDrank(country.number);
                    }, 400);

                });
        },

    };
</script>

<template>
    <main class="main">
        <div v-if="countryCode !== null && !noDataAvailable">
            <p>
                <span>You already drank</span>
                <i-odometer class="number" :value="numberOfBeers" />
                <span>beers* this year**</span>
            </p>
            <p class="info">
                <span>*1 beer = 0.5 litres, rounded up</span>
                <span>**according to <a href="https://brewersofeurope.org/uploads/mycms-files/documents/publications/2017/Statistics-201712-001.pdf" target="_blank" rel="external">brewersofeurope.org’s satistic of 2016</a></span>
                <span>Photo credits: <a href="https://unsplash.com/photos/rrvAuudnAfg" target="_blank" rel="external">Patrick Fore (unsplash.com)</a></span>
            </p>
        </div>
        <div v-else-if="noDataAvailable === true">
            <p class="info">
                <span>There's no data available for your country 😢🍻</span>
            </p>
        </div>
        <div v-else>
            <p class="info">
                <span>Loading&hellip;</span>
            </p>
        </div>
    </main>
</template>
