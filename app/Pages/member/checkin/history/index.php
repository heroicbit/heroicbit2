<div id="member_checkin_history" x-data="member_checkin_history()" x-cloak>

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
            --green-soft: #D9F2E5;
            --amber: #C79A3E;
            --amber-soft: #F6EDDA;
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
            padding: calc(env(safe-area-inset-top, 0px) + 16px) 20px 8px;
            flex-shrink: 0;
        }

        .topbar .brand {
            display: flex;
            align-items: center;
            gap: 9px;
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
            font-size: 13px;
            color: var(--ink-faint);
        }

        .content {
            flex: 1;
            overflow-y: auto;
            padding: 4px 20px 20px;
        }

        .tabs {
            display: flex;
            gap: 6px;
            margin: 6px 0 16px;
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

        .card {
            background: var(--bg-surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 16px 16px 14px;
            margin: 14px 0;
        }

        .filter-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 10px;
        }

        .filter-label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .range-badge {
            font-size: 11px;
            font-weight: 600;
            color: var(--brass-ink);
            background: var(--brass-soft);
            padding: 4px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .chip-row {
            display: flex;
            gap: 6px;
        }

        .chip {
            flex: 1;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 10px;
            border-radius: 999px;
            border: 1.5px solid var(--line);
            background: var(--bg-surface-2);
            color: var(--ink-soft);
            cursor: pointer;
            white-space: nowrap;
        }

        .chip.selected {
            background: var(--brass);
            border-color: var(--brass);
            color: #fff;
        }

        .custom-range {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed var(--line);
        }

        .custom-range .fields {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .custom-range .field {
            flex: 1;
            min-width: 0;
        }

        .custom-range .field .flabel {
            font-size: 10.5px;
            font-weight: 600;
            color: var(--ink-faint);
            margin-bottom: 4px;
        }

        .custom-range input[type="date"] {
            width: 100%;
            padding: 9px 11px;
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            background: var(--bg-surface-2);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12.5px;
            color: var(--ink);
            outline: none;
        }

        .custom-range input[type="date"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 192, 207, 0.16);
        }

        .custom-range .sep {
            padding-top: 16px;
            font-size: 12px;
            color: var(--ink-faint);
        }

        .custom-range .apply-btn {
            width: 100%;
            margin-top: 12px;
            padding: 11px;
            border: none;
            border-radius: 999px;
            background: var(--brass);
            color: #fff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .custom-range .apply-btn:active {
            opacity: .85;
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

        .hist-summary {
            background: var(--bg-surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 16px;
            display: flex;
            margin: 2px 0 4px;
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

        .hist-summary .col.green .num {
            color: var(--green);
        }

        .hist-summary .col.amber .num {
            color: var(--amber);
        }

        .hist-summary .col.red .num {
            color: var(--rust);
        }

        .hist-summary .col .lbl {
            font-size: 10.5px;
            color: var(--ink-faint);
            margin-top: 2px;
        }

        .range-note {
            text-align: center;
            font-size: 11.5px;
            color: var(--ink-faint);
            margin: 8px 0 2px;
        }

        .range-note .sep {
            color: var(--line);
        }

        .rate-note {
            text-align: center;
            font-size: 11px;
            color: var(--ink-soft);
            background: var(--primary-soft);
            border-radius: 999px;
            padding: 5px 10px;
            margin: 4px 0 0;
        }

        .rate-note b {
            color: var(--brass-ink);
        }

        .section-label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ink-soft);
            margin: 20px 0 10px 2px;
            text-transform: uppercase;
            letter-spacing: .06em;
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hist-row .icn.st-hadir {
            background: var(--green-soft);
            color: var(--green);
        }

        .hist-row .icn.st-parsial {
            background: var(--amber-soft);
            color: var(--amber);
        }

        .hist-row .icn.st-tidak_hadir {
            background: var(--rust-soft);
            color: var(--rust);
        }

        .hist-row .body {
            flex: 1;
            min-width: 0;
        }

        .hist-row .body .r1 {
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .hist-row .body .r1 .sch {
            font-size: 9.5px;
            font-weight: 700;
            background: var(--brass-soft);
            color: var(--brass-ink);
            padding: 2px 7px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .hist-row .body .r1 .sch.late {
            background: var(--rust-soft);
            color: var(--rust);
        }

        .hist-row .body .r2.absent {
            color: var(--rust);
            font-weight: 600;
        }

        .hist-row .body .r2 {
            font-size: 11.5px;
            color: var(--ink-faint);
            margin-top: 3px;
        }

        .hist-row .body .r2 b {
            color: var(--ink);
            font-weight: 700;
        }

        .hist-row .status {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .hist-row .status.st-hadir {
            background: var(--green-soft);
            color: var(--green);
        }

        .hist-row .status.st-parsial {
            background: var(--amber-soft);
            color: var(--amber);
        }

        .hist-row .status.st-tidak_hadir {
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
            height: 110px;
            margin: 2px 0 12px;
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
            line-height: 1.6;
        }

        .empty-state svg {
            margin: 0 auto 12px;
            display: block;
        }

        .empty-state b {
            display: block;
            color: var(--ink-soft);
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .toast {
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            bottom: 40px;
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

        svg {
            display: block;
        }

        /* --- Modal detail hari --- */
        .hist-row.clickable {
            cursor: pointer;
        }

        .hist-row.clickable:active {
            background: var(--bg-surface-2);
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(20, 48, 58, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        .modal-card {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 400px;
            max-height: 84vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(20, 48, 58, 0.3);
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 16px 18px 4px;
            flex-shrink: 0;
        }

        .modal-head .m-eyebrow {
            font-size: 11px;
            font-weight: 600;
            color: var(--ink-faint);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .modal-head .m-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 17px;
            font-weight: 700;
            margin: 2px 0 0;
        }

        .m-close {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            background: var(--bg-surface-2);
            color: var(--ink-soft);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .modal-body {
            padding: 2px 18px 18px;
            overflow-y: auto;
        }

        .m-status-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0 4px;
        }

        .m-chip {
            font-size: 11px;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .m-chip.st-hadir {
            background: var(--green-soft);
            color: var(--green);
        }

        .m-chip.st-parsial {
            background: var(--amber-soft);
            color: var(--amber);
        }

        .m-chip.st-tidak_hadir {
            background: var(--rust-soft);
            color: var(--rust);
        }

        .m-pct {
            font-size: 11px;
            font-weight: 700;
            color: var(--brass-ink);
            background: var(--brass-soft);
            padding: 4px 9px;
            border-radius: 999px;
        }

        .m-info {
            background: var(--bg-surface-2);
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 11px 13px;
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--ink-soft);
            line-height: 1.6;
        }

        .m-info b {
            color: var(--ink);
        }

        .m-label {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin: 14px 2px 8px;
        }

        .m-sched {
            display: flex;
            align-items: center;
            gap: 11px;
            background: var(--bg-surface-2);
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 10px 12px;
            margin-bottom: 7px;
        }

        .m-sched .ms-icn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .m-sched.ok .ms-icn {
            background: var(--green-soft);
            color: var(--green);
        }

        .m-sched.no .ms-icn {
            background: var(--rust-soft);
            color: var(--rust);
        }

        .m-sched .ms-body {
            flex: 1;
            min-width: 0;
        }

        .m-sched .ms-t {
            font-size: 12.5px;
            font-weight: 700;
        }

        .m-sched .ms-sub {
            font-size: 10.5px;
            color: var(--ink-faint);
            margin-top: 2px;
        }

        .m-sched .ms-sub.ok {
            color: var(--ink-soft);
        }

        .m-sched .ms-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 999px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .m-sched.ok .ms-badge {
            background: var(--green-soft);
            color: var(--green);
        }

        .m-sched.no .ms-badge {
            background: var(--rust-soft);
            color: var(--rust);
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <div class="app-shell">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="brand">
                <div class="brand-mark">
                    <svg width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 10l4 4 8-9" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div class="brand-name">Riwayat Presensi</div>
                    <div class="brand-sub" x-text="employee.unit ? ('Unit ' + employee.unit) : 'Presensi Karyawan'"></div>
                </div>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <!-- TABS (navigasi antar halaman check-in) -->
            <div class="tabs">
                <button @click="goCheckin()">Beranda</button>
                <button class="active" disabled>Riwayat</button>
            </div>

            <!-- KARYAWAN TIDAK DITEMUKAN -->
            <div x-show="employeeNotFound" class="empty-state">
                <div style="margin-bottom:10px;">
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none" style="display:inline-block;">
                        <circle cx="28" cy="28" r="28" fill="#F5DEDE"/>
                        <path d="M28 20v12M28 38h.01" stroke="#D94F4F" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="28" cy="28" r="22" stroke="#D94F4F" stroke-width="2"/>
                    </svg>
                </div>
                <b>Karyawan Belum Terdaftar</b>
                Akun Anda belum terdaftar sebagai karyawan, sehingga riwayat presensi belum tersedia.
            </div>

            <div x-show="!employeeNotFound">

                <div class="greeting">
                    <div class="eyebrow">Riwayat presensi Anda</div>
                    <h2 x-text="employee.name || 'Memuat...'"></h2>
                    <div class="role" x-text="[employee.role, employee.unit].filter(Boolean).join(' · ')"></div>
                </div>

                <!-- FILTER RENTANG -->
                <div class="card">
                    <div class="filter-head">
                        <div class="filter-label">Rentang Riwayat</div>
                        <div class="range-badge" x-show="!loading && rangeInfo.start_date" x-text="rangeLabel"></div>
                    </div>
                    <div class="chip-row">
                        <button class="chip" :class="range==='this_month' && 'selected'" @click="setRange('this_month')">Bulan Ini</button>
                        <button class="chip" :class="range==='last_month' && 'selected'" @click="setRange('last_month')">Bulan Lalu</button>
                        <button class="chip" :class="range==='custom' && 'selected'" @click="setRange('custom')">Custom</button>
                    </div>

                    <!-- Custom range: pilih tanggal mulai & akhir -->
                    <div class="custom-range" x-show="range==='custom'">
                        <div class="fields">
                            <div class="field">
                                <div class="flabel">Dari</div>
                                <input type="date" x-model="customStart" :max="customEnd || todayISO">
                            </div>
                            <div class="sep">s/d</div>
                            <div class="field">
                                <div class="flabel">Sampai</div>
                                <input type="date" x-model="customEnd" :max="todayISO">
                            </div>
                        </div>
                        <button class="apply-btn" @click="applyCustom()">Terapkan Rentang</button>
                    </div>
                </div>

                <!-- ERROR -->
                <div x-show="errorMsg" class="error-box">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                        <path d="M10 6v5M10 14h.01" stroke-linecap="round" />
                        <circle cx="10" cy="10" r="8" />
                    </svg>
                    <div>
                        <div x-text="errorMsg"></div>
                        <button @click="loadHistory()">Coba Lagi</button>
                    </div>
                </div>

                <!-- LOADING -->
                <div x-show="loading && !errorMsg">
                    <div class="skeleton skel-card"></div>
                    <div class="skeleton skel-row"></div>
                    <div class="skeleton skel-row"></div>
                    <div class="skeleton skel-row"></div>
                </div>

                <!-- DATA -->
                <div x-show="!loading && !errorMsg">

                    <!-- Ringkasan (per hari kerja) -->
                    <div class="hist-summary">
                        <div class="col green">
                            <div class="num" x-text="summary.hadir"></div>
                            <div class="lbl">Hadir</div>
                        </div>
                        <div class="col amber">
                            <div class="num" x-text="summary.parsial"></div>
                            <div class="lbl">Sebagian</div>
                        </div>
                        <div class="col red">
                            <div class="num" x-text="summary.tidak_hadir"></div>
                            <div class="lbl">Tidak Hadir</div>
                        </div>
                    </div>

                    <div class="range-note">
                        <span x-show="rangeLabel" x-text="'Periode: ' + rangeLabel"></span>
                        <span x-show="summary.work_days > 0" x-text="(rangeLabel ? ' · ' : '') + summary.work_days + ' hari kerja'"></span>
                        <span x-show="summary.libur > 0" x-text="' · ' + summary.libur + ' libur'"></span>
                        <span x-show="summary.bukan_hari_kerja > 0" x-text="' · ' + summary.bukan_hari_kerja + ' off'"></span>
                    </div>
                    <div class="rate-note" x-show="summary.work_days > 0">
                        Rata-rata cakupan kehadiran <b x-text="summary.percent + '%'"></b>
                    </div>

                    <div class="section-label" x-show="days.length > 0">Daftar Hari Kerja</div>

                    <!-- Daftar hari kerja & statusnya -->
                    <template x-for="d in days" :key="d.date">
                        <div class="hist-row clickable" @click="openDay(d)">
                            <div class="icn" :class="'st-' + d.status">
                                <svg x-show="d.status==='hadir'" width="15" height="15" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 10l4 4 8-9" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <svg x-show="d.status==='parsial'" width="15" height="15" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="10" cy="10" r="8" />
                                    <path d="M10 6.5V10l2.6 1.6" stroke-linecap="round" />
                                </svg>
                                <svg x-show="d.status==='tidak_hadir'" width="15" height="15" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 6l8 8M14 6l-8 8" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div class="body">
                                <div class="r1">
                                    <span x-text="dateTitle(d.date)"></span>
                                    <span class="sch late" x-show="d.is_late && d.status==='hadir'">Terlambat</span>
                                </div>
                                <div class="r2" x-show="d.status==='hadir' || d.status==='parsial'">
                                    <span x-show="d.check_in_time">Masuk <b x-text="d.check_in_time"></b></span>
                                    <span x-show="d.check_in_time && d.check_in_distance_meter !== null"> · <b x-text="d.check_in_distance_meter"></b> m</span>
                                    <span x-show="d.check_out_time"> · Pulang <b x-text="d.check_out_time"></b></span>
                                    <span x-show="(d.total_schedules || 0) > 1" x-text="' · Checkin ' + d.check_in_count + '/' + d.total_schedules"></span>
                                </div>
                                <div class="r2 absent" x-show="d.status==='tidak_hadir'">Tidak ada check-in pada hari kerja ini</div>
                            </div>
                            <div class="status" :class="'st-' + d.status" x-text="statusLabel(d.status)"></div>
                        </div>
                    </template>

                    <!-- Kosong -->
                    <div class="empty-state" x-show="days.length===0">
                        <svg width="48" height="48" viewBox="0 0 56 56" fill="none" style="display:inline-block;">
                            <circle cx="28" cy="28" r="28" fill="#D4E4E8"/>
                            <path d="M28 18v14M28 38h.01" stroke="#92AAB0" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                        <b x-text="summary.work_days === 0 ? 'Tidak ada hari kerja' : 'Belum ada data'"></b>
                        <span x-text="summary.work_days === 0
                            ? 'Tidak ada hari kerja dalam rentang tanggal ini.'
                            : 'Belum ada hari kerja yang bisa dinilai pada rentang ini.'"></span>
                    </div>
                </div>

            </div>
        </div>

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

        <!-- MODAL DETAIL HARI -->
        <template x-if="modal.show">
            <div class="modal-overlay" @click.self="closeModal()">
                <div class="modal-card">
                    <div class="modal-head">
                        <div>
                            <div class="m-eyebrow">Detail Presensi</div>
                            <h3 class="m-title" x-text="dateTitle(modal.day.date)"></h3>
                        </div>
                        <button class="m-close" @click="closeModal()">
                            <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 5l10 10M15 5L5 15" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="m-status-row">
                            <span class="m-chip" :class="'st-' + modal.day.status" x-text="statusLabel(modal.day.status)"></span>
                            <span class="m-pct" x-show="(modal.day.total_schedules || 0) > 0" x-text="'Cakupan ' + modal.day.percent + '%'"></span>
                        </div>

                        <!-- Unit tanpa jadwal unit -> info check-in biasa -->
                        <div class="m-info" x-show="(modal.day.total_schedules || 0) === 0">
                            <template x-if="modal.day.status==='tidak_hadir'">
                                <span>Tidak ada check-in pada hari ini.</span>
                            </template>
                            <template x-if="modal.day.status!=='tidak_hadir'">
                                <span>
                                    Masuk <b x-text="modal.day.check_in_time || '—'"></b>
                                    <template x-if="modal.day.check_out_time"><span> · Pulang <b x-text="modal.day.check_out_time"></b></span></template>
                                    <template x-if="modal.day.check_in_distance_meter !== null"><span> · <b x-text="modal.day.check_in_distance_meter"></b> m dari lokasi</span></template>
                                </span>
                            </template>
                        </div>

                        <!-- Unit dengan jadwal unit -> rincian hadir / tidak hadir per jadwal -->
                        <template x-if="(modal.day.total_schedules || 0) > 0">
                            <div>
                                <div class="m-label" x-text="'Jadwal Unit (' + modal.day.check_in_count + '/' + modal.day.total_schedules + ' hadir)'"></div>
                                <template x-for="s in modal.day.schedules" :key="s.id">
                                    <div class="m-sched" :class="s.present ? 'ok' : 'no'">
                                        <div class="ms-icn">
                                            <svg x-show="s.present" width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 10l4 4 8-9" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <svg x-show="!s.present" width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M6 6l8 8M14 6l-8 8" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <div class="ms-body">
                                            <div class="ms-t" x-text="s.title"></div>
                                            <div class="ms-sub" x-text="schedWindow(s)"></div>
                                            <div class="ms-sub ok" x-show="s.present" x-text="'Check-in ' + (s.check_in_time || '—') + (s.check_in_distance_meter !== null ? (' · ' + s.check_in_distance_meter + ' m') : '')"></div>
                                        </div>
                                        <span class="ms-badge" x-text="s.present ? 'Hadir' : 'Tidak Hadir'"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

    </div>
</div>
