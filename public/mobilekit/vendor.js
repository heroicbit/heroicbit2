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

// Configure NProgress
NProgress.configure({ showSpinner: false });
