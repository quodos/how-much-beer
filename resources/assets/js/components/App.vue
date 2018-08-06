<script>
    import find from 'lodash/find';
    import findIndex from 'lodash/findIndex';
    import moment from 'moment';
    import Odometer from 'vue-odometer';
    import { countries } from 'country-data';
    import 'odometer/themes/odometer-theme-default.css';
    import statisticalData from '../_data.json';

    export default {
        components: { Odometer },

        data: () => ({
            busy: false,
            countryCode: null,
            manualCountryCode: null,
        }),

        computed: {
            isDataAvailable() {
                if (this.countryCode === null && this.manualCountryCode === null) {
                    return false;
                }

                if (this.countryCode !== null && findIndex(this.statisticalData, ['code', this.countryCode]) !== -1) {
                    return true;
                }

                if (this.manualCountryCode !== null && findIndex(this.statisticalData, ['code', this.manualCountryCode]) !== -1) {
                    return true;
                }

                return false;
            },

            currentCountryData() {
                if (this.countryCode === null || !Object.prototype.hasOwnProperty.call(countries, this.countryCode)) {
                    return {
                        name: 'unknown',
                    };
                }

                return countries[this.countryCode];
            },

            countryCodeForCalculation() {
                if (this.manualCountryCode !== null) {
                    return this.manualCountryCode;
                }

                if (this.countryCode !== null) {
                    return this.countryCode;
                }

                return null;
            },

            countryStatsForCalculation() {
                const country = find(this.statisticalData, ['code', this.countryCodeForCalculation]);

                return typeof country !== 'undefined' ? country : null;
            },

            beersPerCapita() {
                return this.countryStatsForCalculation !== null ? this.countryStatsForCalculation.number : 0;
            },

            beersDrank() {
                return Math.ceil(this.beersPerCapita / 365 * moment().dayOfYear());
            },

            countries: () => countries,
            statisticalData: () => statisticalData.countries,
        },

        mounted() {
            this.busy = true;
            fetch('/api/country')
                .then(response => {
                    return response.text();
                })
                .then(countryCode => {
                    this.busy = false;
                    this.countryCode = countryCode;
                });
        },

    };
</script>

<template>
    <main class="main">
        <div v-if="busy">
            <p>
                <span>Loading&hellip;</span>
            </p>
        </div>
        <div v-else>
            <div v-show="isDataAvailable">
                <p>
                    <span>You already drank</span>
                    <odometer class="number" :value="beersDrank" ref="counter" />
                    <span>
                        {{ beersDrank | pluralize('beer') }}<sup>*</sup>
                        this year<sup>**</sup>
                    </span>
                </p>
                <p class="info">
                    <span>*1 beer = 0.5 litres, rounded up</span>
                    <span>
                        **according to
                        <a href="https://brewersofeurope.org/uploads/mycms-files/documents/publications/2017/Statistics-201712-001.pdf" target="_blank" rel="external">
                            brewersofeurope.org’s satistic of 2016
                        </a>
                    </span>
                    <span>
                        Made by <a href="https://thomaswilhelm.at">Thomas Wilhelm</a>
                    </span>
                    <span>
                        Photo credits:
                        <a href="https://unsplash.com/photos/rrvAuudnAfg" target="_blank" rel="external">
                            Patrick Fore (unsplash.com)
                        </a>
                    </span>
                </p>
            </div>
            <div v-show="!isDataAvailable">
                <p class="info">
                    <span v-if="currentCountryData.name === 'unknown'">
                        We couldn't detect your country.
                    </span>
                    <span v-else>
                        There's no data available for {{ currentCountryData.name }} 😢🍻
                    </span>
                    <span>
                        <select v-model="manualCountryCode">
                            <option disabled selected>Choose a country manually&hellip;</option>
                            <option v-for="country in countries.all" :value="country.alpha2">
                                {{ country.name }}
                            </option>
                        </select>
                    </span>
                </p>
            </div>
        </div>
    </main>
</template>
