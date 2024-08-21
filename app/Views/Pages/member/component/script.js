// Page member/component
function member_component(){
    return {
        title: "Components",
        data: [],
        init(){
            document.title = this.title
            // Fetch data from API using Axios
            axios
                .get(base_url + 'member/component?dataonly=1')
                .then(response => {
                    console.log(response.data);
                    this.data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
