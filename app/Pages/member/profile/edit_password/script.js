// Page component
window.profile_edit_password = function () {
  return {
    title: "Ganti Kata Sandi",
    showPwd: false,
    model: {
      password: "",
      repeat_password: "",
    },
    errors: {
      password: "",
      repeat_password: "",
    },
    saving: false,

    init() {
      window.scrollTo({top:0, behavior:'auto'});

      document.title = this.title;
      Alpine.store("tarbiyya").currentPage = "profile";
    },

    save() {
      this.errors = {
        password: "",
        repeat_password: "",
      };

      if (this.model.password == "") {
        this.errors.password = "Kata sandi baru tidak boleh kosong.";
        return;
      }
      if (this.model.password.length < 6) {
        this.errors.password = "Kata sandi minimal 6 karakter.";
        return;
      }
      if (this.model.repeat_password == "") {
        this.errors.repeat_password = "Konfirmasi kata sandi tidak boleh kosong.";
        return;
      }
      if (this.model.password != this.model.repeat_password) {
        this.errors.repeat_password = "Konfirmasi kata sandi tidak sama.";
        return;
      }

      this.saving = true;
      postPageData("/member/profile/edit_password", this.model)
      .then((response) => {
        this.saving = false;
        if (response?.success == 1) {
          toastr("Kata sandi berhasil diubah.", "success", "bottom");
          this.model.password = "";
          this.model.repeat_password = "";
        } else {
          this.errors = response?.errors || {};
          if (response?.message) {
            toastr(response.message, "error", "bottom");
          }
        }
      });
    },
  };
};
