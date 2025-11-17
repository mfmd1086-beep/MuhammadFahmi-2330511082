<!doctype html>
<html lang="en">
<head>
    <script src="https://unpkg.com/lucide@latest"></script>
    <meta charset="UTF-8">
   <title>CV – <?= esc($biodata['nama']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP CSS (CSS FRAMEWORK) -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    <!-- Google Font + custom overrides for Elegant Dark theme -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Base neon stylesheet (kept for structure) -->
    <link rel="stylesheet" href="<?= base_url('css/neon.css') ?>">
    <!-- Custom theme overrides (Elegant Dark) -->
    <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
</head>
<body>
<div class="page-wrapper container-lg">

    <!-- NAVBAR -->
<header class="nav">
    <div class="nav-left">
        <!-- Membungkus nama dalam <a> agar bisa diklik dan scroll ke atas -->
        <a href="#top" class="nav-name">
            <?= esc($biodata['nama']) ?>
        </a>
    </div>
    <nav class="nav-links">
        <a href="#about">About</a>
        <a href="#education">Education</a>
        <a href="#skills">Skills</a>
        <a href="#experience">Experience</a>
        <a href="#projects">Projects</a>
        <a href="#contact">Contact</a>
    </nav>
</header>
    <!-- HERO -->
    <section class="hero">
        <div class="hero-left">
            <p class="hero-greeting">Hi, I'm</p>
            <div class="hero-title-rotator">
                <span class="hero-title hero-title-main">
                    <?= esc($biodata['nama']) ?>
                </span>

                <span class="hero-title hero-title-alt">
                    <?= esc($biodata['nama']) ?>
                </span>

            </div>

            <p class="hero-text">
                <?= esc($biodata['deskripsi']) ?>
            </p>

            <div class="hero-actions">
                <a href="#about" class="btn-outline">View Resume</a>
                <div class="hero-contact-mini">
                    <span class="contact-chip">
                        <span class="contact-icon">📧</span>
                        <?= esc($biodata['email']) ?>
                    </span>
                    <span class="contact-chip">
                        <span class="contact-icon">📞</span>
                        <?= esc($biodata['telepon']) ?>
                    </span>
                    <!-- IG pakai icon lucide (framework icon JS, bukan syarat soal, tapi rapi) -->
                    <span class="contact-chip">
                        <i data-lucide="instagram" class="contact-icon"></i>
                        @ambulambu
                    </span>
                </div>
            </div>
        </div>

        <div class="hero-photo-wrapper">
            <div class="neon-card-photo">
                <img src="<?= base_url('images/' . $biodata['foto']) ?>" alt="Foto Profil">

            </div>  
        </div>
    </section>

    <!-- ABOUT (TENTANG SAYA) -->
<section id="about" class="section">
    <h2 class="section-title mb-3">Tentang Saya</h2>
    <div class="about-grid">
        <div class="about-stat">
            <div class="about-stat-number">Etos Kerja</div>
            <div class="about-stat-label">
                Disiplin, pekerja keras,<br>dan selalu berusaha memberikan yang terbaik dalam setiap tugas. Saya memiliki motivasi tinggi untuk berkembang dan belajar hal-hal baru di dunia teknologi.
            </div>
        </div>
        <div class="about-stat">
            <div class="about-stat-number">Keahlian Teknologi</div>
            <div class="about-stat-label">
                Fokus di Cybersecurity, Network & Cloud Security, serta penguasaan berbagai bahasa pemrograman seperti Python, PHP, dan JavaScript. Saya juga memiliki keahlian dalam pengelolaan database dan pengembangan aplikasi berbasis web.
            </div>
        </div>
        <div class="about-stat">
            <div class="about-stat-number">Aktif di Komunitas dan Proyek</div>
            <div class="about-stat-label">
                Terlibat aktif dalam organisasi, proyek teknologi, dan kegiatan kampus yang berfokus pada pengembangan teknologi dan keamanan dunia maya. Saya berkomitmen untuk selalu memberikan kontribusi positif dalam setiap proyek yang saya ikuti.
            </div>
        </div>
    </div>
</section>


    <!-- EDUCATION -->
    <section id="education" class="section">
        <h2 class="section-title mb-3">Education</h2>
        <div class="timeline">
            <?php foreach ($pendidikan as $edu): ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h3><?= esc($edu['jenjang']) ?> – <?= esc($edu['institusi']) ?></h3>
                            <span class="timeline-year">
                                <?= esc($edu['tahun_mulai']) ?> – <?= esc($edu['tahun_selesai']) ?>
                            </span>
                        </div>
                        <p class="timeline-desc"><?= esc($edu['keterangan']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SKILLS & EXPERTISE -->
    <section id="skills" class="section skills-section">
        <h2 class="section-title mb-3">Skills &amp; Expertise</h2>

        <?php
            // filter: buang skill yang ada kata "linux"
            $skillsFiltered = [];
            foreach ($keahlian as $skill) {
                if (stripos($skill['nama_keahlian'], 'linux') !== false) continue;
                $skillsFiltered[] = $skill;
            }
            // ambil maksimal 6 skill
            $skillsFiltered = array_slice($skillsFiltered, 0, 6);
        ?>

        <div class="skills-grid">
            <?php foreach ($skillsFiltered as $skill): ?>

                <?php
                    // mapping icon
                    $icons = [
                        'python'     => 'code',
                        'php'        => 'file-code',
                        'javascript' => 'cpu',
                        'network'    => 'share-2',
                        'security'   => 'shield',
                        'cloud'      => 'cloud',
                        'database'   => 'database',
                        'devops'     => 'settings',
                        'cyber'      => 'shield-check',
                    ];

                    $name = strtolower($skill['nama_keahlian']);
                    $icon = 'sparkles';

                    foreach ($icons as $key => $val) {
                        if (strpos($name, $key) !== false) {
                            $icon = $val;
                            break;
                        }
                    }

                    // warna bar level
                    $levelClass = 'skill-level-medium';
                    if ($skill['tingkat'] === 'Mahir') $levelClass = 'skill-level-high';
                    if ($skill['tingkat'] === 'Dasar')  $levelClass = 'skill-level-low';
                ?>

                <article class="neon-card skill-card skill-item">
                    <div class="skill-icon">
                        <i data-lucide="<?= $icon ?>"></i>
                    </div>

                    <div class="skill-header">
                        <h3 class="card-title"><?= esc($skill['nama_keahlian']) ?></h3>
                        <span class="skill-level-badge"><?= esc($skill['tingkat']) ?></span>
                    </div>

                    <p class="card-text skill-text">
                        Keahlian yang saya pelajari melalui proyek, tugas kuliah,
                        dan pembelajaran mandiri.
                    </p>

                    <div class="skill-level">
                        <span class="skill-level-label">Proficiency</span>
                        <div class="skill-level-bar">
                            <span class="skill-level-fill <?= $levelClass ?>"></span>
                        </div>
                    </div>
                </article>

            <?php endforeach; ?>
        </div>
    </section>

    <!-- EXPERIENCE -->
    <section id="experience" class="section">
        <h2 class="section-title mb-3">Experience</h2>
        <div class="timeline">
            <?php foreach ($pengalaman as $exp): ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h3><?= esc($exp['posisi']) ?></h3>
                            <span class="timeline-year">
                                <?= esc($exp['tahun_mulai']) ?> – <?= esc($exp['tahun_selesai']) ?>
                            </span>
                        </div>
                        <p class="timeline-subtitle"><?= esc($exp['instansi']) ?></p>
                        <p class="timeline-desc"><?= esc($exp['deskripsi']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- PROJECTS -->
   <!-- PROJECTS (Fitur Web Sekolah) -->
<section id="projects" class="section">
    <h2 class="section-title mb-3">Fitur Web Sekolah</h2>

    <?php
        $projectsFiltered = [];
        foreach ($portofolio as $proj) {
            $projectsFiltered[] = $proj;
        }
        // Maksimal 4 (2x2)
        $projectsFiltered = array_slice($projectsFiltered, 0, 4);
    ?>

    <div class="projects-grid">
        <?php foreach ($projectsFiltered as $proj): ?>
            <article class="neon-card project-card">

                <?php if (!empty($proj['gambar'])): ?>
                    <div class="project-thumb">
                        <img src="<?= base_url('images/projects/' . $proj['gambar']) ?>" alt="Project Thumbnail">
                    </div>
                <?php endif; ?>

                <h3 class="card-title"><?= esc($proj['judul']) ?></h3>
                <p class="card-text"><?= esc($proj['deskripsi']) ?></p>

                <?php if (!empty($proj['link'])): ?>
                    <a href="<?= esc($proj['link']) ?>" class="card-link" target="_blank" rel="noreferrer">
                        Lihat Fitur →
                    </a>
                <?php endif; ?>

            </article>
        <?php endforeach; ?>
    </div>
</section>
                    

    <!-- CONTACT -->
    <section id="contact" class="section">
        <h2 class="section-title mb-3">Contact Me</h2>
        <div class="contact-grid">
            <div class="contact-info">
                <p>You can reach me through the following contacts.</p>
                <ul class="contact-list">
                    <li><strong>Email:</strong> <?= esc($biodata['email']) ?></li>
                    <li><strong>Phone:</strong> <?= esc($biodata['telepon']) ?></li>
                    <li><strong>Address:</strong> <?= esc($biodata['alamat']) ?></li>
                </ul>
            </div>
            <form class="contact-form">
                <div class="form-row">
                    <input type="text" placeholder="Your Name">
                    <input type="email" placeholder="Your Email">
                </div>
                        <textarea rows="4" placeholder="Message (mockup only)"></textarea>
                        <button type="button" class="btn-outline btn-full">Send Message</button>
            </form>
        </div>
    </section>

    <footer class="footer">
        <p>© <?= date('Y') ?> <?= esc($biodata['nama']) ?> · Cybersecurity Enthusiast</p>
    </footer>

</div>

<!-- BOOTSTRAP JS BUNDLE (opsional tapi rapi) -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
></script>

<!-- render icon lucide -->
<script>
    lucide.createIcons();
</script>
</body>
</html>
