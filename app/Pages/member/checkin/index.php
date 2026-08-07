<div id="member_checkin" x-data="member_checkin()" x-cloak>

    <style>
        :root {
            --bg-page: #E8F2F4;
            --bg-surface: #ffffff;
            --bg-surface-2: #F0F6F7;
            --ink: #14303A;
            --ink-soft: #5B7A82;
            --ink-faint: #92AAB0;
            --primary: #3BC0CF;
            --primary-soft: #D4F0F3;
            --green: #2FA96A;
            --brass: #157CA1;
            --brass-soft: #CEE6F0;
            --brass-ink: #0D5370;
            --rust: #D94F4F;
            --rust-soft: #F5DEDE;
            --line: #D4E4E8;
            --radius-lg: 24px;
            --radius-md: 16px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg-page);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        [x-cloak] {
            display: none !important;
        }

        .app-shell {
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg-surface-2);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        @media (min-width:640px) {
            body {
                padding: 32px 16px;
            }

            .app-shell {
                min-height: calc(100vh - 64px);
                border-radius: 32px;
                box-shadow: 0 40px 80px -30px rgba(20, 32, 25, 0.35), 0 0 0 1px rgba(20, 32, 25, 0.05);
                overflow: hidden;
            }
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: calc(env(safe-area-inset-top, 0px) + 16px) 20px 12px;
            flex-shrink: 0;
        }

        .topbar .brand {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .topbar .brand img {
            border-radius: 10px;
            flex-shrink: 0;
        }

        .topbar .brand-mark {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            flex-shrink: 0;
        }

        .topbar .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 18px;
            font-weight: 600;
        }

        .topbar .brand-sub {
            font-size: 14px;
            color: var(--ink-faint);
        }

        .content {
            flex: 1;
            overflow-y: auto;
            padding: 4px 20px 16px;
        }

        .tabs {
            display: flex;
            gap: 6px;
            margin: 6px 0 18px;
            background: var(--bg-surface);
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 4px;
        }

        .tabs button {
            flex: 1;
            padding: 9px;
            border: none;
            background: none;
            border-radius: 999px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--ink-faint);
            cursor: pointer;
        }

        .tabs button.active {
            background: var(--primary);
            color: #fff;
        }

        .greeting .eyebrow {
            font-size: 12.5px;
            color: var(--ink-soft);
            font-weight: 500;
        }

        .greeting h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px;
            margin: 2px 0 0;
            font-weight: 600;
        }

        .greeting .role {
            font-size: 12px;
            color: var(--ink-faint);
            margin-top: 2px;
        }

        .clock-card {
            background: var(--primary);
            color: #fff;
            border-radius: var(--radius-lg);
            padding: 20px 20px 18px;
            margin: 16px 0;
            position: relative;
            overflow: hidden;
        }

        .clock-card::after {
            content: "";
            position: absolute;
            right: -30px;
            top: -30px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .clock-card .date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
        }

        .clock-card .time {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 40px;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            margin-top: 2px;
            line-height: 35px;
        }

        .clock-card .time span {
            opacity: .95;
            font-size: 24px;
        }

        .card {
            background: var(--bg-surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 18px;
            margin-bottom: 14px;
        }

        .map-container {
            width: 100%;
            height: 220px;
            border-radius: var(--radius-md);
            margin: 12px 0 6px;
            overflow: hidden;
            border: 1px solid var(--line);
        }

        .map-container .leaflet-control-attribution {
            font-size: 9px;
        }

        .geo-top {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .radar {
            width: 64px;
            height: 64px;
            flex-shrink: 0;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .radar .ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 1.5px solid var(--primary);
            opacity: 0;
        }

        .radar.active .ring {
            animation: pulse 2.4s ease-out infinite;
        }

        .radar.inactive .ring {
            border-color: var(--ink-faint);
            animation: none;
            opacity: 0;
        }

        .radar .ring:nth-child(2) {
            animation-delay: 0.8s;
        }

        .radar .ring:nth-child(3) {
            animation-delay: 1.6s;
        }

        @keyframes pulse {
            0% {
                transform: scale(.4);
                opacity: .55;
            }

            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        .radar .core {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-soft);
            color: var(--primary);
            z-index: 2;
        }

        .radar.inactive .core {
            background: var(--rust-soft);
            color: var(--rust);
        }

        .radar.pending .core {
            background: var(--bg-surface-2);
            color: var(--ink-faint);
        }

        .geo-status {
            flex: 1;
            min-width: 0;
        }

        .geo-status .label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14.5px;
            font-weight: 600;
        }

        .geo-status .label.active {
            color: var(--primary);
        }

        .geo-status .label.inactive {
            color: var(--rust);
        }

        .geo-status .label.pending {
            color: var(--ink-faint);
        }

        .geo-status .distance {
            font-size: 12px;
            color: var(--ink-soft);
            margin-top: 3px;
        }

        .geo-status .distance b {
            color: var(--ink);
            font-weight: 700;
        }

        .geo-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed var(--line);
            font-size: 11.5px;
            color: var(--ink-faint);
        }

        .accuracy-warn {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            background: var(--brass-soft);
            color: var(--brass-ink);
            border-radius: var(--radius-sm);
            padding: 9px 11px;
            margin-top: 12px;
            font-size: 11.5px;
            line-height: 1.5;
        }

        .error-box {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: var(--rust-soft);
            color: var(--rust);
            border-radius: var(--radius-md);
            padding: 13px 14px;
            margin-bottom: 14px;
            font-size: 12.5px;
            line-height: 1.55;
        }

        .error-box button {
            margin-top: 8px;
            background: var(--rust);
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
        }

        .cta-btn {
            width: 100%;
            padding: 16px;
            border-radius: 999px;
            border: none;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform .15s ease, opacity .15s ease;
        }

        .cta-btn:active {
            transform: scale(.98);
        }

        .cta-btn.enabled {
            background: var(--brass);
            color: #fff;
        }

        .cta-btn.done {
            background: var(--green);
            color: #fff;
            cursor: default;
            opacity: 1;
        }

        .cta-btn.disabled {
            background: var(--bg-surface);
            color: var(--ink-faint);
            border: 1.5px solid var(--line);
            cursor: not-allowed;
        }

        .cta-btn .spinner {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #fff;
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .cta-helper {
            text-align: center;
            font-size: 11.5px;
            color: var(--ink-faint);
            margin-top: 9px;
            padding: 0 8px;
            line-height: 1.5;
        }

        .cta-helper.warn {
            color: var(--rust);
        }

        .section-label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ink-soft);
            margin: 24px 0 10px 2px;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .today-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bg-surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 12px 14px;
            margin-bottom: 8px;
        }

        .today-item .dot {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--primary-soft);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .today-item .dot.out {
            background: var(--brass-soft);
            color: var(--brass-ink);
        }

        .today-item .dot.muted {
            background: var(--bg-surface-2);
            color: var(--ink-faint);
        }

        .today-item .info {
            flex: 1;
            min-width: 0;
        }

        .today-item .info .t1 {
            font-size: 13.5px;
            font-weight: 600;
        }

        .today-item .info .t2 {
            font-size: 11.5px;
            color: var(--ink-faint);
        }

        .today-item .badge {
            font-size: 10.5px;
            font-weight: 700;
            background: var(--brass-soft);
            color: var(--brass-ink);
            padding: 4px 9px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .today-item .badge.ontime {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .hist-summary {
            background: var(--bg-surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 16px;
            display: flex;
            margin: 10px 0 18px;
        }

        .hist-summary .col {
            flex: 1;
            text-align: center;
            border-right: 1px solid var(--line);
        }

        .hist-summary .col:last-child {
            border-right: none;
        }

        .hist-summary .col .num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
        }

        .hist-summary .col .lbl {
            font-size: 10.5px;
            color: var(--ink-faint);
            margin-top: 2px;
        }

        .hist-row {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bg-surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 12px 13px;
            margin-bottom: 8px;
        }

        .hist-row .icn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hist-row .icn.in {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .hist-row .icn.late {
            background: var(--rust-soft);
            color: var(--rust);
        }

        .hist-row .body {
            flex: 1;
            min-width: 0;
        }

        .hist-row .body .r1 {
            font-size: 13px;
            font-weight: 600;
        }

        .hist-row .body .r2 {
            font-size: 11px;
            color: var(--ink-faint);
            margin-top: 1px;
        }

        .hist-row .status {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            white-space: nowrap;
        }

        .hist-row .status.late {
            background: var(--rust-soft);
            color: var(--rust);
        }

        .skeleton {
            background: linear-gradient(90deg, var(--bg-surface) 25%, var(--bg-surface-2) 37%, var(--bg-surface) 63%);
            background-size: 400% 100%;
            animation: shimmer 1.4s ease infinite;
            border-radius: var(--radius-md);
        }

        @keyframes shimmer {
            0% {
                background-position: 100% 0;
            }

            100% {
                background-position: -100% 0;
            }
        }

        .skel-card {
            height: 100px;
            margin-bottom: 14px;
        }

        .skel-row {
            height: 56px;
            margin-bottom: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 30px 12px;
            color: var(--ink-faint);
            font-size: 12.5px;
        }

        .bottomnav {
            position: sticky;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-surface);
            border-top: 1px solid var(--line);
            display: flex;
            padding: 6px 12px calc(env(safe-area-inset-bottom, 0px) + 6px);
            z-index: 500;
            flex-shrink: 0;
        }

        .bottomnav .item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 7px 4px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: background .15s ease;
            text-decoration: none;
            color: var(--ink-faint);
            border: none;
            background: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 10.5px;
            font-weight: 600;
        }

        .bottomnav .item:active {
            background: var(--bg-surface-2);
        }

        .bottomnav .item.active {
            color: var(--primary);
        }

        .bottomnav .item svg {
            width: 22px;
            height: 22px;
        }

        .toast {
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            bottom: 90px;
            max-width: 440px;
            width: calc(100% - 40px);
            background: var(--primary);
            color: #fff;
            border-radius: var(--radius-md);
            padding: 13px 15px;
            display: flex;
            align-items: center;
            gap: 11px;
            box-shadow: 0 20px 40px -15px rgba(15, 30, 22, 0.5);
            z-index: 60;
        }

        .toast.error {
            background: var(--rust);
        }

        .toast .icn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast .t1 {
            font-size: 13px;
            font-weight: 700;
        }

        .toast .t2 {
            font-size: 11px;
            opacity: .85;
            margin-top: 1px;
        }

        /* Popup sukses overlay */
        .popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(20, 48, 58, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 24px;
        }

        .popup-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 28px 22px 22px;
            text-align: center;
            max-width: 340px;
            width: 100%;
            box-shadow: 0 24px 60px rgba(20, 48, 58, 0.3);
        }

        .popup-card .popup-icon {
            margin-bottom: 12px;
            display: flex;
            justify-content: center;
        }

        .popup-card .popup-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 6px;
        }

        .popup-card .popup-desc {
            font-size: 13.5px;
            color: var(--ink-soft);
            margin: 0 0 4px;
            line-height: 1.5;
        }

        .popup-card .popup-sub {
            font-size: 12px;
            color: var(--ink-faint);
            margin: 0 0 16px;
        }

        .popup-card .popup-btn {
            width: 100%;
            padding: 12px;
            border-radius: 999px;
            border: none;
            background: var(--primary);
            color: #fff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .popup-card .popup-btn:active {
            opacity: .85;
        }

        svg {
            display: block;
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="app-shell">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="brand">
                <img src="https://app.persis67benda.com/mobilekit/assets/img/icon/logo-67benda/72x72.png" width="36" height="36" alt="Logo Presensi Karyawan">
                <div>
                    <div class="brand-name">Presensi Karyawan</div>
                    <div class="brand-sub" x-text="officeLocation ? officeLocation.name : 'Memuat lokasi...'"></div>
                </div>
            </div>
            <div class="dropdown" x-data="{ open: false }" @click.outside="open = false">
                <button class="btn pe-0" type="button" @click="open = !open">
                    <i class="bi bi-window-stack"></i>
                </button>
                <ul class="card" x-show="open" style="width: 200px; list-style: none; position: absolute; right: 0px; padding: 10px; display: none;">
                    <li><a class="fs-6 d-flex align-items-center gap-2" href="/">
                        <i class="bi bi-speedometer2"></i> 
                        <span>Halaman Wali Santri</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <!-- TABS -->
            <div class="tabs">
                <button :class="tab==='home' && 'active'" @click="tab='home'">Beranda</button>
                <button :class="tab==='history' && 'active'" @click="switchToHistory()">Riwayat</button>
            </div>

            <!-- TAB: BERANDA -->
            <div x-show="tab==='home'">

                <!-- KARYAWAN TIDAK DITEMUKAN -->
                <div x-show="employeeNotFound" style="text-align:center;padding:40px 16px;">
                    <div style="margin-bottom:16px;">
                        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" style="display:inline-block;">
                            <circle cx="28" cy="28" r="28" fill="#F5DEDE"/>
                            <path d="M28 20v12M28 38h.01" stroke="#D94F4F" stroke-width="3" stroke-linecap="round"/>
                            <circle cx="28" cy="28" r="22" stroke="#D94F4F" stroke-width="2"/>
                        </svg>
                    </div>
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-size:18px;font-weight:700;color:var(--ink);margin:0 0 8px;">Karyawan Belum Terdaftar</h3>
                    <p style="font-size:13.5px;color:var(--ink-soft);margin:0;line-height:1.6;">
                        Akun Anda belum terdaftar sebagai karyawan atau jadwal presensi belum diatur.
                    </p>
                    <p style="font-size:12px;color:var(--ink-faint);margin:6px 0 0;">
                        Silakan hubungi admin atau operator pesantren untuk mendaftarkan Anda.
                    </p>
                </div>

                <!-- KONTEN UTAMA (jika karyawan ditemukan) -->
                <div x-show="!employeeNotFound">

                <div class="greeting">
                    <div class="eyebrow">Assalamu'alaikum,</div>
                    <h2 x-text="employee.name || 'Memuat...'"></h2>
                    <div class="role" x-text="[employee.role, employee.unit].filter(Boolean).join(' · ')"></div>
                </div>

                <div class="clock-card">
                    <div class="date" x-text="dateLong"></div>
                    <div class="time"><span x-text="clockHM"></span><span x-text="':' + clockS"></span></div>
                </div>

                <!-- ERROR BOX (geolokasi) -->
                <div x-show="geoErrorMsg">
                    <div class="error-box">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                            <path d="M10 6v5M10 14h.01" stroke-linecap="round" />
                            <circle cx="10" cy="10" r="8" />
                        </svg>
                        <div>
                            <div x-text="geoErrorMsg"></div>
                            <button @click="retryLocation()">Coba Lagi</button>
                        </div>
                    </div>
                </div>

                <!-- CARD: LOKASI & PETA -->
                <div class="card" x-show="!geoErrorMsg">
                    <div class="geo-top">
                        <!-- Radar indicator -->
                        <div class="radar" :class="geoLoading ? 'pending' : (inRadius ? 'active' : 'inactive')">
                            <div class="ring" x-show="!geoLoading"></div>
                            <div class="ring" x-show="!geoLoading"></div>
                            <div class="ring" x-show="!geoLoading"></div>
                            <div class="core">
                                <svg x-show="!geoLoading" width="18" height="18" viewBox="0 0 28 28" fill="currentColor">
                                    <path d="M14 4c-3 2.5-5 5.5-5 9a5 5 0 0010 0c0-3.5-2-6.5-5-9z" />
                                    <rect x="13" y="1" width="2" height="3" />
                                    <rect x="5" y="21" width="18" height="2" opacity="0.55" />
                                </svg>
                                <div x-show="geoLoading" class="spinner" style="border-color:rgba(23,73,60,0.25);border-top-color:var(--primary);"></div>
                            </div>
                        </div>
                        <!-- Status jarak -->
                        <div class="geo-status">
                            <div class="label pending" x-show="geoLoading">Mendeteksi lokasi Anda...</div>
                            <div class="label" x-show="!geoLoading" :class="inRadius ? 'active' : 'inactive'" x-text="inRadius ? 'Dalam radius pesantren' : 'Di luar radius pesantren'"></div>
                            <div class="distance" x-show="!geoLoading">
                                Jarak Anda <b x-text="distance !== null ? distance + ' m' : '—'"></b> dari titik lokasi
                            </div>
                        </div>
                    </div>
                    <!-- Meta -->
                    <div class="geo-meta" x-show="!geoLoading">
                        <span>Radius absen: <b style="color:var(--ink)" x-text="(officeLocation ? officeLocation.radius_meter : '—') + ' m'"></b></span>
                        <span x-text="'Akurasi GPS: ' + (accuracy !== null ? Math.round(accuracy) + ' m' : '—')"></span>
                    </div>
                    <!-- Peta -->
                    <div class="map-container" x-ref="mapContainer"></div>

                    <!-- Peringatan akurasi GPS -->
                    <div class="accuracy-warn" x-show="!geoLoading && accuracy !== null && accuracy > APP_CONFIG.minAccuracyMeter">
                        <svg width="15" height="15" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                            <path d="M10 6v5M10 14h.01" stroke-linecap="round" />
                            <circle cx="10" cy="10" r="8" />
                        </svg>
                        <span>Akurasi GPS kurang baik. Coba pindah ke area terbuka agar deteksi lokasi lebih akurat.</span>
                    </div>
                </div>

                <!-- TOMBOL CTA (check-in / check-out) -->
                <!-- MODE LAMA: unit tanpa jadwal (perilaku sebelumnya) -->
                <div x-show="!geoErrorMsg && unitSchedules.length === 0">
                    <div>
                        <button
                            class="cta-btn"
                            :class="todayStatus.checked_in ? 'done' : (ctaEnabled ? 'enabled' : 'disabled')"
                            :disabled="!ctaEnabled || submitting"
                            @click="handleCta()">
                            <span x-show="submitting" class="spinner"></span>
                            <svg x-show="!submitting" width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 10l4 4 8-9" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span x-text="ctaLabel"></span>
                        </button>
                        <div class="cta-helper" :class="!inRadius && !geoLoading && 'warn'">
                            <span x-show="geoLoading">Menunggu deteksi lokasi sebelum bisa absen...</span>
                            <span x-show="!geoLoading && todayStatus.checked_in">
                                Anda sudah checkin hari ini.
                            </span>
                            <span x-show="!geoLoading && todayStatus.is_holiday">
                                Hari ini libur: <b x-text="todayStatus.holiday_name"></b>
                            </span>
                            <span x-show="!geoLoading && todayStatus.is_day_off && !todayStatus.is_holiday">
                                Hari ini bukan hari kerja Anda.
                            </span>
                            <span x-show="!geoLoading && beforeExpectedTime">
                                Checkin aktif mulai pukul <b x-text="expectedTimeLabel"></b>
                            </span>
                            <div x-show="!geoLoading && !inRadius && !todayStatus.is_holiday && !todayStatus.is_day_off && !beforeExpectedTime && !todayStatus.checked_in">
                                Anda harus berada dalam radius <span x-text="officeLocation ? officeLocation.radius_meter : '—'"></span> m dari pesantren untuk bisa absen.
                            </div>
                            <div x-show="!geoLoading && inRadius && !todayStatus.is_holiday && !todayStatus.is_day_off && !beforeExpectedTime && !todayStatus.checked_in">
                                Anda berada dalam jangkauan. Ketuk tombol untuk mencatat kehadiran.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL CTA MODE BARU: jadwal unit (pres_unit_schedules) -->
                <div x-show="!geoErrorMsg && unitSchedules.length > 0">

                    <!-- Bukan hari kerja / libur -> jangan tampilkan tombol checkin -->
                    <div class="cta-helper" x-show="!geoLoading && (todayStatus.is_holiday || todayStatus.is_day_off)">
                        <span x-show="todayStatus.is_holiday">
                            Hari ini libur: <b x-text="todayStatus.holiday_name"></b>
                        </span>
                        <span x-show="todayStatus.is_day_off && !todayStatus.is_holiday">
                            Hari ini bukan hari kerja Anda.
                        </span>
                    </div>

                    <!-- Tombol checkin per jadwal unit (tetap tampil walau sudah checkin) -->
                    <div x-show="!(todayStatus.is_holiday || todayStatus.is_day_off)">
                        <template x-for="s in unitSchedules" :key="s.id">
                            <div style="margin-bottom:12px;">
                                <button
                                    class="cta-btn"
                                    :class="isScheduleDone(s) ? 'done' : (isScheduleButtonEnabled(s) ? 'enabled' : 'disabled')"
                                    :disabled="!isScheduleButtonEnabled(s)"
                                    @click="handleScheduleCta(s)">
                                    <span x-show="submitting" class="spinner"></span>
                                    <svg x-show="!submitting" width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M4 10l4 4 8-9" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span x-text="s.title"></span>
                                </button>
                                <div class="cta-helper" :class="!inRadius && !geoLoading && 'warn'">
                                    <span x-show="geoLoading">Menunggu deteksi lokasi sebelum bisa absen...</span>
                                    <span x-show="!geoLoading && isScheduleDone(s)">
                                        Anda sudah checkin untuk jadwal ini hari ini.
                                    </span>
                                    <span x-show="!geoLoading && !isScheduleDone(s) && !isScheduleWindowActive(s)">Jadwal aktif pukul <b x-text="scheduleWindowLabel(s)"></b></span>
                                    <span x-show="!geoLoading && !isScheduleDone(s) && isScheduleWindowActive(s) && !inRadius">
                                        Anda harus berada dalam radius <span x-text="officeLocation ? officeLocation.radius_meter : '—'"></span> m dari pesantren untuk bisa absen.
                                    </span>
                                    <span x-show="!geoLoading && !isScheduleDone(s) && isScheduleWindowActive(s) && inRadius">
                                        Anda berada dalam jangkauan. Ketuk tombol untuk mencatat kehadiran.
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- AKTIVITAS HARI INI -->
                <div class="section-label">Aktivitas Hari Ini</div>
                <div x-show="loadingToday" class="skeleton skel-row"></div>

                <!-- Daftar check-in hari ini (bisa lebih dari satu) -->
                <template x-for="(c, i) in todayCheckins" :key="i">
                    <div class="today-item">
                        <div class="dot">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 10l4 4 8-9" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="info">
                            <div class="t1" x-text="c.title || 'Checkin Masuk'"></div>
                            <div class="t2" x-text="'Pukul ' + c.check_in_time + (c.check_in_distance_meter !== null ? (' · ' + c.check_in_distance_meter + ' m dari lokasi') : '')"></div>
                        </div>
                        <div class="badge" :class="c.status==='hadir' && todayStatus.schedule && todayStatus.schedule.expected_time_in && 'ontime'" x-text="c.status==='terlambat' ? 'Terlambat' : (todayStatus.schedule && todayStatus.schedule.expected_time_in ? 'Tepat waktu' : 'Hadir')"></div>
                    </div>
                </template>

                <div x-show="!loadingToday && todayCheckins.length === 0 && !todayStatus.is_holiday && !todayStatus.is_day_off">
                    <div class="today-item">
                        <div class="dot muted">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="10" cy="10" r="7" />
                                <path d="M10 6v4l3 2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="info">
                            <div class="t1" style="color:var(--ink-faint)">Belum ada absen masuk</div>
                            <div class="t2">Menunggu Anda memasuki radius lokasi</div>
                        </div>
                    </div>
                </div>
                <div x-show="!loadingToday && todayStatus.is_holiday">
                    <div class="today-item">
                        <div class="dot muted">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="14" height="14" rx="2" />
                                <path d="M8 8h4v6H8zM8 8V5a2 2 0 012-2h0a2 2 0 012 2v3" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="info">
                            <div class="t1">Hari Libur</div>
                            <div class="t2" x-text="todayStatus.holiday_name"></div>
                        </div>
                    </div>
                </div>
                <div x-show="!loadingToday && todayStatus.is_day_off && !todayStatus.is_holiday">
                    <div class="today-item">
                        <div class="dot muted">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="10" cy="10" r="7" />
                                <path d="M10 6v4l3 2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="info">
                            <div class="t1">Bukan Hari Kerja</div>
                            <div class="t2">Tidak ada jadwal kerja hari ini</div>
                        </div>
                    </div>
                </div>

                </div><!-- /!employeeNotFound -->
            </div>

            <!-- TAB: RIWAYAT -->
            <div x-show="tab==='history'">
                <div class="greeting" style="padding-bottom:2px;">
                    <div class="eyebrow">Riwayat</div>
                    <h2>Presensi Saya</h2>
                </div>

                <div x-show="loadingHistory">
                    <div class="skeleton skel-card"></div>
                    <div class="skeleton skel-row"></div>
                    <div class="skeleton skel-row"></div>
                </div>

                <div x-show="!loadingHistory">
                    <div>
                        <!-- Ringkasan -->
                        <div class="hist-summary">
                            <div class="col">
                                <div class="num" x-text="historySummary.hadir"></div>
                                <div class="lbl">Hadir</div>
                            </div>
                            <div class="col">
                                <div class="num" x-text="historySummary.terlambat"></div>
                                <div class="lbl">Terlambat</div>
                            </div>
                            <div class="col">
                                <div class="num" x-text="historySummary.total"></div>
                                <div class="lbl">Total Tercatat</div>
                            </div>
                        </div>

                        <!-- Daftar riwayat -->
                        <template x-for="h in history" :key="h.date">
                            <div class="hist-row">
                                <div class="icn" :class="h.status==='terlambat' ? 'late' : 'in'">
                                    <svg width="15" height="15" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 10l4 4 8-9" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="body">
                                    <div class="r1" x-text="formatDateLabel(h.date) + ' · Masuk ' + (h.check_in_time || '—')"></div>
                                    <div class="r2" x-text="h.check_out_time ? ('Pulang ' + h.check_out_time) : 'Belum absen pulang'"></div>
                                </div>
                                <div class="status" :class="h.status==='terlambat' && 'late'" x-text="statusLabel(h.status)"></div>
                            </div>
                        </template>

                        <div class="empty-state" x-show="history.length===0">Belum ada riwayat presensi.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTTOM NAVIGATION (hanya untuk super/admin) -->
        <nav class="bottomnav" x-show="showBottomNav">
            <button class="item" :class="tab==='home' && 'active'" @click="tab='home'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4" />
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                </svg>
                <span>Checkin</span>
            </button>
            <a href="/member/checkin/rekap" class="item" :class="tab==='history' && 'active'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10 9 9 9 8 9" />
                </svg>
                <span>Rekap</span>
            </a>
        </nav>

        <!-- TOAST NOTIFIKASI -->
        <div class="toast" :class="toast.type" x-show="toast.show" x-transition x-cloak>
            <div class="icn">
                <svg x-show="toast.type!=='error'" width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="#fff" stroke-width="2">
                    <path d="M4 10l4 4 8-9" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <svg x-show="toast.type==='error'" width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="#fff" stroke-width="2">
                    <path d="M6 6l8 8M14 6l-8 8" stroke-linecap="round" />
                </svg>
            </div>
            <div>
                <div class="t1" x-text="toast.title"></div>
                <div class="t2" x-show="toast.desc" x-text="toast.desc"></div>
            </div>
        </div>

      <!-- POPUP SUKSES (lebih prominent daripada toast) -->
    <div class="popup-overlay" x-show="popup.show" x-transition.opacity x-cloak>
      <div class="popup-card" :class="popup.type" x-show="popup.show" x-transition.scale>
        <div class="popup-icon">
          <svg x-show="popup.type==='success'" width="48" height="48" viewBox="0 0 48 48" fill="none">
            <circle cx="24" cy="24" r="24" fill="#D4F0F3"/>
            <path d="M16 24l6 6 10-12" stroke="#3BC0CF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3 class="popup-title" x-text="popup.title"></h3>
        <p class="popup-desc" x-text="popup.desc"></p>
        <p class="popup-sub" x-show="popup.sub" x-text="popup.sub"></p>
        <button class="popup-btn" @click="closePopup()">Tutup</button>
      </div>
    </div>

  </div>
</div>