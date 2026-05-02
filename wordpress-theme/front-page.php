<?php get_header(); ?>

<main id="main-content" role="main">

<!-- ===== HERO ===== -->
<section id="hero" aria-labelledby="hero-title">
  <div class="container">
    <div class="hero-grid">
      <div class="hero-content">
        <div class="hero-tag">
          <span class="pulse-dot" aria-hidden="true"></span>
          Live Sensor Monitoring · Sudbury, ON
        </div>
        <h1 class="hero-title" id="hero-title">
          TSF Real-Time<br><span>Monitoring</span><br>Dashboard
        </h1>
        <p class="hero-desc">
          A production WordPress dashboard delivering live Tailings Storage Facility sensor data visualization — 
          built for the Smart &amp; Sustainable Mining Lab at Laurentian University. Custom PHP theme, REST API 
          integration, and role-based access control.
        </p>
        <div class="hero-actions">
          <a href="#features" class="btn btn-primary">Explore Features ↓</a>
          <a href="https://github.com/rashed079/tsf-dashboard" class="btn btn-outline" target="_blank" rel="noopener">View on GitHub ↗</a>
        </div>
      </div>
      <div class="hero-visual" aria-hidden="true">
        <div class="dashboard-mockup">
          <div class="mockup-bar">
            <div class="mockup-dot" style="background:#e63946"></div>
            <div class="mockup-dot" style="background:#e9c46a;margin-left:4px"></div>
            <div class="mockup-dot" style="background:#2dc653;margin-left:4px"></div>
            <span class="mockup-title">TSF Monitor — SSML Dashboard</span>
          </div>
          <div class="mockup-body">
            <div class="mockup-stats">
              <div class="m-stat">
                <div class="m-stat-val" style="color:#2dc653">74.2m</div>
                <div class="m-stat-lbl">Water Level</div>
              </div>
              <div class="m-stat">
                <div class="m-stat-val" style="color:#e9c46a">98 kPa</div>
                <div class="m-stat-lbl">Pore Pressure</div>
              </div>
              <div class="m-stat">
                <div class="m-stat-val" style="color:#00b4d8">2.1 L/m</div>
                <div class="m-stat-lbl">Seepage Rate</div>
              </div>
            </div>
            <div class="mockup-chart">
              <div class="chart-label">WATER LEVEL — 30D TREND</div>
              <canvas id="hero-chart" class="bar-chart-svg" height="70"></canvas>
            </div>
            <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
              <span class="status-badge status-safe">● System Normal</span>
              <span class="status-badge status-warn">⚠ Sensor 3 Drift</span>
              <span style="font-size:10px;color:var(--c-muted);margin-left:auto;align-self:center">Updated 30s ago</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FEATURES ===== -->
<section id="features" class="section">
  <div class="container">
    <p class="section-label">What It Does</p>
    <h2 class="section-title">Built for <span>Real-World</span> Mining Safety</h2>
    <p class="section-desc">
      Every component was designed for the operational realities of tailings facility monitoring —
      real data, real thresholds, real consequences.
    </p>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">📡</div>
        <h3 class="feature-title">Live IoT Sensor Integration</h3>
        <p class="feature-desc">REST API endpoints receive readings from IoT hardware every 30 seconds. Water level, pore pressure, seepage rate, turbidity, and temperature streams rendered in real time.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📊</div>
        <h3 class="feature-title">Chart.js Data Visualization</h3>
        <p class="feature-desc">Interactive time-series charts built with Chart.js, polling the WordPress REST API without page reload. 30-day trend lines, threshold markers, and anomaly highlighting.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🔐</div>
        <h3 class="feature-title">Role-Based Access Control</h3>
        <p class="feature-desc">Custom WordPress user roles: Researchers see raw data exports; Public users see summarized dashboards; Admins access sensor configuration and alert thresholds.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">⚠️</div>
        <h3 class="feature-title">Threshold Alert System</h3>
        <p class="feature-desc">ICOLD-inspired threshold logic flags readings as Safe / Warning / Critical. Email alerts dispatched via wp_mail() when thresholds are crossed. Alert history logged to custom DB table.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🛡️</div>
        <h3 class="feature-title">Security Hardening</h3>
        <p class="feature-desc">Wordfence firewall, SSL enforcement, WP-CLI deployment, nonce-protected API endpoints, application passwords for IoT devices, and WP_DEBUG disabled in production.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🔍</div>
        <h3 class="feature-title">SEO & Performance</h3>
        <p class="feature-desc">Yoast SEO configuration, Core Web Vitals optimization (lazy loading, caching, image compression), schema.org markup for the research lab, and Google Search Console integration.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== TECH STACK ===== -->
<section id="stack" class="section--sm" style="background:var(--c-surface);border-top:1px solid var(--c-border);border-bottom:1px solid var(--c-border)">
  <div class="container">
    <p class="section-label">Technology Stack</p>
    <h2 class="section-title" style="font-size:1.5rem">Built with production-grade tools</h2>
    <div class="tech-grid">
      <?php
      $techs = [
        'WordPress 6.7','Custom PHP Theme','REST API','MySQL','Chart.js',
        'Elementor','JavaScript ES6+','HTML5 / CSS3','Wordfence','Yoast SEO',
        'WP-CLI','Git / GitHub','SSL / HTTPS','IoT Sensor Integration','Role-Based Access',
        'Core Web Vitals','WP REST API','Application Passwords',
      ];
      foreach ($techs as $t): ?>
        <span class="tech-pill"><span class="tech-pill-dot"></span><?php echo esc_html($t); ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== ARCHITECTURE ===== -->
