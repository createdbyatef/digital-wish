// 💎 SUPABASE CONFIG: Majestic Connection Active!
const SUPABASE_URL = 'https://dnnriugtvcehicqpbxkd.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRubnJpdWd0dmN2ZWhpY3FwYnhkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzEzMDkxOTUsImV4cCI6MjA4Njg4NTE5NX0.YwDJrOLc9jQqzDXBoPbGuvUKB-6fuy8ATvG8SvSjAjQ';

let supabaseClient = null;
try {
    supabaseClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
    console.log("Majestic Hint: Enjin Supabase Berjaya Dihisap!");
} catch (e) {
    console.error("Critical: Enjin Supabase Gagal!", e);
}

const fileInput = document.getElementById('file-input');
const mainCaptureBtn = document.getElementById('main-capture-btn');
const photoReview = document.getElementById('photo-review');
const photoVignette = document.getElementById('photo-vignette');
const wishPhase = document.getElementById('wish-phase');
const cameraPhase = document.getElementById('camera-phase');
const submitBtn = document.getElementById('submit-btn');
const wishText = document.getElementById('wish-text');
const wishGallery = document.getElementById('wish-gallery');
const retakeBtn = document.getElementById('retake-btn-v6');

// 🎶 Music Elements
const musicBtn = document.getElementById('music-controller');
const bgAudio = document.getElementById('bg-audio');

let capturedImage = null;
let isMusicPlaying = false;

/**
 * 🎵 ROYAL MUSIC ENGINE
 */
function toggleMusic() {
    if (isMusicPlaying) {
        bgAudio.pause();
        musicBtn.classList.remove('playing');
    } else {
        bgAudio.play().catch(e => console.log("Majestic Hint: Tetamu kena tekan skrin dulu!"));
        musicBtn.classList.add('playing');
    }
    isMusicPlaying = !isMusicPlaying;
}

musicBtn.addEventListener('click', toggleMusic);

// Auto-trigger music on first tap anywhere
document.addEventListener('click', () => {
    if (!isMusicPlaying && bgAudio.paused) {
        bgAudio.volume = 0;
        bgAudio.play().then(() => {
            // Smooth fade-in
            let vol = 0;
            const interval = setInterval(() => {
                if (vol < 0.5) {
                    vol += 0.05;
                    bgAudio.volume = vol;
                } else {
                    clearInterval(interval);
                }
            }, 100);
            musicBtn.classList.add('playing');
            isMusicPlaying = true;
        }).catch(() => { });
    }
}, { once: true });

/**
 * ✨ MAGIC DUST ENGINE (Majestic Grandeur)
 */
function createSparkles() {
    const container = document.getElementById('sparkle-container');
    const colors = ['#d4af37', '#b28d42', '#fff'];

    setInterval(() => {
        const s = document.createElement('div');
        s.className = 'sparkle';
        s.style.left = Math.random() * 100 + 'vw';
        s.style.top = (Math.random() * 100 + 100) + 'vh';
        s.style.background = colors[Math.floor(Math.random() * colors.length)];
        s.style.animationDuration = (Math.random() * 3 + 2) + 's';
        s.style.width = (Math.random() * 4 + 1) + 'px';
        s.style.height = s.style.width;

        container.appendChild(s);
        setTimeout(() => s.remove(), 5000);
    }, 300);
}

/**
 * 🏛️ PHASE TRANSITION
 */
function navigateToPhase(targetPhase) {
    const current = document.querySelector('.ux-phase.active');
    if (current) {
        current.style.opacity = '0';
        current.style.transform = 'translateY(-20px) scale(1.02)';

        setTimeout(() => {
            current.classList.remove('active');
            current.style.opacity = '';
            current.style.transform = '';

            targetPhase.classList.add('active');
            targetPhase.style.opacity = '1';
            targetPhase.style.transform = 'translateY(0) scale(1)';
            window.scrollTo({ top: 120, behavior: 'smooth' });
        }, 600);
    } else {
        targetPhase.classList.add('active');
        targetPhase.style.opacity = '1';
    }
}

/**
 * 📸 NATIVE CAPTURE FLOW (With Smart Normalization)
 */
