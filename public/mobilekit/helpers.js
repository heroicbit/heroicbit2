// Heroicbit2 Helper functions
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

// COOKIE SETTER GETTER
window.setCookie = function(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
window.getCookie = function(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
