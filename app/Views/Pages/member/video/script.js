window.member_video = function(id){
    return {
        title: "Detail Info",
        id: id,
        notFound: false,
        video: {},
        youtubeEmbedURL: null,
        player: null,
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'video'
            Alpine.store('member').showBottomMenu = true

            // Get cache if exists
            this.video = cachePageData[`member/videos`]?.videos.filter(item => item.id == this.id) ?? {};
            if(Object.keys(this.video).length == 0) {
                fetchPageData(`pages/member/video/${this.id}`, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                    }
                })
                .then(data => {
                    if(data.data.video.length == 0){
                        this.notFound = true
                    } else {
                        this.video = data.data.video
                    }
                })
            }
        },
        formatDate(dateString){
            if(dateString && dateString != '0000-00-00') {
                const date = new Date(dateString);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return new Intl.DateTimeFormat('id-ID', options).format(date);
            }
            return '';
        },
        initPlyr() {
            this.player = new Plyr("#player", {
                // Pengaturan tambahan (opsional)
                controls: [
                    "play-large", // Tombol play di tengah
                    "play", // Tombol play/pause
                    "progress", // Baris progress
                    "current-time", // Waktu saat ini
                    "duration", // Total durasi
                    "mute", // Tombol mute/unmute
                    "volume", // Kontrol volume
                    "settings", // Pengaturan
                    "fullscreen", // Tombol fullscreen
                ],
                settings: ["captions", "quality", "speed"], // Opsi pengaturan
                youtube: {
                    noCookie: true, // Menggunakan YouTube domain tanpa cookie
                    rel: 0, // Menghilangkan video terkait saat video berakhir
                    modestbranding: 1, // Mengurangi branding YouTube
                },
            });
        }
    }
}
