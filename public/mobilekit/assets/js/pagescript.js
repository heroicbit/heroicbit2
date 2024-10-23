/*! For license information please see pagescript.js.LICENSE.txt */
window.member_feed=function(e){return{title:"Detail Info",id:e,notFound:!1,feed:{},init:function(){var e,t,n=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="feed",Alpine.store("member").showBottomMenu=!0,this.feed=null!==(e=null===(t=cachePageData["member/feeds"])||void 0===t?void 0:t.feeds.filter((function(e){return e.id==n.id})))&&void 0!==e?e:{},0==Object.keys(this.feed).length&&fetchPageData("pages/member/feed/".concat(this.id),{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){0==e.data.post.length?n.notFound=!0:n.feed=e.data.post}))},formatDate:function(e){if(e&&"0000-00-00"!=e){var t=new Date(e);return new Intl.DateTimeFormat("id-ID",{day:"numeric",month:"long",year:"numeric"}).format(t)}return""}}},window.member_feeds=function(){return{title:"Info Pesantren",feeds:[],nextPage:1,empty:!1,init:function(){var e,t,n,a=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="feeds",Alpine.store("member").showBottomMenu=!0,(null===(e=cachePageData["member/feeds"])||void 0===e?void 0:e.feeds.length)>0?(cachePageData["member/feeds"].feeds.forEach((function(e){a.feeds.push(e)})),this.nextPage=null!==(t=cachePageData["member/feeds"].nextPage)&&void 0!==t?t:1,this.empty=null!==(n=cachePageData["member/feeds"].empty)&&void 0!==n&&n):(cachePageData["member/feeds"]={},this.loadFeeds())},formatDate:function(e){if(e&&"0000-00-00"!=e){var t=new Date(e);return new Intl.DateTimeFormat("id-ID",{day:"numeric",month:"long",year:"numeric"}).format(t)}return""},loadMore:function(){this.loadFeeds()},loadFeeds:function(){var e=this;fetchPageData("pages/member/feeds?page=".concat(this.nextPage),{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){0==t.data.posts.length?(e.empty=!0,cachePageData["member/feeds"].empty=!0):(t.data.posts.forEach((function(t){e.feeds.push(t)})),e.nextPage++,cachePageData["member/feeds"].feeds=e.feeds,cachePageData["member/feeds"].nextPage=e.nextPage)}))},showDetail:function(e){window.PineconeRouter.context.navigate("/feed/".concat(this.feeds[e].id))},stripIntro:function(e,t,n){var a=t.split(" ");return a.length>e?a.slice(0,e).join(" ")+'... <a href="javascript:void()" x-on:click="showDetail('.concat(n,')">Selengkapnya</a>'):t}}},window.member_home=function(){return{title:"Beranda",data:[],init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="home",Alpine.store("member").showBottomMenu=!0,cachePageData["member/home"]?this.data=cachePageData["member/home"]:fetchPageData("pages/member/home",{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){cachePageData["member/home"]=t,e.data=t}))},initSwiperArticles:function(){new Swiper(".swiper-article",{slidesPerView:1.6,spaceBetween:10,slidesOffsetBefore:15,slidesOffsetAfter:20,autoplay:{delay:5e3,pauseOnMouseEnter:!0},breakpoints:{640:{slidesPerView:2.8,spaceBetween:20}}})}}},window.member_intro=function(){return{title:"Intro",swiper:null,init:function(){document.title=this.title,Alpine.store("member").currentPage="intro",Alpine.store("member").showBottomMenu=!1,this.swiper=new Swiper(".swiper-intro",{slidesPerView:1,spaceBetween:20,autoplay:{delay:5e3,pauseOnMouseEnter:!0},pagination:{el:".swiper-pagination"}})},gotoLogin:function(){localStorage.setItem("intro",1),window.PineconeRouter.context.navigate("/login")}}},window.member_kodepesantren=function(){return{title:"Kode Pesantren",buttonDisabled:!0,kode:null,init:function(){document.title=this.title,Alpine.store("member").currentPage="kodepesantren",Alpine.store("member").showBottomMenu=!1},checkKodePesantren:function(){var e=new FormData;e.append("kodepesantren",this.kode),axios.post("/pages/member/kodepesantren",e,{headers:{"Content-Type":"multipart/form-data"}}).then((function(e){1==e.data.found?(localStorage.setItem("kodepesantren",e.data.pesantrenID),Alpine.store("member").kodePesantren=localStorage.getItem("kodepesantren"),setCookie("kodepesantren",e.data.pesantrenID,1e3),window.PineconeRouter.context.navigate("/login")):toastr.warning("Kode pesantren tidak tersedia")}))},enableButton:function(){""!=this.kode.trim()?this.buttonDisabled=!1:this.buttonDisabled=!0}}},window.member_login=function(){return{title:"Login",showPwd:!1,data:[],init:function(){document.title=this.title,Alpine.store("member").currentPage="login",Alpine.store("member").showBottomMenu=!1},login:function(){var e=new FormData;e.append("username",this.data.username),e.append("password",this.data.password),axios.post("/pages/member/login",e,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){1==e.data.found?(localStorage.setItem("heroic_token",e.data.jwt),Alpine.store("member").sessionToken=localStorage.getItem("heroic_token"),window.PineconeRouter.context.navigate("/")):toastr.warning("Username atau password salah")}))}}},window.member_page=function(e){return{title:"Detail Halaman",slug:e,notFound:!1,page:{},init:function(){var e,t=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="page",Alpine.store("member").showBottomMenu=!0;var n="pages/member/page/".concat(this.slug);this.page=null!==(e=cachePageData[n])&&void 0!==e?e:{},0===Object.keys(this.page).length&&fetchPageData(n,{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){0==e.data.page.length?t.notFound=!0:(t.page=e.data.page,cachePageData[n]=t.page,t.title=t.page.title,document.title=t.page.title)}))}}},window.member_pengumuman=function(){return{title:"Pengumuman Pesantren",data:[],detailFeed:null,init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="pengumuman",Alpine.store("member").showBottomMenu=!0,cachePageData["member/pengumuman"]?this.data=cachePageData["member/pengumuman"]:fetchPageData("member/pengumuman").then((function(t){cachePageData["member/pengumuman"]=t,e.data=t}))},showDetail:function(e){this.detailFeed=this.data.posts[e]}}},window.member_profile=function(){return{title:"Profile",data:[],init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="info",Alpine.store("member").showBottomMenu=!0,cachePageData["member/info"]?this.data=cachePageData["member/info"]:fetchPageData("member/info").then((function(t){cachePageData["member/info"]=t,e.data=t}))},logout:function(){localStorage.removeItem("heroic_token"),window.PineconeRouter.context.navigate("/login")}}},window.member_register=function(){return{title:"Register",showPwd:!1,data:{name:"",email:"",whatsapp:"",password:"",repeat_password:""},init:function(){document.title=this.title,Alpine.store("member").currentPage="register",Alpine.store("member").showBottomMenu=!1},register:function(){var e=new FormData;e.append("fullname",this.data.name),e.append("email",this.data.email),e.append("whatsapp",this.data.whatsapp),e.append("password",this.data.password),e.append("repeat_password",this.data.repeat_password),axios.post("/member/register",e,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){1==e.data.success?(localStorage.setItem("token",e.data.jwt),window.PineconeRouter.context.navigate("/")):toastr.warning(e.data.message)}))}}},window.member_santri=function(){return{title:"Daftar Santri",data:[],searchNIS:null,NISFound:{found:null,token:null,nama_santri:null,class_name:null},detailSantri:{nama_santri:null,nis:null,nik_santri:null,nisn:null,tempat_lahir_santri:null,jenis_kelamin:null,status_keluarga:null,anak_ke:null,jumlah_saudara_kandung:null,jumlah_saudara_tiri:null,jumlah_saudara_angkat:null,hobi:null,cita:null,asal_sekolah:null,npsn_sekolah:null,alamat_sekolah:null,nama_ayah:null,nik_ayah:null,tempat_lahir_ayah:null,kontak_ayah:null,pekerjaan_ayah:null,pendidikan_ayah:null,nama_ibu:null,nik_ibu:null,tempat_lahir_ibu:null,kontak_ibu:null,pekerjaan_ibu:null,pendidikan_ibu:null,penghasilan:null,alamat:null,rtrw:null,desa:null,kecamatan:null,kota:null,provinsi:null,kodepos:null,tahun_masuk:null,iuran_bulanan:null},init:function(){var e=this;document.title=this.title,Alpine.store("member").currentPage="santri",Alpine.store("member").showBottomMenu=!0,cachePageData["member/santri"]?this.data=cachePageData["member/santri"]:fetchPageData("pages/member/santri",{headers:{Authorization:"Bearer "+Alpine.store("member").sessionToken,"Pesantrenku-ID":Alpine.store("member").kodePesantren}}).then((function(t){cachePageData["member/santri"]=t,e.data=t}))},showDetail:function(e){this.detailSantri=this.data.santri[e]},formatDate:function(e){if(e&&"0000-00-00"!=e){var t=new Date(e);return new Intl.DateTimeFormat("id-ID",{day:"numeric",month:"long",year:"numeric"}).format(t)}return""},checkNIS:function(){var e=this;this.searchNIS&&fetchPageData("pages/member/santri/?m=checkNIS&nis="+this.searchNIS,{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){1!=t.found?e.NISFound.found=0:(e.NISFound=t,e.NISFound.found=1)}))},addSantri:function(){var e=this;postPageData("pages/member/santri",{token:this.NISFound.token},{headers:{Authorization:"Bearer "+localStorage.getItem("heroic_token"),"Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(t){"success"==t.status&&(e.data.santri.push(t.santri),e.searchNIS=null,e.NISFound={found:null,token:null,nama_santri:null,class_name:null},bootstrap.Modal.getInstance(Array.from(window.modalElements).find((function(e){return"addSantriModal"===e.id}))).hide())}))}}},document.addEventListener("alpine:init",(function(){NProgress.configure({showSpinner:!1}),window.PineconeRouter.settings.basePath="/member",document.addEventListener("pinecone-start",(function(){NProgress.start(),Alpine.store("member").pageLoaded=!1})),document.addEventListener("pinecone-end",(function(){NProgress.done(),Alpine.store("member").pageLoaded=!0})),document.addEventListener("fetch-error",(function(e){})),Alpine.store("member",{currentPage:"home",pageLoaded:!1,showBottomMenu:!0,sessionToken:null,kodePesantren:null}),Alpine.data("member",(function(){return{init:function(){document.title=this.title,Alpine.store("member").kodePesantren=localStorage.getItem("kodepesantren"),Alpine.store("member").sessionToken=localStorage.getItem("heroic_token")},isKodePesantrenSet:function(e){if(null==Alpine.store("member").kodePesantren)return e.redirect("/kodepesantren")},isLoggedIn:function(e){if(null==Alpine.store("member").sessionToken)return e.redirect("/login")}}}))})),window.updateActiveBottomMenu=function(){var e=window.location.hash.replace(/^#\/?/,"");document.querySelectorAll('[id^="bottommenu-"]').forEach((function(e){return e.classList.remove("active")})),""==e&&(e="member");var t=document.getElementById("bottommenu-"+e);t&&t.classList.add("active")},window.animatedScroll=function(){var e=document.querySelector(".appHeader.scrolled");window.scrollY>20?e.classList.add("is-active"):e.classList.remove("is-active")},window.openOffcanvas=null,window.openModal=null,window.historyStateAdded=!1,document.addEventListener("pinecone-end",(function(){updateActiveBottomMenu();var e=document.querySelector(".appHeader.scrolled");function t(e){window.openOffcanvas?(bootstrap.Offcanvas.getInstance(window.openOffcanvas).hide(),window.historyStateAdded=!1,window.openOffcanvas=null):window.openModal&&(bootstrap.Modal.getInstance(window.openModal).hide(),historyStateAdded=!1,window.openModal=null)}document.body.contains(e)&&(animatedScroll(),document.addEventListener("scroll",(function(){animatedScroll()}))),window.offcanvasElements=document.querySelectorAll(".offcanvas"),window.modalElements=document.querySelectorAll(".modal"),offcanvasElements.forEach((function(e){bootstrap.Offcanvas.getOrCreateInstance(e),e.addEventListener("shown.bs.offcanvas",(function(){window.historyStateAdded||(history.pushState({offcanvasOpen:!0},""),window.historyStateAdded=!0),window.openOffcanvas=e,window.addEventListener("popstate",t)})),e.addEventListener("hidden.bs.offcanvas",(function(){window.removeEventListener("popstate",t),window.historyStateAdded&&(history.back(),window.historyStateAdded=!1),window.openOffcanvas=null}))})),modalElements.forEach((function(e){bootstrap.Modal.getOrCreateInstance(e),e.addEventListener("shown.bs.modal",(function(){window.historyStateAdded||(history.pushState({modalOpen:!0},""),window.historyStateAdded=!0),window.openModal=e,window.addEventListener("popstate",t)})),e.addEventListener("hidden.bs.modal",(function(){window.removeEventListener("popstate",t),window.historyStateAdded&&(history.back(),window.historyStateAdded=!1),window.openModal=null}))}))})),window.member_tagihan=function(){return{title:"Profile",data:[],init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="info",Alpine.store("member").showBottomMenu=!0,cachePageData["member/info"]?this.data=cachePageData["member/info"]:fetchPageData("member/info").then((function(t){cachePageData["member/info"]=t,e.data=t}))}}};
//# sourceMappingURL=pagescript.js.map