// Page component
window.profile_edit_info = function () {
  return {
    title: "Edit Info Profil",
    showPwd: false,
    data: {
      profile: {},
    },
    model: {
      name: "",
      short_description: "",
      gender: "",
      birthday: "",
      jobs: "",
    },
    errors: {
      name: "",
      short_description: "",
      gender: "",
      birthday: "",
      jobs: "",
    },

    init() {
      window.scrollTo({top:0, behavior:'auto'});

      document.title = this.title;
      Alpine.store("tarbiyya").currentPage = "profile";

      if(cachePageData['member/profile']){
        this.data = cachePageData['member/profile']
        this.prepareModel();
      } else {   
          fetchPageData('/member/profile/supply', {
              headers: {
                  'Authorization': `Bearer ` + Alpine.store('tarbiyya').sessionToken,
                  'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID,
              }
          }).then(data => {
              cachePageData['member/profile'] = data
              this.data = data
              this.prepareModel();
          })
      }
    },

    prepareModel() {
        // Prepare data model
        this.model.name = this.data.profile.name;
        this.model.short_description = this.data.profile.short_description;
        this.model.gender = this.data.profile.gender;
        this.model.birthday = this.data.profile.birthday;
        this.model.jobs = this.data.profile.jobs;
    },

    save() {
      this.errors = {
        name: "",
        short_description: "",
        gender: "",
        birthday: "",
        status_marital: "",
        jobs: "",
      };

      // Check login using axios post
      postPageData("/member/profile/edit_info", this.model)
      .then((response) => {
        if (response.success == 1) {
          toastr('Data info berhasil diperbaharui', 'success', 'bottom');
        } else {
          this.errors = response.errors;
        }
      });
    },
  };
};
