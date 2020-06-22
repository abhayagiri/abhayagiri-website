<template>
    <ais-instant-search
        :search-client="searchClient"
        :search-function="searchFunction"
        :index-name="indexName"
    >
        <ais-configure :filters="searchFilters" />
        <ais-configure :attributesToSnippet="snippetAttributes" />
        <ais-configure snippetEllipsisText="…" />
        <div class="search-box-container">
            <div class="search-box">
                <ais-search-box>
                    <input
                        slot-scope="{ currentRefinement, refine }"
                        ref="searchInput"
                        :placeholder="searchPlaceholder"
                        type="search"
                        :value="currentRefinement"
                        @input="determineLanguageFilter($event.currentTarget.value); refine($event.currentTarget.value)"
                    >
                </ais-search-box>
            </div>
            <div class="powered-by">
                <ais-powered-by />
            </div>
        </div>
        <div class="search-results-container">
            <div class="search-results">
                <ais-state-results>
                    <template slot-scope="{ query, hits, nbHits }">
                        <template v-if="hits.length">
                            <ais-hits>
                                <template slot="item" slot-scope="{ item }">
                                    <div class="path">
                                        <a :href="item.text.path">
                                            <ais-highlight attribute="text.path" :hit="item" />
                                        </a>
                                    </div>
                                    <h2>
                                        <a :href="item.text.path">
                                            <ais-highlight attribute="text.title" :hit="item" />
                                        </a>
                                    </h2>
                                    <h3 class="author" v-if="item.text.author">
                                        <ais-highlight attribute="text.author" :hit="item" />
                                    </h3>
                                    <p>
                                        <ais-snippet attribute="text.body" :hit="item" />
                                    </p>
                                </template>
                            </ais-hits>
                            <ais-stats>
                                {{ $tc('common.search_result_count', nbHits) }}.
                            </ais-stats>
                        </template>
                        <template v-else-if="query">
                            <ais-stats>
                                {{ $tc('common.search_result_count', 0) }}.
                            </ais-stats>
                        </template>
                        <template v-else>
                            &nbsp;
                        </template>
                    </template>
                </ais-state-results>
            </div>
        </div>
    </ais-instant-search>
</template>

<script>
    import algoliasearch from 'algoliasearch/lite';
    import { EventBus } from './../../scripts/event_bus';

    let algoliaClient;
    if (window.Laravel && window.Laravel.algoliaId && window.Laravel.algoliaSearchKey) {
        algoliaClient = algoliasearch(
            window.Laravel.algoliaId,
            window.Laravel.algoliaSearchKey
        );
    } else {
        algoliaClient = null;
    }

    export default {

        methods: {
            determineLanguageFilter(query) {
                let lang = this.determineInputLanguage(query);

                if(window.Locale == 'en' && lang == 'en') {
                    this.languageFilter = lang;
                } else {
                    this.languageFilter = null;
                }
            },
            determineInputLanguage(query) {
                let thaiUnicodeRange = /[\u0E00-\u0E7F]/;

                if (thaiUnicodeRange.test(query.replace(/\s/g)) == true) {
                    return 'th';
                }

                return 'en';
            },
            searchFunction(helper) {
                helper.search();
            },
        },

        computed: {
            searchFilters() {
                if(this.languageFilter) {
                    return `text.lng:${this.languageFilter}`;
                }

                return '';
            },
            searchPlaceholder() {
                return this.$t('common.search') + '...';
            },
            snippetAttributes() {
                return [
                    'text.body:30',
                ];
            },
        },

        data() {
            const self = this;
            return {
                indexName: window.Laravel.algoliaPagesIndex,
                languageFilter: null,
                searchClient: {
                    search(requests) {
                        // Do not search on empty query...
                        //
                        // https://www.algolia.com/doc/guides/building-search-ui/going-further/conditional-requests/js/#prevent-search-on-empty-query
                        if (requests.every(({ params }) => !params.query)) {
                            return Promise.resolve({
                                results: requests.map(() => ({
                                    hits: [],
                                    nbHits: 0,
                                    processingTimeMS: 0,
                                })),
                            });
                        } else if (!algoliaClient) {
                            // Local Dev / Test
                            return Promise.resolve({
                                results: requests.map(() => ({
                                    hits: [
                                        {
                                            text: {
                                                path: '/books/1-what-is-buddhism',
                                                author: 'Abhayagiri Sangha',
                                            },
                                            _highlightResult: {
                                                text: {
                                                    title: {
                                                        value: 'What is Buddhism?',
                                                    },
                                                    author: {
                                                        value: 'Abhayagiri Sangha',
                                                    },
                                                    path: {
                                                        value: '/books/1-what-is-buddhism',
                                                    },
                                                },
                                            },
                                            _snippetResult: {
                                                text: {
                                                    body: {
                                                        value: 'What is Buddhism offers a very clear and concise overview of Buddhism and its core teachings.',
                                                        matchLevel: 'partial',
                                                    },
                                                },
                                            },
                                        },
                                    ],
                                    nbHits: 1,
                                    processingTimeMS: 1,
                                })),
                            });
                        } else {
                          return algoliaClient.search(requests);
                        }
                    }
                },
            };
        },

        mounted() {
            EventBus.$on('search', () => {
                Vue.nextTick(() => {
                    this.$refs.searchInput.select();
                    this.$refs.searchInput.focus();
                });
            });
        },

    };
</script>
