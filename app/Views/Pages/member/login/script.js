// Page member/component
window.member_login = function () {
  return {
    title: "Login",
    showPwd: false,
    forceKodePesantren: false,
    errorMessage: null,
    data: [],
    init() {
      if (localStorage.getItem("intro") != 1) {
        window.PineconeRouter.context.navigate("/intro");
      }

      document.title = this.title;
      Alpine.store("member").currentPage = "login";
      Alpine.store("member").showBottomMenu = false;

      this.forceKodePesantren = localStorage.getItem("forcekodepesantren");

      if (cachePageData["member/login"]) {
        this.data = cachePageData["member/login"];
      } else {
        fetchPageData("pages/member/login", {
          headers: {
            "Pesantrenku-ID": localStorage.getItem("kodepesantren"),
          },
        }).then((data) => {
          cachePageData["member/login"] = data;
          this.data = data;
        });
      }
    },

    login() {
      this.errorMessage = "";

      // Check login using axios post
      const formData = new FormData();
      formData.append("username", this.data.username);
      formData.append("password", this.data.password);
      axios
        .post("/pages/member/login", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            "Pesantrenku-ID": localStorage.getItem("kodepesantren"),
          },
        })
        .then((response) => {
          if (response.data.found == 1) {
            localStorage.setItem("heroic_token", response.data.jwt);
            Alpine.store("member").sessionToken =
              localStorage.getItem("heroic_token");
            window.PineconeRouter.context.navigate("/");
          } else {
            this.errorMessage = "Password tidak cocok atau akun belum terdaftar";
            setTimeout(() => (this.errorMessage = ""), 10000);
          }
        });
    },
  };
};