mainCaptureBtn.addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (ev) => {
        const rawImg = new Image();
        rawImg.onload = () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = rawImg.width;
            canvas.height = rawImg.height;
            ctx.drawImage(rawImg, 0, 0);
            capturedImage = canvas.toDataURL('image/jpeg', 0.8);

            photoReview.innerHTML = `<img src="${capturedImage}">`;
            photoVignette.style.display = 'block';
            navigateToPhase(wishPhase);
        };
        rawImg.src = ev.target.result;
    };
    reader.readAsDataURL(file);
});

/**
 * 🔄 RETAKE FLOW
 */
retakeBtn.addEventListener('click', () => {
    capturedImage = null;
    photoReview.innerHTML = '';
    photoVignette.style.display = 'none';
    fileInput.value = '';
    navigateToPhase(cameraPhase);
    setTimeout(() => fileInput.click(), 800);
});

/**
 * 🏛️ THE GRAND OPENING
 */
function runRoyalSequence() {
    const loader = document.getElementById('grand-preloader');

    setTimeout(() => {
        loader.classList.add('loaded');
        document.body.classList.remove('is-loading');

        setTimeout(() => {
            document.getElementById('app').classList.add('is-active');
            createSparkles();
            loadGallery();
        }, 1200);
    }, 3500);
}

const lightbox = document.getElementById('royal-lightbox');
const lbImg = document.getElementById('lightbox-img');
const lbWish = lightbox.querySelector('.lightbox-wish-text');
const lbClose = lightbox.querySelector('.lightbox-close');

/**
 * 🎞️ ROYAL LIGHTBOX ENGINE
 */
function openLightbox(imgSrc, wishText) {
    lbImg.src = imgSrc;
    lbWish.innerText = wishText;
    lightbox.classList.add('active');
}

lbClose.addEventListener('click', () => lightbox.classList.remove('active'));
lightbox.querySelector('.lightbox-backdrop').addEventListener('click', () => lightbox.classList.remove('active'));

/**
 * ✨ GOLD-DUST TOUCH TRAIL ENGINE (V13.2 - Stable Motion)
 */
