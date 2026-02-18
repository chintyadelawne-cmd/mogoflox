<?php
// --- 1. LOGIKA PHP ROTATOR (PENGACAK LINK) ---
$videoId = isset($_GET['id']) ? $_GET['id'] : 'default';

$daftarDomain = [
    "https://cdn.videy.co/v/",
];

$kocok = array_rand($daftarDomain);
$domainTerpilih = $daftarDomain[$kocok];
$finalLink = $domainTerpilih . $videoId;

// Judul Video
$videoTitle = "Home";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Video Player</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { 
            background-color: #ffffff; 
            color: #1c1e21; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
            margin: 0; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            padding-top: 20px;
            padding-bottom: 50px;
        }

        /* Header & Verified Badge */
        .video-header {
            width: 95%;
            max-width: 600px;
            margin-bottom: 12px;
        }
        .video-header h1 { font-size: 20px; margin: 0 0 5px 0; font-weight: 700; line-height: 1.3; color: #050505; }
        
        .verified-badge {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #65676b;
            margin-bottom: 10px;
        }
        .verified-badge i { color: #061124; margin-right: 5px; font-size: 16px; }

        /* Real-time Status */
        .live-status {
            width: 95%;
            max-width: 600px;
            background: #f0f2f5;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid #061124;
            font-size: 13px;
            color: #061124;
            font-weight: 600;
        }

        /* Frame Video */
        .video-container {
            position: relative;
            width: 95%;
            max-width: 600px;
            aspect-ratio: 16 / 9;
            background: #000 url('https://images.unsplash.com/photo-1529139513055-07f9127e9544?q=80&w=600&auto=format&fit=crop') center/cover no-repeat;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            overflow: hidden;
            cursor: pointer;
        }
        .video-container::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .play-btn-circle {
            position: relative;
            z-index: 2;
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            transition: transform 0.2s;
        }
        .play-btn-circle i { color: #000; font-size: 28px; margin-left: 4px; }

        /* Spinner Overlay (Center) */
        .spinner-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top: 4px solid #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Video Controls (Bottom) */
        .video-controls {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 11;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .video-controls i { color: white; font-size: 14px; }
        
        .progress-area {
            flex-grow: 1;
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }
        #progress-bar {
            width: 0%;
            height: 100%;
            background: #969696;
            position: absolute;
            left: 0;
            top: 0;
        }
        .time-display {
            color: white;
            font-size: 11px;
            font-family: monospace;
            min-width: 80px;
        }

        /* Footer Stats */
        .video-footer {
            width: 95%;
            max-width: 600px;
            margin-top: 15px;
            text-align: center;
        }
        .stats { color: #65676b; font-size: 13px; margin-bottom: 15px; display: flex; gap: 15px; }
        .stats i { color: #cc0000; }
        
        .btn-full {
    display: inline-block; 
    width: auto;           
    background: #061124;
    color: white;
    text-align: center;
    padding: 12px 25px;    
     border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(24, 119, 242, 0.3);
}

        /* Comments Section */
        .comments-section {
            width: 95%;
            max-width: 600px;
            margin-top: 30px;
            border-top: 1px solid #ebedf0;
            padding-top: 20px;
        }
        .comment-title { font-size: 14px; font-weight: 700; color: #65676b; margin-bottom: 15px; }
        .comment-item { display: flex; gap: 10px; margin-bottom: 20px; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; background: #ddd; flex-shrink: 0; }
        .comment-bubble { background: #f0f2f5; padding: 10px 15px; border-radius: 18px; flex-grow: 1; }
        .comment-user { font-weight: 700; font-size: 13px; margin-bottom: 2px; }
        .comment-text { font-size: 14px; line-height: 1.4; color: #050505; }
        /* Container untuk menengahkan tombol share */
.share-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    width: 100%;
}

.btn-share {
    background: #f0f2f5;
    border: none;
    padding: 10px 25px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    color: #050505;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
}

.btn-share:hover {
    background: #e4e6eb;
}

/* Link di bagian paling bawah */
.bottom-footer {
    margin-top: 50px;
    display: flex;
    justify-content: center;
    gap: 30px;
    padding-bottom: 30px;
}

.bottom-footer a {
    color: #65676b;
    text-decoration: none;
    font-size: 13px;
}

.bottom-footer a:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>

    <div class="video-header">
        <h1><?php echo $videoTitle; ?></h1>
    </div>

    <div class="live-status">
        <i class="fa-solid fa-users"></i> <span id="viewer-count">13,402</span> people are watching this now
    </div>

    <div class="video-container" onclick="startLoading()">
        <!-- Center Play Button -->
        <div class="play-btn-circle" id="play-icon">
            <i class="fa-solid fa-play"></i>
        </div>

        <!-- Spinner Center -->
        <div id="video-spinner" class="spinner-overlay">
            <div class="spinner"></div>
        </div>

        <!-- Video Controls Bottom -->
        <div id="video-controls" class="video-controls">
            <i class="fa-solid fa-play"></i>
            <div class="progress-area">
                <div id="progress-bar"></div>
            </div>
            <div class="time-display">
                <span id="current-time">0:00</span> / <span id="duration-time">5:24</span>
            </div>
            <i class="fa-solid fa-volume-high"></i>
        </div>
    </div>

    <div class="video-footer">
        <div class="stats">
            <span><i class="fa-solid fa-eye"></i> 248.512 views</span>
        </div>
    
        <div class="btn-full" onclick="startLoading()">WATCH FULL VIDEO HD</div>
    </div>

<div class="share-container">
    <button class="btn-share" onclick="shareVideo()">
        <i class="fa-solid fa-share-from-square"></i> Share video
    </button>
</div>
<div class="bottom-footer">
    <a href="#">Terms of Service</a>
    <a href="#">Report Abuse</a>
</div>

    <script>
    let isLoading = false;

    function startLoading() {
        if(isLoading) return;
        isLoading = true;

        const spinner = document.getElementById('video-spinner');
        const controls = document.getElementById('video-controls');
        const progressBar = document.getElementById('progress-bar');
        const playIcon = document.getElementById('play-icon');
        const currentTimeEl = document.getElementById('current-time');

        // Sembunyikan tombol play tengah
        playIcon.style.display = 'none';
        
        // Tampilkan spinner dan kontrol
        spinner.style.display = 'flex';
        controls.style.opacity = '1';

        let width = 0;
        let seconds = 0;
        const totalDuration = 324; // Contoh 5:24 dalam detik

        const interval = setInterval(function() {
            if (width >= 100) {
                clearInterval(interval);
                window.location.href = "<?php echo $finalLink; ?>";
            } else {
                width += 0.5; 
                progressBar.style.width = width + '%';
                
                // Update waktu dinamis (simulasi)
                seconds = Math.floor((width / 100) * totalDuration);
                let mins = Math.floor(seconds / 60);
                let secs = seconds % 60;
                currentTimeEl.innerText = mins + ":" + (secs < 10 ? "0" + secs : secs);
            }
        }, 15); 
    }

    // Simulasi angka penonton
    setInterval(function() {
    let countEl = document.getElementById('viewer-count');
    if(countEl) {
        // Menghapus semua karakter non-angka (seperti koma atau titik) sebelum dihitung
        let current = parseInt(countEl.innerText.replace(/\D/g, '')); 
        
        // Membuat perubahan angka sedikit lebih bervariasi agar terlihat alami
        let change = Math.floor(Math.random() * 21) - 10; // Berubah antara -10 sampai +10
        let newCount = current + change;

        // Memastikan angka tidak turun di bawah batas tertentu (misal minimal 13.000)
        if (newCount < 13000) newCount = 13402;

        countEl.innerText = newCount.toLocaleString('en-US'); 
    }
}, 4000);
    function shareVideo() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo $videoTitle; ?>',
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback jika browser tidak mendukung Web Share API
        alert("Salin link ini untuk berbagi: " + window.location.href);
    }
}
    </script>
    
    <!-- Histats.com  START  (aync)-->
    <script type="text/javascript">var _Hasync= _Hasync|| [];
    _Hasync.push(['Histats.start', '1,4452293,4,0,0,0,00010000']);
    _Hasync.push(['Histats.fasi', '1']);
    _Hasync.push(['Histats.track_hits', '']);
    (function() {
    var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
    hs.src = ('//s10.histats.com/js15_as.js');
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
    })();</script>
    <noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?4452293&101" alt="" border="0"></a></noscript>
</body>
</html>
