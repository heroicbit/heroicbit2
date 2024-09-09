import axios from 'axios';
import 'bootstrap';
import NProgress from 'nprogress';
import PineconeRouter from 'pinecone-router'
import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';
import toastr from 'toastr';

// Attach libraries to the window object for global access
window.axios = axios;
window.NProgress = NProgress;
window.PineconeRouter = PineconeRouter;
window.Alpine = Alpine;
window.Swiper = Swiper;
window.toastr = toastr;
Alpine.plugin(PineconeRouter);

// Helper functions
window.fetchPageData = function(page){ 
    return axios
        .get(base_url + page + '?dataonly=1')
        .then(response => {
            return response.data
        })
        .catch(error => {
            console.log(error);
        });
}

const cachePageData = {};
window.pageData = function(page){
    return {
        data: [],
        init(){
            if (cachePageData[page]) {
                console.log('Returning cached data for:', page);
                this.data = cachePageData[page];
            } else {
                fetchPageData(page).then(data => {
                    cachePageData[page] = data
                    this.data = data
                })
            }
        }
    }
}
window.cachePageData = cachePageData;