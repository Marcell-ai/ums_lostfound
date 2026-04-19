<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMS Lost & Found Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ============================
         NAVBAR
    ============================= -->
    <nav class="navbar">
        <div class="nav-brand">
            <span class="brand-ums">UMS</span>
            <span class="brand-sep">|</span>
            <span class="brand-title">Lost &amp; Found Hub</span>
        </div>
        <div class="nav-links">
            <a href="index.php"    class="nav-link active">Home</a>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="add_item.php"  class="nav-link nav-cta">Report Item</a>
        </div>
    </nav>

    <!-- ============================
         HERO
    ============================= -->
    <header class="hero">

        <!-- Floating dark squares (like the video) -->
        <div class="float-el fe1"><span class="f-icon">🎒</span></div>
        <div class="float-el fe2"><span class="f-icon">🔑</span></div>
        <div class="float-el fe3"><span class="f-icon">💼</span></div>
        <div class="float-el fe4"><span class="f-icon">🪪</span></div>
        <div class="float-el fe5"><span class="f-icon">⌚</span></div>
        <div class="float-el fe6"><span class="f-icon">📱</span></div>
        <div class="float-el fe7"><span class="f-icon">🎧</span></div>

        <!-- Glow orbs -->
        <div class="glow-orb glow-purple" style="top:-200px; left:-150px;"></div>
        <div class="glow-orb glow-green"  style="bottom:0px; right:-100px;"></div>

        <!-- Content -->
        <div class="hero-content">
            <div class="hero-badge">Universiti Malaysia Sabah</div>
            <h1 class="hero-title">
                Find What<br>
                <span class="hero-accent">You've Lost.</span>
            </h1>
            <p class="hero-sub">
                The official digital Lost &amp; Found platform for UMS. 
                Report missing items, discover found belongings, and reconnect 
                the campus community — all in one place.
            </p>
            <div class="hero-actions">
                <a href="add_item.php"  class="btn btn-primary">📋 Report an Item</a>
                <a href="dashboard.php" class="btn btn-outline">🔍 Browse All Items</a>
            </div>
        </div>

        <!-- Social proof -->
        <div class="hero-social-proof">
            <div class="avatar-stack">
                <div class="av av1">A</div>
                <div class="av av2">N</div>
                <div class="av av3">T</div>
                <div class="av av4">S</div>
            </div>
            <span>Join <span class="proof-num">500+</span> UMS members using this platform</span>
        </div>

    </header>

    <!-- ============================
         STATS BAR
    ============================= -->
    <?php include 'db_config.php';
        $total  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM items"))['c'];
        $lost   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM items WHERE category='Lost'"))['c'];
        $found  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM items WHERE category='Found'"))['c'];
        $solved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM items WHERE status='Resolved'"))['c'];
    ?>
    <section class="stats-bar" style="padding: 40px 48px;">
        <div class="stats-inner">
            <div class="stat-item">
                <span class="stat-num total-color"><?php echo $total; ?></span>
                <span class="stat-label">Total Reports</span>
            </div>
            <div class="stat-item">
                <span class="stat-num lost-color"><?php echo $lost; ?></span>
                <span class="stat-label">Items Lost</span>
            </div>
            <div class="stat-item">
                <span class="stat-num found-color"><?php echo $found; ?></span>
                <span class="stat-label">Items Found</span>
            </div>
            <div class="stat-item">
                <span class="stat-num resolved-color"><?php echo $solved; ?></span>
                <span class="stat-label">Cases Resolved</span>
            </div>
        </div>
    </section>

    <!-- ============================
         ABOUT THE SYSTEM
    ============================= -->
    <section class="section about-section">
        <div class="section-inner">
            <span class="section-eyebrow">About the System</span>
            <div class="about-grid">
                <div>
                    <h2 class="section-title" style="margin-bottom:28px;">What is UMS<br>Lost &amp; Found Hub?</h2>
                    <div class="about-text">
                        <p>
                            The <strong>UMS Lost &amp; Found Hub</strong> is a centralised web-based platform
                            built to help the Universiti Malaysia Sabah community manage lost and found
                            items efficiently — replacing scattered notice-board postings with a clean,
                            searchable digital directory.
                        </p>
                        <p>
                            Whether you've lost your student ID in the library or found a set of keys near
                            the cafeteria, this system lets you log the item, attach a photo, and publish
                            it to the community within minutes.
                        </p>
                        <p>
                            Once claimed, reports are marked <strong>Resolved</strong> — keeping the directory
                            clean and relevant for the entire campus.
                        </p>
                    </div>
                    <div style="margin-top: 28px;">
                        <a href="dashboard.php" class="btn btn-outline btn-sm">View Dashboard →</a>
                    </div>
                </div>
                <div class="about-visual">
                   
					<img src="images/ums.jpeg" alt="ums" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </section>

    <!-- ============================
         HOW IT WORKS
    ============================= -->
    <section class="section" style="padding-top: 0;">
        <div class="section-inner">
            <span class="section-eyebrow">Simple Process</span>
            <h2 class="section-title">How It Works</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num">01 ————</div>
                    <span class="step-icon">📋</span>
                    <h3>Report the Item</h3>
                    <p>Fill in item details, location, contact info, and upload a photo if available.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">02 ————</div>
                    <span class="step-icon">🔍</span>
                    <h3>Browse &amp; Search</h3>
                    <p>Anyone on campus can search and filter through all active reports on the dashboard.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">03 ————</div>
                    <span class="step-icon">📞</span>
                    <h3>Contact &amp; Claim</h3>
                    <p>Spot your item? Contact the reporter directly using the info in the listing.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">04 ————</div>
                    <span class="step-icon">✅</span>
                    <h3>Mark Resolved</h3>
                    <p>Item returned? Update the report to Resolved so the community stays informed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================
         FEATURES
    ============================= -->
    <section class="section" style="padding-top:0;">
        <div class="section-inner">
            <span class="section-eyebrow">Key Features</span>
            <h2 class="section-title">Everything You Need</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="feat-icon">➕</span>
                    <h3>Add Reports</h3>
                    <p>Submit lost or found reports with full details, contact info, and image upload.</p>
                </div>
                <div class="feature-card">
                    <span class="feat-icon">📊</span>
                    <h3>Live Dashboard</h3>
                    <p>View all reports in a searchable and filterable real-time table.</p>
                </div>
                <div class="feature-card">
                    <span class="feat-icon">✏️</span>
                    <h3>Edit Reports</h3>
                    <p>Update status, fix details, or replace images on any existing report.</p>
                </div>
                <div class="feature-card">
                    <span class="feat-icon">🗑️</span>
                    <h3>Safe Deletion</h3>
                    <p>Remove outdated reports with confirmation prompts and auto image cleanup.</p>
                </div>
                <div class="feature-card">
                    <span class="feat-icon">🖼️</span>
                    <h3>Image Upload</h3>
                    <p>Attach real photos of items for faster, easier identification.</p>
                </div>
                <div class="feature-card">
                    <span class="feat-icon">🔐</span>
                    <h3>Input Sanitization</h3>
                    <p>All inputs are validated and sanitized to protect against SQL injection.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================
         GROUP MEMBERS
    ============================= -->
    <section class="section team-section">
        <div class="section-inner">
            <span class="section-eyebrow">Development Team</span>
            <h2 class="section-title">Meet the Group</h2>
            <p class="section-subtitle">CP0025 Web Programming — Group Assignment 2026</p>
            <div class="team-grid">

                <div class="member-card">
                    <div class="member-photo">
                      
                        <img src="images/marcell.jpeg" alt="Member 1">
                    </div>
                    <div class="member-role-badge">Logic Architect</div>
                    <div class="member-name">MARCELL STEPHEN</div>
                    <div class="member-id">FT25110149</div>
                    <div class="module-tag">dashboard.php</div>
                    <p class="member-desc">SELECT queries, table display, search and category filter.</p>
                </div>

                <div class="member-card">
                    <div class="member-photo">
						<img src="images/alexander.jpeg" alt="Member 2">
                    </div>
                    <div class="member-role-badge" style="background:rgba(34,255,136,0.1); border-color:rgba(34,255,136,0.3); color:var(--green);">Data Specialist</div>
                    <div class="member-name">ALEXANDER BRYAN</div>
                    <div class="member-id">FT25110097</div>
                    <div class="module-tag">style.css</div>
                    <p class="member-desc">Complete CSS stylesheet, responsive layout, navigation, and alerts.</p>
                </div>

                <div class="member-card">
                    <div class="member-photo">
                     
						<img src="images/roni.jpg" alt="Member 3">
                    </div>
                    <div class="member-role-badge" style="background:rgba(0,212,255,0.1); border-color:rgba(0,212,255,0.3); color:#00d4ff;">Modification Lead</div>
                    <div class="member-name">RONI AYMAN ROSLAN</div>
                    <div class="member-id">FT25160162</div>
                    <div class="module-tag">delete.php</div>
                    <p class="member-desc">DELETE logic, input sanitization, SQL injection protection, image cleanup.</p>
                </div>

                <div class="member-card">
                    <div class="member-photo">
                      
						<img src="images/yuanjackson.jpeg" alt="Member 4">
                    </div>
                    <div class="member-role-badge" style="background:rgba(255,71,87,0.12); border-color:rgba(255,71,87,0.3); color:var(--lost-red);">Security &amp; Maint.</div>
                    <div class="member-name">YUANJACKSON AROM</div>
                    <div class="member-id">FT25110077</div>
                    <div class="module-tag">edit.php</div>
                    <p class="member-desc">Fetching records into pre-filled forms, UPDATE logic, image replacement.</p>
                </div>

                <div class="member-card">
                    <div class="member-photo">
                      
						<img src="images/rafie.png" alt="Member 5">
                    </div>
                    <div class="member-role-badge" style="background:rgba(251,191,36,0.1); border-color:rgba(251,191,36,0.3); color:#fbbf24;">UI/UX &amp; Integration</div>
                    <div class="member-name">MOHD RAFIE IRFAN</div>
                    <div class="member-id">FT25160198</div>
                    <div class="module-tag">add_item.php</div>
                    <p class="member-desc">Form handling, data validation, image upload, and INSERT logic.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================
         CTA SECTION
    ============================= -->
    <section class="cta-section">
        <!-- Floating elements -->
        <div class="float-el" style="width:120px;height:120px;background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.2);bottom:10%;left:8%;--rot:rotate(-14deg);animation:floatA 8s ease-in-out infinite;border-radius:24%;position:absolute;"></div>
        <div class="float-el" style="width:80px;height:80px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);top:15%;right:10%;--rot:rotate(18deg);animation:floatB 10s ease-in-out infinite;border-radius:24%;position:absolute;"></div>
        <div class="glow-orb glow-green" style="bottom:-150px; left:50%; transform:translateX(-50%); opacity:0.12;"></div>

        <div class="cta-inner">
            <h2>Ready to Report or Search?</h2>
            <p>Help reconnect lost items with their owners. It only takes 2 minutes.</p>
            <div class="cta-buttons">
                <a href="add_item.php"  class="btn btn-primary">📋 Report an Item</a>
                <a href="dashboard.php" class="btn btn-white">🔍 Browse Dashboard</a>
            </div>
        </div>
    </section>

    <!-- ============================
         FOOTER
    ============================= -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand"><span class="brand-ums">UMS</span> Lost &amp; Found Hub</div>
            <p class="footer-sub">Universiti Malaysia Sabah &nbsp;|&nbsp; CP0025 Web Programming &nbsp;|&nbsp; Group Assignment 2026</p>
            <p class="footer-copy">&copy; 2026 All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