<section id="architecture" class="section">
  <div class="container">
    <p class="section-label">System Architecture</p>
    <h2 class="section-title">How Data Flows from <span>Sensor to Screen</span></h2>
    <p class="section-desc">
      A clean separation between data ingestion, storage, and presentation — built on WordPress APIs throughout.
    </p>
    <div class="arch-flow" role="img" aria-label="Architecture diagram: IoT Sensors post to REST API, stored in MySQL, Chart.js reads via REST, displayed in WordPress dashboard">
      <div class="arch-node">
        <div class="arch-node-icon">🔌</div>
        <div class="arch-node-title">IoT Sensors</div>
        <div class="arch-node-desc">Hardware nodes<br>POST every 30s</div>
      </div>
      <div class="arch-arrow"></div>
      <div class="arch-node">
        <div class="arch-node-icon">🌐</div>
        <div class="arch-node-title">WP REST API</div>
        <div class="arch-node-desc">Custom endpoint<br>/tsf/v1/readings</div>
      </div>
      <div class="arch-arrow"></div>
      <div class="arch-node">
        <div class="arch-node-icon">🗄️</div>
        <div class="arch-node-title">MySQL DB</div>
        <div class="arch-node-desc">tsf_readings<br>custom table</div>
      </div>
      <div class="arch-arrow"></div>
      <div class="arch-node">
        <div class="arch-node-icon">📊</div>
        <div class="arch-node-title">Chart.js</div>
        <div class="arch-node-desc">Polls API<br>every 30s</div>
      </div>
      <div class="arch-arrow"></div>
      <div class="arch-node">
        <div class="arch-node-icon">🖥️</div>
        <div class="arch-node-title">Dashboard</div>
        <div class="arch-node-desc">Role-based<br>WordPress UI</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== METRICS ===== -->
<section id="metrics" class="section" style="background:var(--c-surface);border-top:1px solid var(--c-border)">
  <div class="container">
    <p class="section-label">Project Outcomes</p>
    <h2 class="section-title">Measurable <span>Impact</span></h2>
    <div class="metrics-grid">
      <div class="metric-card">
        <div class="metric-num">30s</div>
        <div class="metric-unit">refresh interval</div>
        <div class="metric-label">Live sensor data polled & rendered without page reload</div>
      </div>
      <div class="metric-card">
        <div class="metric-num">5+</div>
        <div class="metric-unit">sensor streams</div>
        <div class="metric-label">Water level, pore pressure, seepage, temperature, turbidity</div>
      </div>
      <div class="metric-card">
        <div class="metric-num">3</div>
        <div class="metric-unit">user roles</div>
        <div class="metric-label">Admin, Researcher, Public — each with scoped access</div>
      </div>
      <div class="metric-card">
        <div class="metric-num">98+</div>
        <div class="metric-unit">PageSpeed score</div>
        <div class="metric-label">Core Web Vitals optimized — lazy loading, caching, compression</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== ABOUT ===== -->
<section id="about" class="section">
  <div class="container">
    <div class="about-grid">
      <div>
        <p class="section-label">The Developer</p>
        <h2 class="section-title">Md Rashed Azad <span>Chowdhury</span></h2>
        <p class="about-bio">
          WordPress Web Developer and PMP®-certified project manager based in Sudbury, ON. 
          Currently pursuing MSc in Computational Science at Laurentian University while building 
          production web applications for the Smart &amp; Sustainable Mining Lab and CROSH research labs.
        </p>
        <p class="about-bio">
          12+ years of enterprise IT and digital project delivery across banking and financial services 
          (City Bank PLC, BRAC Bank PLC), bringing both technical depth and financial-sector discipline 
          to every web project.
        </p>
        <div class="cert-list">
          <span class="cert-badge">PMP®</span>
          <span class="cert-badge">CBAP®</span>
          <span class="cert-badge">ITIL V3</span>
          <span class="cert-badge">ISO 27001</span>
          <span class="cert-badge">Lean Six Sigma BB</span>
        </div>
      </div>
      <div class="contact-card">
        <div class="contact-row">
          <div class="contact-icon">📍</div>
          <div>
            <div class="contact-info-label">Location</div>
            <div class="contact-info-val">Greater Sudbury, ON, Canada</div>
          </div>
        </div>
        <div class="contact-row">
          <div class="contact-icon">📧</div>
          <div>
            <div class="contact-info-label">Email</div>
            <div class="contact-info-val"><a href="mailto:rashed06cse@gmail.com">rashed06cse@gmail.com</a></div>
          </div>
        </div>
        <div class="contact-row">
          <div class="contact-icon">💼</div>
          <div>
            <div class="contact-info-label">LinkedIn</div>
            <div class="contact-info-val"><a href="https://linkedin.com/in/rashed-azad" target="_blank" rel="noopener">linkedin.com/in/rashed-azad</a></div>
          </div>
        </div>
        <div class="contact-row">
          <div class="contact-icon">🐙</div>
          <div>
            <div class="contact-info-label">GitHub</div>
            <div class="contact-info-val"><a href="https://github.com/rashed079" target="_blank" rel="noopener">github.com/rashed079</a></div>
          </div>
        </div>
        <div class="contact-row">
          <div class="contact-icon">🎓</div>
          <div>
            <div class="contact-info-label">Institution</div>
            <div class="contact-info-val">Laurentian University — MSc Computational Science</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</main>

<?php get_footer(); ?>
