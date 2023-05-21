// Check that service workers are supported
const useSW = false;
if(useSW)
  if ('serviceWorker' in navigator) {
    // Use the window load event to keep the page load performant
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/service-worker.js');
    });
  }

const Axios = axios.create({
  baseURL: base_url,
  headers: {
    Authorization: localStorage.getItem('cdplusertoken')
  }
})