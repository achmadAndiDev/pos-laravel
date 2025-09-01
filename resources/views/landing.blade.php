<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Majeko — Profil Perusahaan</title>
    <meta name="description" content="Majeko — Solusi sederhana dan tepercaya untuk kebutuhan bisnis Anda." />
    <style>
        :root{
            --bg:#0f172a; /* slate-900 */
            --card:#111827ee; /* gray-900 */
            --fg:#e5e7eb; /* gray-200 */
            --muted:#9ca3af; /* gray-400 */
            --brand:#22c55e; /* green-500 */
            --brand-2:#86efac; /* green-300 */
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0; font-family:ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, "Apple Color Emoji", "Segoe UI Emoji";
            background:
                radial-gradient(800px 400px at 10% 10%, #16a34a22, transparent 60%),
                radial-gradient(800px 400px at 90% 20%, #22c55e22, transparent 60%),
                linear-gradient(180deg, #0b1220, var(--bg));
            color:var(--fg);
        }
        .container{max-width:1000px;margin:0 auto;padding:24px}
        header{
            display:flex;align-items:center;justify-content:space-between;gap:16px;padding:12px 0
        }
        .brand{display:flex;align-items:center;gap:12px;font-weight:700;font-size:20px}
        .brand-badge{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--brand),var(--brand-2));display:grid;place-items:center;color:#052e16;font-weight:900}
        .nav a{color:var(--fg);text-decoration:none;opacity:.85;margin-left:16px}
        .nav a:hover{opacity:1}
        .hero{padding:64px 0 40px;display:grid;gap:16px}
        .title{font-size:clamp(28px,4vw,44px);line-height:1.1;margin:0}
        .subtitle{color:var(--muted);max-width:60ch}
        .cta{display:flex;gap:12px;flex-wrap:wrap;margin-top:8px}
        .btn{padding:12px 16px;border-radius:10px;border:1px solid #1f2937;background:var(--card);color:var(--fg);text-decoration:none}
        .btn-primary{background:linear-gradient(135deg,var(--brand),var(--brand-2));color:#052e16;border:0;font-weight:700}
        .grid{display:grid;gap:16px;grid-template-columns:repeat(12,1fr)}
        .card{grid-column:span 12;background:var(--card);border:1px solid #1f2937;border-radius:14px;padding:20px}
        .features{grid-column:span 12;display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
        .feature{background:#0b1220aa;border:1px solid #1f2937;border-radius:12px;padding:16px}
        footer{padding:32px 0;color:var(--muted);font-size:14px}
        @media (min-width:768px){.card{grid-column:span 8}.side{grid-column:span 4}}
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="brand">
                <div class="brand-badge">M</div>
                Majeko
            </div>
            <nav class="nav">
                <a href="#tentang">Tentang</a>
                <a href="#layanan">Layanan</a>
                <a href="#kontak">Kontak</a>
            </nav>
        </header>

        <section class="hero">
            <h1 class="title">Majeko — Solusi sederhana dan tepercaya untuk pertumbuhan bisnis Anda.</h1>
            <p class="subtitle">Kami membantu bisnis kecil dan menengah mengelola operasional dengan lebih efektif melalui pendekatan yang ringkas, modern, dan mudah digunakan.</p>
            <div class="cta">
                <a class="btn btn-primary" href="/kasir">Masuk ke Kasir</a>
                <a class="btn" href="#tentang">Pelajari Lebih Lanjut</a>
            </div>
        </section>

        <section id="tentang" class="grid">
            <article class="card">
                <h2 style="margin:0 0 8px 0">Tentang Majeko</h2>
                <p style="color:var(--muted);margin:0">Majeko adalah perusahaan yang berfokus pada solusi perangkat lunak yang sederhana namun kuat. Kami percaya teknologi harus mempermudah, bukan mempersulit. Dengan tim kecil yang gesit, kami membangun produk yang cepat, aman, dan mudah diadopsi.</p>
            </article>
            <aside class="side">
                <div class="features">
                    <div class="feature">
                        <strong>Kualitas</strong>
                        <p style="margin:6px 0 0;color:var(--muted)">Standar tinggi dalam desain, kinerja, dan keamanan.</p>
                    </div>
                    <div class="feature">
                        <strong>Kepercayaan</strong>
                        <p style="margin:6px 0 0;color:var(--muted)">Transparan, responsif, dan fokus pada hasil.</p>
                    </div>
                    <div class="feature">
                        <strong>Inovasi</strong>
                        <p style="margin:6px 0 0;color:var(--muted)">Iterasi cepat untuk solusi yang relevan dan berkelanjutan.</p>
                    </div>
                </div>
            </aside>
        </section>

        <section id="layanan" class="grid" style="margin-top:16px">
            <div class="card" style="grid-column:span 12">
                <h2 style="margin:0 0 8px 0">Layanan Singkat</h2>
                <ul style="margin:0;color:var(--muted);padding-left:18px">
                    <li>Pengembangan aplikasi web yang simpel dan cepat diimplementasikan.</li>
                    <li>Integrasi sistem untuk menyatukan proses bisnis.</li>
                    <li>Dukungan dan pemeliharaan berkelanjutan.</li>
                </ul>
            </div>
        </section>

        <section id="kontak" class="grid" style="margin-top:16px">
            <div class="card" style="grid-column:span 12;display:flex;flex-wrap:wrap;align-items:center;gap:12px;justify-content:space-between">
                <div>
                    <h3 style="margin:0 0 6px 0">Ingin berdiskusi?</h3>
                    <p style="margin:0;color:var(--muted)">Kami siap membantu menyesuaikan solusi untuk kebutuhan Anda.</p>
                </div>
                <div class="cta">
                    <a class="btn btn-primary" href="mailto:halo@majeko.id">halo@majeko.id</a>
                    <a class="btn" href="https://wa.me/6281234567890" target="_blank" rel="noopener">WhatsApp</a>
                </div>
            </div>
        </section>

        <footer>
            © <span id="y"></span> Majeko. Semua hak dilindungi.
        </footer>
    </div>
    <script>
        document.getElementById('y').textContent = new Date().getFullYear();
    </script>
</body>
</html>