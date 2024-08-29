// Page member/home
window.member_home = function(){
    return {
        title: "Discover",
        data: [],
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'home'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/home']){
                this.data = cachePageData['member/home']
            } else {   
                fetchPageData('member/home').then(data => {
                    cachePageData['member/home'] = data
                    this.data = data
                })
            }

            // InstantSearch
            const searchClient = algoliasearch('72D1PNVZLX', '4ff0451309e2d5c546008a13ed5925b6');
            const search = instantsearch({
                indexName: 'tester',
                searchClient,
                searchFunction(helper) {
                    const query = helper.state.query;
                    if (query) {
                      helper.search();
                    }
                },
            });
            search.addWidgets([
                instantsearch.widgets.searchBox({
                    container: '#searchbox',
                    placeholder: 'Cari user, course, thread, atau event...',
                    searchAsYouType: false,
                }),
                instantsearch.widgets.hits({
                    container: '#hits',
                    templates: {
                    item: (hit, { html, components }) => html`
                        <div class="item">
                            <div class="imageWrapper">
                                <img src="${hit.thumbnail ? hit.thumbnail : 'mobilekit/assets/img/sample/photo/1.jpg'}" class="imaged w64" />
                            </div>
                            <div class="in">
                                <div>
                                    <header>${components.Highlight({ hit, attribute: "object_type" })}</header>
                                    ${components.Highlight({ hit, attribute: "title" })}
                                    <footer>${components.Highlight({ hit, attribute: "subtitle" })}</footer>
                                </div>
                            </div>
                        </div>
                    `,
                    },
                })
            ]);
            search.start();
        }
    }
}