function initTouchTrail() {
    const canvas = document.getElementById('touch-trail');
    const ctx = canvas.getContext('2d');
    let points = [];

    const resize = () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    };
    window.addEventListener('resize', resize);
    resize();

    const addPoint = (x, y) => {
        points.push({
            x: x, y: y,
            age: 0,
            opacity: 1,
            size: Math.random() * 8 + 4,
            vx: (Math.random() - 0.5) * 2,
            vy: (Math.random() - 0.5) * 2
        });
    };

    window.addEventListener('mousemove', (e) => addPoint(e.clientX, e.clientY));
    window.addEventListener('touchmove', (e) => {
        const touch = e.touches[0];
        addPoint(touch.clientX, touch.clientY);
    });
    window.addEventListener('touchstart', (e) => {
        const touch = e.touches[0];
        addPoint(touch.clientX, touch.clientY);
    });

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        points.forEach((p, i) => {
            p.age += 1;
            p.opacity -= 0.015;
            p.x += p.vx;
            p.y += p.vy;

            if (p.opacity <= 0) {
                points.splice(i, 1);
                return;
            }
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size * p.opacity, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(212, 175, 55, ${p.opacity})`;
            ctx.shadowBlur = 15;
            ctx.shadowColor = '#d4af37';
            ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    draw();
}

/**
 * 🎺 FANFARE SOUNDSCAPE
 */
const sfx = {
    chime: new Audio('https://assets.mixkit.co/sfx/preview/mixkit-bright-notificaiton-primary-2980.mp3'),
    success: new Audio('https://assets.mixkit.co/sfx/preview/mixkit-magic-marimba-notification-2115.mp3')
};
function playSFX(name) {
    try {
        sfx[name].currentTime = 0;
        sfx[name].volume = 0.2;
        sfx[name].play();
    } catch (e) { }
}

/**
 * 💖 THE PRAISE OF HEARTS: Reaction System
 */
function spawnHearts(x, y) {
    for (let i = 0; i < 8; i++) {
        setTimeout(() => {
            const h = document.createElement('div');
            h.className = 'heart-particle';
            h.innerHTML = '✨';
            h.style.left = x + 'px';
            h.style.top = y + 'px';
            h.style.setProperty('--rot', (Math.random() * 30 - 15) + 'deg');
            h.style.marginLeft = (Math.random() * 60 - 30) + 'px';
            document.body.appendChild(h);
            setTimeout(() => h.remove(), 1000);
        }, i * 100);
    }
}

/**
 * 🖼️ ELEGANT MASONRY LOADER (V15 - Absolute Sovereign)
 */
async function loadGallery() {
    if (!supabaseClient) return;

    console.log("Majestic Hint: Sedang memuatkan memori...");
    try {
        const { data: wishes, error } = await supabaseClient
            .from('wishes')
            .select('*')
            .order('created_at', { ascending: false })
            .limit(16);

        if (error) {
            console.warn("Gallery Info: Mungkin SQL polisy belum setel.", error);
            wishGallery.innerHTML = '<div class="empty-gallery-hint">Awaiting the first royal blessing...</div>';
            return;
        }

        if (!wishes || wishes.length === 0) {
            wishGallery.innerHTML = '<div class="empty-gallery-hint">No memories found yet. Be the first!</div>';
            return;
        }

        wishGallery.innerHTML = '';
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('in-view');
            });
        }, { threshold: 0.1 });

        const seals = ['A', 'I', '❦', '✨', '❤️'];

        wishes.forEach((item, i) => {
            const rot = (Math.random() * 8 - 4).toFixed(2);
            const tile = document.createElement('div');
            tile.className = 'grand-tile bloom-reveal';
            tile.style.setProperty('--rot', `${rot}deg`);
            tile.style.transitionDelay = `${i * 80}ms`;

            const firstLetter = (item.wish && item.wish[0]) || seals[Math.floor(Math.random() * seals.length)];

            tile.innerHTML = `
                <div class="wax-seal">${firstLetter.toUpperCase()}</div>
                <div class="cameo-frame">
                    <div class="shiny-glass"></div>
                    <div class="tile-corner-decor tl"></div>
                    <div class="tile-corner-decor tr"></div>
                    <img src="${item.image_url}" loading="lazy" alt="Guest Wish">
                </div>
                <div class="grand-caption-v9">
                    <span class="quote-mark">❦</span>
                    <p>${item.wish || 'Sending love & blessings...'}</p>
                </div>
            `;

            tile.addEventListener('click', () => {
                playSFX('chime');
                openLightbox(item.image_url, item.wish);
            });

            tile.addEventListener('dblclick', (e) => spawnHearts(e.clientX, e.clientY));

            tile.addEventListener('mousemove', (e) => {
                const box = tile.getBoundingClientRect();
                const x = e.clientX - box.left - box.width / 2;
                const y = e.clientY - box.top - box.height / 2;
                tile.style.transform = `perspective(1000px) rotateX(${-y / 15}deg) rotateY(${x / 15}deg) scale(1.05) translateY(-10px)`;
            });

            tile.addEventListener('mouseleave', () => tile.style.transform = '');

            wishGallery.appendChild(tile);
            observer.observe(tile);
        });
    } catch (e) {
        console.error("Gallery Crash:", e);
        wishGallery.innerHTML = '<div class="empty-gallery-hint">The Archive is resting. Please try again later.</div>';
    }
}

/**
 * 🕰️ TIME-TRAVELER GREETING ENGINE
 */
function updateTimeGreeting() {
    const hr = new Date().getHours();
    const greetEl = document.getElementById('time-greeting');
    let msg = "Together with their families";
    if (hr < 12) {
        msg = "☀️ A Majestic Morning, Welcome to the Union of";
        greetEl.classList.add('greeting-morning');
    } else if (hr < 18) {
        msg = "🌤️ A Golden Afternoon, Welcome to the Union of";
    } else {
        msg = "🌙 An Enchanting Evening, Welcome to the Union of";
        greetEl.classList.add('greeting-evening');
    }
    greetEl.innerText = msg;
}

/**
 * 📜 ORACLE OF WISDOM: Royal Inspiration
 */
const royalWishes = [
    "Semoga bahtera cinta Atef & Ika berlayar di lautan rahmat dan kasih sayang hingga ke syurga.",
    "May your union be blessed with the fragrance of Jannah and the strength of eternal bonds.",
    "Dua hati, satu jiwa. Semoga diredhai Allah setiap langkah perjalanan kalian berdua.",
    "A journey of a thousand miles begins with this beautiful step. Sakinah, Mawaddah, Rahmah.",
    "Cinta kalian adalah bukti keagungan-Nya. Selamat menempuh alam perkahwinan yang barakah."
];

function runOracle() {
    const wishTxt = document.getElementById('wish-text');
    const randomWish = royalWishes[Math.floor(Math.random() * royalWishes.length)];
    wishTxt.value = '';
    let i = 0;
    const interval = setInterval(() => {
        if (i < randomWish.length) {
            wishTxt.value += randomWish[i];
            i++;
        } else {
            clearInterval(interval);
        }
    }, 40);
}

document.getElementById('oracle-btn').addEventListener('click', runOracle);

/**
 * 🌸 CELEBRATION BLOOM: Petal & Gold Rain
 */
function triggerBloom() {
    const container = document.getElementById('celebration-bloom');
    for (let i = 0; i < 60; i++) {
        setTimeout(() => {
            const isPetal = Math.random() > 0.4;
            const item = document.createElement('div');
            item.className = isPetal ? 'petal' : 'gold-flake';
            const startX = Math.random() * 100 + 'vw';
            const drift = (Math.random() * 200 - 100) + 'px';
            const duration = (Math.random() * 3 + 3) + 's';
            item.style.left = startX;
            item.style.setProperty('--drift', drift);
            item.style.animationDuration = duration;
            container.appendChild(item);
            setTimeout(() => item.remove(), 6000);
        }, i * 50);
    }
}

/**
 * ✨ PUBLISH WISH: Supabase Cloud Storage (V15 Reliable)
 */
async function uploadToSupabase() {
    const txt = wishText.value.trim();
    if (!txt || !capturedImage) {
        alert("Sila ambil gambar & tulis ucapan dulu!");
        return;
    }

    if (!supabaseClient) {
        alert("Sistem Supabase belum sedia. Sila refresh!");
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = "<span>SENDING...</span>";

    try {
        // 🎞️ 1. Convert Base64 directly to Blob (Safer Method)
        const base64Data = capturedImage.split(',')[1];
        const byteCharacters = atob(base64Data);
        const byteNumbers = new Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        const blob = new Blob([byteArray], { type: 'image/jpeg' });

        const fileName = `wish_${Date.now()}.jpg`;

        // 🎞️ 2. Upload to Storage
        const { data: uploadData, error: uploadError } = await supabaseClient.storage
            .from('memories')
            .upload(fileName, blob, {
                contentType: 'image/jpeg',
                cacheControl: '3600',
                upsert: false
            });

        if (uploadError) {
            alert("Error Upload Gambar: " + uploadError.message);
            throw uploadError;
        }

        // 🎞️ 3. Get URL
        const { data: { publicUrl } } = supabaseClient.storage
            .from('memories')
            .getPublicUrl(fileName);

        // 🎞️ 4. Save Wish
        const { error: dbError } = await supabaseClient
            .from('wishes')
            .insert([{ image_url: publicUrl, wish: txt }]);

        if (dbError) {
            alert("Error Simpan Wish: " + dbError.message);
            throw dbError;
        }

        // 🌸 SUCCESS
        submitBtn.innerHTML = "<span>PUBLISHED ❤️</span>";
        triggerBloom();
        playSFX('success');

        setTimeout(() => {
            navigateToPhase(cameraPhase);
            photoReview.innerHTML = '';
            photoVignette.style.display = 'none';
            wishText.value = '';
            fileInput.value = '';
            capturedImage = null;
            submitBtn.disabled = false;
            submitBtn.innerHTML = "<span>SHARE</span>";
            loadGallery();
        }, 3000);

    } catch (e) {
        console.error("Publish Failed:", e);
        submitBtn.disabled = false;
        submitBtn.innerHTML = "<span>RETRY</span>";
    }
}

submitBtn.addEventListener('click', uploadToSupabase);

document.addEventListener('DOMContentLoaded', () => {
    runRoyalSequence();
    updateTimeGreeting();
    initTouchTrail();
});
