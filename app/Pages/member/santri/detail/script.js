// Page member/santri/detail
window.member_santri_detail = function (id) {
  return {
    title: "Detail Santri",
    id: id,
    notFound: false,
    detailSantri: {
      nama_santri: null,
      nis: null,
      nik_santri: null,
      nisn: null,
      tempat_lahir_santri: null,
      tanggal_lahir_santri: null,
      jenis_kelamin: null,
      status_keluarga: null,
      anak_ke: null,
      jumlah_saudara_kandung: null,
      jumlah_saudara_tiri: null,
      jumlah_saudara_angkat: null,
      hobi: null,
      cita: null,
      asal_sekolah: null,
      npsn_sekolah: null,
      alamat_sekolah: null,
      nama_ayah: null,
      nik_ayah: null,
      tempat_lahir_ayah: null,
      tanggal_lahir_ayah: null,
      kontak_ayah: null,
      pekerjaan_ayah: null,
      pendidikan_ayah: null,
      nama_ibu: null,
      nik_ibu: null,
      tempat_lahir_ibu: null,
      tanggal_lahir_ibu: null,
      kontak_ibu: null,
      pekerjaan_ibu: null,
      pendidikan_ibu: null,
      penghasilan: null,
      alamat: null,
      rtrw: null,
      desa: null,
      kecamatan: null,
      kota: null,
      provinsi: null,
      kodepos: null,
      tahun_masuk: null,
      iuran_bulanan: null,
      presensi: {},
      total_sakit: 0,
      total_izin: 0,
      total_alpa: 0,
    },
    calendar: null,
    selectedDate: null,
    selectedPresensi: {},

    init() {
      document.title = this.title;
      Alpine.store("tarbiyya").currentPage = "santri";
      Alpine.store("tarbiyya").showBottomMenu = true;

      // Lookup from cached list data first
      let cached = cachePageData["member/santri/detail/" + this.id];
      if (cached && cached.santri) {
        let found = cached.santri.find((s) => s.id == this.id);
        if (found) {
          this.detailSantri = found;
          return;
        }
      }

      // Fetch from API if not cached
      fetchPageData("member/santri/detail/supply/" + this.id, {
        headers: {
          Authorization: `Bearer ` + Alpine.store("tarbiyya").sessionToken,
          "Pesantrenku-ID": Alpine.store("tarbiyya").pesantrenID,
        },
      }).then((data) => {
        if (data.found != 1) {
          this.notFound = true;
        } else {
          this.detailSantri = data.santri;
        }
      });
    },

    loadDetailPresensi() {
      // Reset calendar object
      this.calendar = null;

      // Define calendar options
      const options = {
        settings: {
          visibility: {
            theme: "light",
            weekend: false,
          },
          lang: "id",
        },
        actions: {
          clickDay: (e, self) => {
            this.getSelectedPresensi(self.selectedDates[0]);
          },
        },
        popups: {
          "2024-10-28": {
            modifier: "bg-danger",
          },
        },
      };

      // Load data presensi
      if (cachePageData[`member/santri/${this.id}/presensi`]) {
        this.detailSantri.presensi =
          cachePageData[`member/santri/${this.id}/presensi`];
        options.popups = this.setCalendarPopups(this.detailSantri.presensi);

        // Init calendar
        this.calendar = new VanillaCalendar("#calendar", options);
        this.calendar.init();
      } else {
        fetchPageData("member/santri/detailPresensi/" + this.id, {
          headers: {
            Authorization: `Bearer ` + Alpine.store("tarbiyya").sessionToken,
            "Pesantrenku-ID": Alpine.store("tarbiyya").pesantrenID,
          },
        }).then((data) => {
          cachePageData[`member/santri/${this.id}/presensi`] = data.presensi;
          this.detailSantri.presensi = data.presensi;
          options.popups = this.setCalendarPopups(this.detailSantri.presensi);

          // Init calendar
          this.calendar = new VanillaCalendar("#calendar", options);
          this.calendar.init();
        });
      }
    },

    setCalendarPopups(presensi) {
      const calendarPopups = {};
      let illCount = 0;
      let permitCount = 0;
      let noinfoCount = 0;

      if (!presensi) return calendarPopups;

      Object.keys(presensi).forEach((date) => {
        const entry = presensi[date];

        // Cek modifier berdasarkan nilai 'ill', 'permit', atau 'noinfo'
        let modifier = null;
        if (entry.ill === "1") {
          modifier = "sakit";
          illCount++;
        } else if (entry.permit === "1") {
          modifier = "izin";
          permitCount++;
        } else if (entry.noinfo === "1") {
          modifier = "alpa";
          noinfoCount++;
        }

        // Jika ada modifier, masukkan ke calendarPopups
        if (modifier) {
          calendarPopups[date] = { modifier };
        }
      });

      this.detailSantri.total_sakit = illCount;
      this.detailSantri.total_izin = permitCount;
      this.detailSantri.total_alpa = noinfoCount;

      return calendarPopups;
    },

    getSelectedPresensi(date) {
      this.selectedDate = date;
      let presensi = this.detailSantri.presensi[this.selectedDate];
      let caption,
        status = null;

      if (presensi) {
        if (presensi.ill === "1") {
          status = "sakit";
          caption = "Sakit";
        } else if (presensi.permit === "1") {
          status = "izin";
          caption = "Izin";
        } else if (presensi.noinfo === "1") {
          status = "alpa";
          caption = "Alpa";
        }

        this.selectedPresensi = {};
        this.selectedPresensi.date = this.selectedDate;
        this.selectedPresensi.status = status;
        this.selectedPresensi.caption = caption;
        this.selectedPresensi.description = presensi?.description;
      } else {
        this.selectedPresensi = {};
      }
    },

    formatDate(dateString) {
      if (dateString && dateString != "0000-00-00") {
        const date = new Date(dateString + "T00:00:00");
        const options = { day: "numeric", month: "long", year: "numeric" };
        return new Intl.DateTimeFormat("id-ID", options).format(date);
      }
      return "";
    },
  };
};
