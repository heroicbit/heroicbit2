// Heroicbit2 Helper functions
window.fetchPageData = function(page, headers = {}){ 
    // Memastikan base_url diakhiri dengan '/'
    if (!base_url.endsWith('/')) {
        base_url += '/';
    }

    // Menggabungkan base_url dan page
    let fullUrl = base_url + page;

    // Menentukan separator berdasarkan ada atau tidaknya '?'
    let separator = fullUrl.includes('?') ? '&' : '?';

    // Menambahkan parameter 'dataonly=1'
    fullUrl += separator + 'dataonly=1';

    return axios
        .get(fullUrl, headers)
        .then(response => {
            return response.data;
        })
        .catch(error => {
            console.log(error);
        });
}

window.postPageData = function(page, data = {}, headers = {}) {
    if (!base_url.endsWith('/')) {
        base_url += '/';
    }
    let fullUrl = base_url + page;

    // Membuat objek FormData
    const formData = new FormData();

    // Menambahkan data yang dipassing dari parameter ke FormData
    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            const value = data[key];

            if (Array.isArray(value)) {
                // Jika nilai adalah array, tambahkan setiap elemen
                value.forEach(item => formData.append(`${key}[]`, item));
            } else if (value instanceof File || value instanceof Blob) {
                // Jika nilai adalah File atau Blob, tambahkan langsung
                formData.append(key, value);
            } else if (typeof value === 'object' && value !== null) {
                // Jika nilai adalah objek, Anda mungkin perlu serialisasi ke JSON
                formData.append(key, JSON.stringify(value));
            } else {
                // Nilai primitif (string, number, boolean)
                formData.append(key, value);
            }
        }
    }

    return axios
        .post(fullUrl, formData, headers)
        .then(response => {
            return response.data;
        })
        .catch(error => {
            console.error(error);
            // Tangani kesalahan sesuai kebutuhan
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

window.nl2br = function(str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}