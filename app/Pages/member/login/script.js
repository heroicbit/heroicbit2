// Page member/component
window.member_login = function () {
  return {
    title: "Login",
    showPwd: false,
    forceKodePesantren: false,
    errorMessage: null,
    buttonSubmitting: false,
    data: {
      username: "",
      password: "",
      logo: "",
      sitename: "",
    },
    sanboxLogin: {},
    

    init() {
      if (localStorage.getItem("intro") != 1) {
        window.PineconeRouter.context.navigate("/intro");
      } else if(Object.keys(Alpine.store("member").tarbiyyaSetting).length < 1){
        window.PineconeRouter.context.navigate("/kodepesantren");
      }

      // Place sandbox login if set
      this.sandboxLogin = JSON.parse(Alpine.store("member").tarbiyyaSetting.sandbox_login ? Alpine.store("member").tarbiyyaSetting.sandbox_login : "{}");
      if(this.sandboxLogin && Object.keys(this.sandboxLogin).length > 0){
        this.data.username = this.sandboxLogin.username;
        this.data.password = this.sandboxLogin.password;
      }
      
      document.title = this.title;
      Alpine.store("member").currentPage = "login";
      Alpine.store("member").showBottomMenu = false;

      this.data.logo = Alpine.store("member").tarbiyyaSetting.auth_logo;
      this.data.sitename = Alpine.store("member").tarbiyyaSetting.app_title;

      this.forceKodePesantren = localStorage.getItem("forcekodepesantren");
    },

    login() {
      this.errorMessage = "";
      this.buttonSubmitting = true;

      // Check login using axios post
      const formData = new FormData();
      formData.append("username", this.data.username);
      formData.append("password", this.data.password);
      axios
        .post("/member/login", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            "Pesantrenku-ID": Alpine.store("member").pesantrenID,
          },
        })
        .then((response) => {
          if (response.data.found == 1) {
            localStorage.setItem("heroic_token", response.data.jwt);
            Alpine.store("member").sessionToken = localStorage.getItem("heroic_token");

            setTimeout(() => {
              window.location.replace("/");
            }, 500);
          } else {
            this.buttonSubmitting = false;
            this.errorMessage = "Password tidak cocok atau akun belum terdaftar";
            setTimeout(() => (this.errorMessage = ""), 10000);
          }
        });
    },

  };
};
