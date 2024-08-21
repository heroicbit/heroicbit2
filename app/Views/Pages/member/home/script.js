// Page member/home
window.member_home = function(){
    return {
        title: "Discover",
        data: [],
        init(){
            document.title = this.title;

            fetchPageData('member/home').then(data => {
                this.data = data
            })
            // Fetch data from API using Axios
            // axios
            //     .get(base_url + 'member/home?dataonly=1')
            //     .then(response => {
            //         this.data = response.data;
            //     })
            //     .catch(error => {
            //         console.log(error);
            //     });
        }
    }
}