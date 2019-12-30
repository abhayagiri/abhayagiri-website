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
                        v-model="currentRefinement"
                        @input="refine($event.currentTarget.value)"
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
                                {{ nbHits }} items found. <!-- TODO localize -->
                            </ais-stats>
                        </template>
                        <template v-else-if="query">
                            <ais-stats>
                                No items found. <!-- TODO localize -->
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

    const algoliaClient = algoliasearch(
        window.Laravel.algoliaId,
        window.Laravel.algoliaSearchKey
    );

    export default {

        computed: {
            searchFilters() {
                // If on an English page, only search English.
                //
                // TODO: We would like to also investigate the contents of the query
                // and provide the relavent filter in those cases. See:
                // https://github.com/abhayagiri/abhayagiri-website/issues/132
                return window.Locale === 'th' ? '' : 'text.lng:en';
            },
            searchPlaceholder() {
                // TODO localize
                return "Search...";
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
                        }
                        return algoliaClient.search(requests);
                    }
                },
                searchFunction(helper) {
                    //console.log(helper.state);
                    helper.search();
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
