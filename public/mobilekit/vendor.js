import axios from 'axios';
import 'bootstrap';
import NProgress from 'nprogress';
import PineconeRouter from 'pinecone-router'
import Alpine from 'alpinejs';

// Attach libraries to the window object for global access
window.axios = axios;
window.NProgress = NProgress;
window.PineconeRouter = PineconeRouter;
window.Alpine = Alpine;
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