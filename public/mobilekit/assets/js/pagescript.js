/*! For license information please see pagescript.js.LICENSE.txt */
window.member_feeds_detail=function(e){return{title:"Detail Info",id:e,notFound:!1,feed:{},swiper:null,init:function(){var e,t,n=this;document.title=this.title,Alpine.store("member").currentPage="feed",Alpine.store("member").showBottomMenu=!0,this.feed=null!==(e=null===(t=cachePageData["member/feeds"])||void 0===t?void 0:t.feeds.filter((function(e){return e.id==n.id})))&&void 0!==e?e:{},0==Object.keys(this.feed).length&&fetchPageData("api/member/feeds/detail/".concat(this.id),{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){0==e.data.post.length?n.notFound=!0:n.feed=e.data.post}))},initFeedSwiper:function(){this.swiper=new Swiper(".feed-carousel",{pagination:{el:".swiper-pagination",type:"fraction"},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"}})},formatDate:function(e){if(e&&"0000-00-00"!=e){var t=new Date(e);return new Intl.DateTimeFormat("id-ID",{day:"numeric",month:"long",year:"numeric"}).format(t)}return""}}},window.member_feeds=function(){return{title:"Info Pesantren",feeds:[],nextPage:1,empty:!1,init:function(){var e,t,n,a=this;document.title=this.title,Alpine.store("member").currentPage="feeds",Alpine.store("member").showBottomMenu=!0,(null===(e=cachePageData["member/feeds"])||void 0===e?void 0:e.feeds.length)>0?(cachePageData["member/feeds"].feeds.forEach((function(e){a.feeds.push(e)})),this.nextPage=null!==(t=cachePageData["member/feeds"].nextPage)&&void 0!==t?t:1,this.empty=null!==(n=cachePageData["member/feeds"].empty)&&void 0!==n&&n):(cachePageData["member/feeds"]={},this.loadFeeds())},formatDate:function(e){if(e&&"0000-00-00"!=e){var t=new Date(e);return new Intl.DateTimeFormat("id-ID",{day:"numeric",month:"long",year:"numeric"}).format(t)}return""},loadMore:function(){this.loadFeeds()},loadFeeds:function(){var e=this;fetchPageData("api/member/feeds?page=".concat(this.nextPage),{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){0==t.data.posts.length?(e.empty=!0,cachePageData["member/feeds"].empty=!0):(t.data.posts.forEach((function(t){e.feeds.push(t)})),e.nextPage++,cachePageData["member/feeds"].feeds=e.feeds,cachePageData["member/feeds"].nextPage=e.nextPage)}))},showDetail:function(e){window.PineconeRouter.context.navigate("/feeds/".concat(this.feeds[e].id))},stripIntro:function(e,t,n){var a=t.split(" ");return a.length>e?a.slice(0,e).join(" ")+'... <a href="javascript:void()" x-on:click="showDetail('.concat(n,')">Selengkapnya</a>'):t}}},window.member_home=function(){return{title:"Beranda",data:[],comingsoon:!1,showAllIcons:!1,swiperArticle:null,swiperVideo:null,unreadPengumuman:3,init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="home",Alpine.store("member").showBottomMenu=!0,cachePageData["member/home"]?this.data=cachePageData["member/home"]:fetchPageData("api/member/home",{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){cachePageData["member/home"]=t,e.data=t}))},initSwiperArticles:function(){var e={slidesPerView:1.6,spaceBetween:10,slidesOffsetBefore:15,slidesOffsetAfter:20,autoplay:{delay:6e4,pauseOnMouseEnter:!0},breakpoints:{640:{slidesPerView:2.8,spaceBetween:20}}};this.data.posts.length>2&&(e.autoplay.delay=6e4),this.swiperArticle=new Swiper(".swiper-article",e)},initSwiperVideos:function(){var e={slidesPerView:1.6,spaceBetween:10,slidesOffsetBefore:15,slidesOffsetAfter:20,autoplay:{delay:12e4,pauseOnMouseEnter:!0},breakpoints:{640:{slidesPerView:2.8,spaceBetween:20}}};this.data.videos.length>2&&(e.autoplay.delay=6e4),this.swiperVideo=new Swiper(".swiper-video",e)}}},window.member_intro=function(){return{title:"Intro",swiper:null,init:function(){document.title=this.title,Alpine.store("member").currentPage="intro",Alpine.store("member").showBottomMenu=!1,this.swiper=new Swiper(".swiper-intro",{slidesPerView:1,spaceBetween:20,autoplay:{delay:5e3,pauseOnMouseEnter:!0},pagination:{el:".swiper-pagination"}})},gotoLogin:function(){localStorage.setItem("intro",1),window.location.href="/member/kodepesantren"}}},window.member_kodepesantren=function(){return{title:"Kode Pesantren",buttonDisabled:!0,allcodes:[],kode:null,init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="kodepesantren",Alpine.store("member").showBottomMenu=!1,axios.get("/api/member/kodepesantren").then((function(t){t.data.length>1?e.allcodes=t.data:toastr.warning("Belum ada data pesantren")}))},checkKodePesantren:function(){var e=new FormData;e.append("kodepesantren",this.kode),axios.post("/api/member/kodepesantren",e,{headers:{"Content-Type":"multipart/form-data"}}).then((function(e){1==e.data.found?(localStorage.setItem("kodepesantren",e.data.pesantrenID),Alpine.store("member").kodePesantren=localStorage.getItem("kodepesantren"),setCookie("kodepesantren",e.data.pesantrenID,1e3),window.PineconeRouter.context.redirect("/login")):toastr.warning("Kode pesantren tidak tersedia")}))},forceKodePesantren:function(e){localStorage.setItem("forcekodepesantren",1),localStorage.setItem("kodepesantren",e),Alpine.store("member").kodePesantren=localStorage.getItem("kodepesantren"),window.PineconeRouter.context.navigate("/login")},enableButton:function(){""!=this.kode.trim()?this.buttonDisabled=!1:this.buttonDisabled=!0}}},window.member_login=function(){return{title:"Login",showPwd:!1,forceKodePesantren:!1,errorMessage:null,data:{username:"",password:"",logo:"",sitename:""},sanboxLogin:{},init:function(){var e;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),this.sandboxLogin=JSON.parse(null!==(e=Alpine.store("member").tarbiyyaSetting.sandbox_login)&&void 0!==e?e:"{}"),Object.keys(this.sandboxLogin).length>0&&(this.data.username=this.sandboxLogin.username,this.data.password=this.sandboxLogin.password),document.title=this.title,Alpine.store("member").currentPage="login",Alpine.store("member").showBottomMenu=!1,this.data.logo=Alpine.store("member").tarbiyyaSetting.auth_logo,this.data.sitename=Alpine.store("member").tarbiyyaSetting.site_title,this.forceKodePesantren=localStorage.getItem("forcekodepesantren")},login:function(){var e=this;this.errorMessage="";var t=new FormData;t.append("username",this.data.username),t.append("password",this.data.password),axios.post("/member/login",t,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){1==t.data.found?(localStorage.setItem("heroic_token",t.data.jwt),Alpine.store("member").sessionToken=localStorage.getItem("heroic_token"),window.PineconeRouter.context.navigate("/")):(e.errorMessage="Password tidak cocok atau akun belum terdaftar",setTimeout((function(){return e.errorMessage=""}),1e4))}))}}},window.member_page=function(e){return{title:"Detail Halaman",slug:e,notFound:!1,page:{},init:function(){var e,t=this;document.title=this.title,Alpine.store("member").currentPage="page",Alpine.store("member").showBottomMenu=!0;var n="api/member/page/".concat(this.slug);this.page=null!==(e=cachePageData[n])&&void 0!==e?e:{},0===Object.keys(this.page).length&&fetchPageData(n,{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){0==e.data.page.length?t.notFound=!0:(t.page=e.data.page,cachePageData[n]=t.page,t.title=t.page.title,document.title=t.page.title)}))}}},window.member_pengumuman=function(){return{title:"Pengumuman Pesantren",data:[],detailPengumuman:null,init:function(){var e=this;document.title=this.title,Alpine.store("member").currentPage="pengumuman",Alpine.store("member").showBottomMenu=!0,cachePageData["member/pengumuman"]?this.data=cachePageData["member/pengumuman"]:fetchPageData("api/member/pengumuman",{headers:{Authorization:"Bearer "+Alpine.store("member").sessionToken,"Pesantrenku-ID":Alpine.store("member").kodePesantren}}).then((function(t){cachePageData["member/pengumuman"]=t,e.data=t}))},showDetail:function(e){this.detailPengumuman=this.data.pengumuman[e]}}},window.member_profile=function(){return{title:"Profile",data:[],init:function(){var e=this;window.scrollTo({top:0,behavior:"auto"}),document.title=this.title,Alpine.store("member").currentPage="profile",Alpine.store("member").showBottomMenu=!0,cachePageData["member/profile"]?this.data=cachePageData["member/profile"]:fetchPageData("/api/member/profile",{headers:{Authorization:"Bearer "+Alpine.store("member").sessionToken,"Pesantrenku-ID":Alpine.store("member").kodePesantren}}).then((function(t){cachePageData["member/profile"]=t,e.data=t}))},logout:function(){localStorage.removeItem("heroic_token"),window.PineconeRouter.context.navigate("/login")}}},window.member_profile_delete=function(){return{title:"Hapus Akun",showPwd:!1,errorMessage:"",deleting:!1,data:{whatsapp:"",password:""},init:function(){document.title=this.title,Alpine.store("member").currentPage="profile_delete",Alpine.store("member").showBottomMenu=!0},removeAccount:function(){var e,t,n=this;if(this.errorMessage="",""!=this.data.whatsapp&&""!=this.data.password){var a=new FormData;a.append("whatsapp",null!==(e=this.data.whatsapp)&&void 0!==e?e:""),a.append("password",null!==(t=this.data.password)&&void 0!==t?t:""),axios.post("/pages/member/profile_delete",a,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren"),Authorization:"Bearer "+localStorage.getItem("heroic_token")}}).then((function(e){1==e.data.success?(localStorage.removeItem("heroic_token"),window.PineconeRouter.context.navigate("/login")):n.errorMessage=e.data.message}))}else this.errorMessage="Mohon lengkapi semua kolom"}}},window.member_profile_edit=function(){return{title:"Register",showPwd:!1,data:{fullname:"",email:"",whatsapp:"",password:"",repeat_password:""},errors:{fullname:"",email:"",whatsapp:"",password:"",repeat_password:""},init:function(){var e=this;document.title=this.title,Alpine.store("member").currentPage="register",Alpine.store("member").showBottomMenu=!1,cachePageData["member/register"]?this.data=cachePageData["member/register"]:fetchPageData("api/member/register",{headers:{"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){cachePageData["member/register"]=t,e.data=t}))},register:function(){var e,t,n,a,i,r=this;this.errors={fullname:"",email:"",whatsapp:"",password:"",repeat_password:""};var o=new FormData;o.append("fullname",null!==(e=this.data.fullname)&&void 0!==e?e:""),o.append("email",null!==(t=this.data.email)&&void 0!==t?t:""),o.append("whatsapp",null!==(n=this.data.whatsapp)&&void 0!==n?n:""),o.append("password",null!==(a=this.data.password)&&void 0!==a?a:""),o.append("repeat_password",null!==(i=this.data.repeat_password)&&void 0!==i?i:""),axios.post("/pages/member/register",o,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){if(1==e.data.success){var t=e.data.token+"_"+e.data.id+"X"+Math.random().toString(36).substring(7);window.PineconeRouter.context.navigate("/member/register_confirm/"+t)}else r.errors=e.data.errors}))}}},window.member_program_pesantren=function(){return{title:"Program Pesantren",data:[],detail:null,init:function(){var e=this;document.title=this.title,Alpine.store("member").currentPage="program_pesantren",Alpine.store("member").showBottomMenu=!0,cachePageData["member/program_pesantren"]?this.data=cachePageData["member/program_pesantren"]:fetchPageData("api/member/program_pesantren",{headers:{Authorization:"Bearer "+Alpine.store("member").sessionToken,"Pesantrenku-ID":Alpine.store("member").kodePesantren}}).then((function(t){cachePageData["member/program_pesantren"]=t,e.data=t}))},showDetail:function(e){this.detail=this.data.pages[e]}}},(()=>{function e(e,n){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=n){var a,i,r,o,s=[],l=!0,d=!1;try{if(r=(n=n.call(e)).next,0===t){if(Object(n)!==n)return;l=!1}else for(;!(l=(a=r.call(n)).done)&&(s.push(a.value),s.length!==t);l=!0);}catch(e){d=!0,i=e}finally{try{if(!l&&null!=n.return&&(o=n.return(),Object(o)!==o))return}finally{if(d)throw i}}return s}}(e,n)||function(e,n){if(e){if("string"==typeof e)return t(e,n);var a={}.toString.call(e).slice(8,-1);return"Object"===a&&e.constructor&&(a=e.constructor.name),"Map"===a||"Set"===a?Array.from(e):"Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)?t(e,n):void 0}}(e,n)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function t(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=Array(t);n<t;n++)a[n]=e[n];return a}window.member_register_confirm=function(){return{title:"Konfirmasi Registrasi",data:{logo:"",id:"",token:"",otp:""},resending:!1,error:"",remainingTime:30,interval:null,get formattedTime(){var e=Math.floor(this.remainingTime/60),t=this.remainingTime%60;return"".concat(String(e).padStart(2,"0"),":").concat(String(t).padStart(2,"0"))},startCountdown:function(){var e=this;this.interval=setInterval((function(){e.remainingTime>0?e.remainingTime-=1:clearInterval(e.interval)}),1e3)},init:function(){document.title=this.title,Alpine.store("member").currentPage="register_confirm",Alpine.store("member").showBottomMenu=!1,this.data.logo=Alpine.store("member").tarbiyyaSetting.auth_logo;var t=new URLSearchParams(window.location.search).get("token");/^[a-f0-9]+_[0-9]+X.+$/.test(t)||window.PineconeRouter.context.redirect("/member/register");var n=e(t.split("_"),2),a=n[0],i=e(n[1].split("X"),2),r=i[0];i[1],this.data.token=a,this.data.id=r,this.startCountdown()},confirm:function(){var e,t,n,a=this;if(""!=this.data.otp){var i=new FormData;i.append("id",null!==(e=this.data.id)&&void 0!==e?e:""),i.append("token",null!==(t=this.data.token)&&void 0!==t?t:""),i.append("otp",null!==(n=this.data.otp)&&void 0!==n?n:""),axios.post("/member/register/confirm",i,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){1==e.data.success?(localStorage.setItem("heroic_token",e.data.jwt),setTimeout((function(){Alpine.store("member").sessionToken=localStorage.getItem("heroic_token"),window.PineconeRouter.context.navigate("/")}))):a.error=e.data.message}))}else this.error="Kode Registrasi tidak boleh kosong."},resendOTP:function(){var e,t,n=this;this.resending=!0;var a=new FormData;a.append("id",null!==(e=this.data.id)&&void 0!==e?e:""),a.append("token",null!==(t=this.data.token)&&void 0!==t?t:""),axios.post("/pages/member/register_confirm?m=resend",a,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){if(1==e.data.success){var t=e.data.token+"_"+e.data.id+"X"+Math.random().toString(36).substring(7);window.PineconeRouter.context.navigate("/member/register/"+t)}else n.error=e.data.message,n.resending=!1}))}}}})(),window.member_register=function(){return{title:"Register",showPwd:!1,registering:!1,data:{fullname:"",whatsapp:"",password:"",repeat_password:"",logo:"",sitename:""},errors:{fullname:"",whatsapp:"",password:"",repeat_password:""},init:function(){document.title=this.title,Alpine.store("member").currentPage="register",Alpine.store("member").showBottomMenu=!1,this.data.logo=Alpine.store("member").tarbiyyaSetting.auth_logo,this.data.sitename=Alpine.store("member").tarbiyyaSetting.site_title},register:function(){var e,t,n,a,i=this;this.registering=!0,this.errors={fullname:"",whatsapp:"",password:"",repeat_password:""};var r=new FormData;r.append("fullname",null!==(e=this.data.fullname)&&void 0!==e?e:""),r.append("whatsapp",null!==(t=this.data.whatsapp)&&void 0!==t?t:""),r.append("password",null!==(n=this.data.password)&&void 0!==n?n:""),r.append("repeat_password",null!==(a=this.data.repeat_password)&&void 0!==a?a:""),axios.post("/api/member/register",r,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){if(1==e.data.success){var t=e.data.token+"_"+e.data.id+"X"+Math.random().toString(36).substring(7);window.PineconeRouter.context.navigate("/member/register/"+t)}else i.errors=e.data.errors,i.registering=!1}))}}},window.member_santri=function(){return{title:"Daftar Santri",data:[],searchNIS:null,NISFound:{found:null,token:null,nama_santri:null,class_name:null},detailSantriIndex:null,detailSantri:{nama_santri:null,nis:null,nik_santri:null,nisn:null,tempat_lahir_santri:null,jenis_kelamin:null,status_keluarga:null,anak_ke:null,jumlah_saudara_kandung:null,jumlah_saudara_tiri:null,jumlah_saudara_angkat:null,hobi:null,cita:null,asal_sekolah:null,npsn_sekolah:null,alamat_sekolah:null,nama_ayah:null,nik_ayah:null,tempat_lahir_ayah:null,kontak_ayah:null,pekerjaan_ayah:null,pendidikan_ayah:null,nama_ibu:null,nik_ibu:null,tempat_lahir_ibu:null,kontak_ibu:null,pekerjaan_ibu:null,pendidikan_ibu:null,penghasilan:null,alamat:null,rtrw:null,desa:null,kecamatan:null,kota:null,provinsi:null,kodepos:null,tahun_masuk:null,iuran_bulanan:null,presensi:{},total_sakit:0,total_izin:0,total_alpa:0},calendar:null,selectedDate:null,selectedPresensi:{},init:function(){var e=this;document.title=this.title,Alpine.store("member").currentPage="santri",Alpine.store("member").showBottomMenu=!0,cachePageData["member/santri"]?this.data=cachePageData["member/santri"]:fetchPageData("api/member/santri",{headers:{Authorization:"Bearer "+Alpine.store("member").sessionToken,"Pesantrenku-ID":Alpine.store("member").kodePesantren}}).then((function(t){cachePageData["member/santri"]=t,e.data=t}))},getTodayPresensi:function(e){var t=this.data.santri[e],n='<span class="text-secondary">Presensi belum dicek</span>';return this.data.isLibur?n='<span class="text-secondary">'.concat(this.data.isLibur," libur</span>"):"1"==t.presensi_hadir?n='<span class="text-secondary">Presensi hari ini: </span><span class="text-success">Hadir</span>':"1"==t.presensi_sakit?n='<span class="text-secondary">Presensi hari ini: </span><span class="text-warning">Sakit</span>':"1"==t.presensi_izin?n='<span class="text-secondary">Presensi hari ini: </span><span class="text-info">Izin</span>':"1"==t.presensi_alpa&&(n='<span class="text-secondary">Presensi hari ini: </span><span class="text-danger">Alpa</span>'),n},showDetail:function(e){this.detailSantriIndex=e,this.detailSantri=this.data.santri[e]},loadDetailPresensi:function(){var e=this;this.calendar=null;var t={settings:{visibility:{theme:"light",weekend:!1},lang:"id"},actions:{clickDay:function(t,n){e.getSelectedPresensi(n.selectedDates[0])}},popups:{"2024-10-28":{modifier:"bg-danger"}}};cachePageData["member/santri/".concat(this.detailSantriIndex,"/presensi")]?(this.detailSantri.presensi=cachePageData["member/santri/".concat(this.detailSantriIndex,"/presensi")],t.popups=this.setCalendarPopups(this.detailSantri.presensi),this.calendar=new VanillaCalendar("#calendar",t),this.calendar.init()):fetchPageData("api/member/santri?m=detailPresensi&student_id="+this.detailSantri.id,{headers:{Authorization:"Bearer "+Alpine.store("member").sessionToken,"Pesantrenku-ID":Alpine.store("member").kodePesantren}}).then((function(n){cachePageData["member/santri/".concat(e.detailSantriIndex,"/presensi")]=n.presensi,e.detailSantri.presensi=n.presensi,t.popups=e.setCalendarPopups(e.detailSantri.presensi),e.calendar=new VanillaCalendar("#calendar",t),e.calendar.init()}))},setCalendarPopups:function(e){var t={},n=0,a=0,i=0;return e?(Object.keys(e).forEach((function(r){var o=e[r],s=null;"1"===o.ill?(s="sakit",n++):"1"===o.permit?(s="izin",a++):"1"===o.noinfo&&(s="alpa",i++),s&&(t[r]={modifier:s})})),this.detailSantri.total_sakit=n,this.detailSantri.total_izin=a,this.detailSantri.total_alpa=i,t):t},getSelectedPresensi:function(e){this.selectedDate=e;var t,n=this.detailSantri.presensi[this.selectedDate],a=null;n?("1"===n.ill?(a="sakit",t="Sakit"):"1"===n.permit?(a="izin",t="Izin"):"1"===n.noinfo&&(a="alpa",t="Alpa"),this.selectedPresensi.date=currentDate,this.selectedPresensi.status=a,this.selectedPresensi.caption=t,this.selectedPresensi.description=null==n?void 0:n.description):this.selectedPresensi={}},checkNIS:function(){var e=this;this.searchNIS&&fetchPageData("api/member/santri/?m=checkNIS&nis="+this.searchNIS,{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){1!=t.found?e.NISFound.found=0:(e.NISFound=t,e.NISFound.found=1)}))},addSantri:function(){var e=this;postPageData("api/member/santri",{token:this.NISFound.token},{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){"success"==t.status&&(e.data.santri.push(t.santri),e.searchNIS=null,e.NISFound={found:null,token:null,nama_santri:null,class_name:null},bootstrap.Modal.getInstance(Array.from(window.modalElements).find((function(e){return"addSantriModal"===e.id}))).hide())}))}}},document.addEventListener("alpine:init",(function(){NProgress.configure({showSpinner:!1}),window.PineconeRouter.settings.basePath="/member",document.addEventListener("pinecone-start",(function(){NProgress.start(),Alpine.store("member").pageLoaded=!1})),document.addEventListener("pinecone-end",(function(){NProgress.done(),Alpine.store("member").pageLoaded=!0})),document.addEventListener("fetch-error",(function(e){})),Alpine.store("member",{currentPage:"home",pageLoaded:!1,showBottomMenu:!0,sessionToken:null,kodePesantren:null,tarbiyyaSetting:{}}),window.member=function(){return{init:function(){document.title=this.title,Alpine.store("member").kodePesantren=localStorage.getItem("kodepesantren"),Alpine.store("member").sessionToken=localStorage.getItem("heroic_token"),Alpine.store("member").kodePesantren&&Object.keys(Alpine.store("member").tarbiyyaSetting).length<1&&fetchPageData("api/member",{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){Alpine.store("member").tarbiyyaSetting=e.tarbiyyaSetting}))},isKodePesantrenSet:function(e){if(null==Alpine.store("member").kodePesantren)return e.redirect("/kodepesantren")},isLoggedIn:function(e){if(null==Alpine.store("member").sessionToken)return e.redirect("/login")}}}})),window.animatedScroll=function(){var e=document.querySelector(".appHeader.scrolled");window.scrollY>20?e.classList.add("is-active"):e.classList.remove("is-active")},window.openOffcanvas=null,window.openModal=null,window.historyStateAdded=!1,document.addEventListener("pinecone-end",(function(){var e=document.querySelector(".appHeader.scrolled");function t(e){window.openOffcanvas?(bootstrap.Offcanvas.getInstance(window.openOffcanvas).hide(),window.historyStateAdded=!1,window.openOffcanvas=null):window.openModal&&(bootstrap.Modal.getInstance(window.openModal).hide(),historyStateAdded=!1,window.openModal=null)}document.body.contains(e)&&(animatedScroll(),document.addEventListener("scroll",(function(){animatedScroll()}))),window.offcanvasElements=document.querySelectorAll(".offcanvas"),window.modalElements=document.querySelectorAll(".modal"),offcanvasElements.forEach((function(e){bootstrap.Offcanvas.getOrCreateInstance(e),e.addEventListener("shown.bs.offcanvas",(function(){window.historyStateAdded||(history.pushState({offcanvasOpen:!0},""),window.historyStateAdded=!0),window.openOffcanvas=e,window.addEventListener("popstate",t)})),e.addEventListener("hidden.bs.offcanvas",(function(){window.removeEventListener("popstate",t),window.historyStateAdded&&(history.back(),window.historyStateAdded=!1),window.openOffcanvas=null}))})),modalElements.forEach((function(e){bootstrap.Modal.getOrCreateInstance(e),e.addEventListener("shown.bs.modal",(function(){window.historyStateAdded||(history.pushState({modalOpen:!0},""),window.historyStateAdded=!0),window.openModal=e,window.addEventListener("popstate",t)})),e.addEventListener("hidden.bs.modal",(function(){window.removeEventListener("popstate",t),window.historyStateAdded&&(history.back(),window.historyStateAdded=!1),window.openModal=null}))}))})),window.member_tagihan=function(){return{title:"Profile",data:[],init:function(){var e=this;document.title=this.title,Alpine.store("member").currentPage="info",Alpine.store("member").showBottomMenu=!0,cachePageData["member/info"]?this.data=cachePageData["member/info"]:fetchPageData("member/info").then((function(t){cachePageData["member/info"]=t,e.data=t}))}}},window.member_videos_detail=function(e){return{title:"Detail Video",id:e,notFound:!1,video:{},youtubeEmbedURL:null,player:null,init:function(){var e,t,n=this;window.scrollTo({top:0,behavior:"auto"}),document.title=this.title,Alpine.store("member").currentPage="video",Alpine.store("member").showBottomMenu=!0,this.video=null!==(e=null===(t=cachePageData["member/videos"])||void 0===t?void 0:t.videos.filter((function(e){return e.id==n.id})))&&void 0!==e?e:{},0==Object.keys(this.video).length&&fetchPageData("api/member/videos/detail/".concat(this.id),{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){0==e.data.video.length?n.notFound=!0:n.video=e.data.video}))},formatDate:function(e){if(e&&"0000-00-00"!=e){var t=new Date(e);return new Intl.DateTimeFormat("id-ID",{day:"numeric",month:"long",year:"numeric"}).format(t)}return""},initPlyr:function(){this.player=new Plyr("#player",{controls:["play-large","play","progress","current-time","duration","mute","volume","settings","fullscreen"],settings:["captions","quality","speed"],youtube:{noCookie:!0,rel:0,modestbranding:1}})}}},window.member_videos=function(){return{title:"Video Pesantren",videos:[],nextPage:1,empty:!1,init:function(){var e,t,n,a=this;document.title=this.title,Alpine.store("member").currentPage="videos",Alpine.store("member").showBottomMenu=!0,(null===(e=cachePageData["member/videos"])||void 0===e?void 0:e.videos.length)>0?(cachePageData["member/videos"].videos.forEach((function(e){a.videos.push(e)})),this.nextPage=null!==(t=cachePageData["member/videos"].nextPage)&&void 0!==t?t:1,this.empty=null!==(n=cachePageData["member/videos"].empty)&&void 0!==n&&n):(cachePageData["member/videos"]={},this.loadVideos())},formatDate:function(e){if(e&&"0000-00-00"!=e){var t=new Date(e);return new Intl.DateTimeFormat("id-ID",{day:"numeric",month:"long",year:"numeric"}).format(t)}return""},loadMore:function(){this.loadVideos()},loadVideos:function(){var e=this;fetchPageData("api/member/videos?page=".concat(this.nextPage),{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){0==t.data.videos.length?(e.empty=!0,cachePageData["member/videos"].empty=!0):(t.data.videos.forEach((function(t){e.videos.push(t)})),e.nextPage++,cachePageData["member/videos"].videos=e.videos,cachePageData["member/videos"].nextPage=e.nextPage)}))},showDetail:function(e){window.PineconeRouter.context.navigate("/videos/".concat(this.videos[e].id))},stripIntro:function(e,t,n){var a=t.split(" ");return a.length>e?a.slice(0,e).join(" ")+'... <a href="javascript:void()" x-on:click="showDetail('.concat(n,')">Selengkapnya</a>'):t}}};
//# sourceMappingURL=pagescript.js.map