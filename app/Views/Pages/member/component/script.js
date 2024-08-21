// Page member/component
window.member_component = function(){
    return {
        title: "Components",
        data: [],
        init(){
            document.title = this.title
            // Fetch data from API using Axios
            axios
                .get(base_url + 'member/component?dataonly=1')
                .then(response => {
                    this.data = response.data
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}