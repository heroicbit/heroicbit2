function parseJwt () {
  let token = localStorage.getItem('cdplusertoken')
  let base64Url = token.split('.')[1]
  let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/')
  let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
  }).join(''))
  return JSON.parse(jsonPayload)
};


// Alpinejs shared data
let currentUser = parseJwt();

document.addEventListener('alpine:init', () => {
  Alpine.store('currentUser', {
  	fullname: currentUser.fullname
  });
});

var onlineStat = true;
window.addEventListener("load", () => {
  function handleNetworkChange(event) {
    if (navigator.onLine) {
      onlineStat = true;
      $('.offline-notification').addClass('sr-only');
    } else {
      onlineStat = false;
      $('.offline-notification').removeClass('sr-only');
    }
  }
  window.addEventListener("online", handleNetworkChange);
  window.addEventListener("offline", handleNetworkChange);
});