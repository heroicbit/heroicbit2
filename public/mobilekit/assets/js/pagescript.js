/*! For license information please see pagescript.js.LICENSE.txt */
window.member_feed=function(){return{title:"Info Pesantren",data:[],detailFeed:null,init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="feed",Alpine.store("member").showBottomMenu=!0,cachePageData["member/feed"]?this.data=cachePageData["member/feed"]:fetchPageData("member/feed").then((function(t){cachePageData["member/feed"]=t,e.data=t}))},showDetail:function(e){this.detailFeed=this.data.articles[e]}}},window.member_home=function(){return{title:"Beranda",data:[],init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="home",Alpine.store("member").showBottomMenu=!0,cachePageData["member/home"]?this.data=cachePageData["member/home"]:fetchPageData("member/home").then((function(t){cachePageData["member/home"]=t,e.data=t}))}}},window.member_intro=function(){return{title:"Intro",swiper:null,init:function(){document.title=this.title,Alpine.store("member").currentPage="intro",Alpine.store("member").showBottomMenu=!1,this.swiper=new Swiper(".swiper-intro",{slidesPerView:1,spaceBetween:20,autoplay:{delay:5e3,pauseOnMouseEnter:!0},pagination:{el:".swiper-pagination"}})},gotoLogin:function(){localStorage.setItem("intro",1),window.PineconeRouter.context.navigate("/login")}}},window.member_kodepesantren=function(){return{title:"Kode Pesantren",buttonDisabled:!0,kode:null,init:function(){document.title=this.title,Alpine.store("member").currentPage="kodepesantren",Alpine.store("member").showBottomMenu=!1},checkKodePesantren:function(){var e=new FormData;e.append("kodepesantren",this.kode),axios.post("/member/kodepesantren",e,{headers:{"Content-Type":"multipart/form-data"}}).then((function(e){1==e.data.found?(localStorage.setItem("kodepesantren",e.data.pesantrenID),setCookie("kodepesantren",e.data.pesantrenID,1e3),window.PineconeRouter.context.navigate("/login")):toastr.warning("Kode pesantren tidak tersedia")}))},enableButton:function(){""!=this.kode.trim()?this.buttonDisabled=!1:this.buttonDisabled=!0}}},window.member_login=function(){return{title:"Login",showPwd:!1,data:[],init:function(){document.title=this.title,Alpine.store("member").currentPage="login",Alpine.store("member").showBottomMenu=!1},login:function(){var e=new FormData;e.append("username",this.data.username),e.append("password",this.data.password),axios.post("/member/login",e,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){1==e.data.found?(localStorage.setItem("token",e.data.jwt),window.PineconeRouter.context.navigate("/")):toastr.warning("Username atau password salah")}))}}},window.member_pengumuman=function(){return{title:"Pengumuman Pesantren",data:[],detailFeed:null,init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="pengumuman",Alpine.store("member").showBottomMenu=!0,cachePageData["member/pengumuman"]?this.data=cachePageData["member/pengumuman"]:fetchPageData("member/pengumuman").then((function(t){cachePageData["member/pengumuman"]=t,e.data=t}))},showDetail:function(e){this.detailFeed=this.data.posts[e]}}},window.member_profile=function(){return{title:"Profile",data:[],init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="info",Alpine.store("member").showBottomMenu=!0,cachePageData["member/info"]?this.data=cachePageData["member/info"]:fetchPageData("member/info").then((function(t){cachePageData["member/info"]=t,e.data=t}))}}},window.member_register=function(){return{title:"Register",showPwd:!1,data:{name:"",email:"",whatsapp:"",password:"",repeat_password:""},init:function(){document.title=this.title,Alpine.store("member").currentPage="register",Alpine.store("member").showBottomMenu=!1},register:function(){var e=new FormData;e.append("fullname",this.data.name),e.append("email",this.data.email),e.append("whatsapp",this.data.whatsapp),e.append("password",this.data.password),e.append("repeat_password",this.data.repeat_password),axios.post("/member/register",e,{headers:{"Content-Type":"multipart/form-data","Pesantrenku-ID":localStorage.getItem("kodepesantren")}}).then((function(e){1==e.data.success?(localStorage.setItem("token",e.data.jwt),window.PineconeRouter.context.navigate("/")):toastr.warning(e.data.message)}))}}},window.member_santri=function(){return{title:"Santri",data:[],init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="info",Alpine.store("member").showBottomMenu=!0,cachePageData["member/info"]?this.data=cachePageData["member/info"]:fetchPageData("member/info").then((function(t){cachePageData["member/info"]=t,e.data=t}))}}},Alpine.store("member",{currentPage:"home",pageLoaded:!1,showBottomMenu:!0}),window.member=function(){return{title:"Member Dashboard",sessionToken:null,kodePesantren:null,init:function(){document.title=this.title},isKodePesantrenSet:function(e){if(this.kodePesantren=localStorage.getItem("kodepesantren"),null==this.kodePesantren)return e.redirect("/kodepesantren")},isLoggedIn:function(e){if(this.sessionToken=localStorage.getItem("token"),null==this.sessionToken)return e.redirect("/login")}}},window.updateActiveBottomMenu=function(){var e=window.location.hash.replace(/^#\/?/,"");document.querySelectorAll('[id^="bottommenu-"]').forEach((function(e){return e.classList.remove("active")})),""==e&&(e="member");var t=document.getElementById("bottommenu-"+e);t&&t.classList.add("active")},window.animatedScroll=function(){var e=document.querySelector(".appHeader.scrolled");window.scrollY>20?e.classList.add("is-active"):e.classList.remove("is-active")},document.addEventListener("pinecone-end",(function(){updateActiveBottomMenu();var e=document.querySelector(".appHeader.scrolled");document.body.contains(e)&&(animatedScroll(),document.addEventListener("scroll",(function(){animatedScroll()})))})),window.member_tagihan=function(){return{title:"Profile",data:[],init:function(){var e=this;1!=localStorage.getItem("intro")&&window.PineconeRouter.context.navigate("/intro"),document.title=this.title,Alpine.store("member").currentPage="info",Alpine.store("member").showBottomMenu=!0,cachePageData["member/info"]?this.data=cachePageData["member/info"]:fetchPageData("member/info").then((function(t){cachePageData["member/info"]=t,e.data=t}))}}};
//# sourceMappingURL=pagescript.js.map