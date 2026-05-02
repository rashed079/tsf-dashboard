# TSF Monitor Dashboard

**Real-time Tailings Storage Facility monitoring — Smart & Sustainable Mining Lab, Laurentian University**

[![Live Demo](https://img.shields.io/badge/Live%20Demo-GitHub%20Pages-00b4d8?style=flat-square)](https://rashed079.github.io/tsf-dashboard/)
[![WordPress](https://img.shields.io/badge/WordPress-6.7-21759B?style=flat-square&logo=wordpress)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-GPL%20v2-blue?style=flat-square)](LICENSE)

---

## Overview

A production WordPress dashboard delivering **live IoT sensor data visualization** for Tailings Storage Facility (TSF) monitoring at the Smart & Sustainable Mining Lab (SSML), Laurentian University, Sudbury, ON.

Built as a **custom PHP WordPress theme** with a REST API data ingestion layer, Chart.js time-series visualization, role-based access control, and full security hardening.

---

## Features

| Feature | Implementation |
|---|---|
| Live sensor polling | Custom WP REST API endpoints (`/tsf/v1/readings`) |
| Data visualization | Chart.js time-series, 30s auto-refresh |
| Alert system | ICOLD threshold logic → `wp_mail()` alerts |
| Role-based access | Admin / Researcher / Public roles |
| Security | Wordfence, Nonces, Application Passwords, SSL |
| SEO | Yoast SEO, schema.org, Core Web Vitals optimized |
| Deployment | WP-CLI, Git-based workflow |

---

## Sensor Streams

- Water Level (metres above datum)
- Pore Pressure (kPa)
- Seepage Rate (L/min)
- Temperature (°C)
- Turbidity (NTU)

---

## Architecture

```
IoT Sensors → POST every 30s
     ↓
WP REST API  /tsf/v1/readings  (nonce + application password auth)
     ↓
MySQL        wp_tsf_readings   (custom table via dbDelta)
     ↓
Chart.js     polls REST API every 30s, renders without page reload
     ↓
WordPress    role-based dashboard UI
```

---

## Repository Structure

```
tsf-dashboard/
├── wordpress-theme/          # Full WordPress theme (upload to wp-content/themes/)
│   ├── style.css             # Theme header + all CSS
│   ├── functions.php         # Theme setup, REST API, CPT, DB table
│   ├── front-page.php        # Main dashboard template
│   ├── header.php
│   ├── footer.php
│   ├── index.php             # Fallback template
│   └── js/
│       └── main.js           # Chart.js + REST API polling
│
└── github-pages/             # Static GitHub Pages demo
    └── index.html            # Self-contained demo (no PHP required)
```

---

## WordPress Theme Installation

1. Download / clone this repo
2. Copy `wordpress-theme/` folder to `wp-content/themes/tsf-monitor/`
3. Activate theme in **Appearance → Themes**
4. The `wp_tsf_readings` database table is created automatically on activation
5. Set your front page to **Reading → Static Page**
6. Configure REST API authentication (Application Passwords recommended for IoT devices)

### REST API Endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| GET | `/tsf/v1/readings?limit=20` | Public | Fetch latest readings |
| POST | `/tsf/v1/readings` | Authenticated | Ingest sensor reading |
| GET | `/tsf/v1/status` | Public | Current system status |

### Sensor POST Payload

```json
{
  "sensor_id": "SENSOR-01",
  "water_level": 74.2,
  "pore_pressure": 98.5,
  "seepage_rate": 2.1,
  "temperature": 12.4,
  "turbidity": 3.2
}
```

---

## Live Demo

The `github-pages/index.html` is a fully self-contained static demo that simulates the live data pipeline — suitable for immediate deployment on GitHub Pages.

**To deploy:**
1. Go to repo **Settings → Pages**
2. Set source to `github-pages/` folder (or copy `index.html` to repo root)
3. Your site will be live at `https://rashed079.github.io/tsf-dashboard/`

---

## Security

- All API write endpoints require authentication (nonce or Application Password)
- Input sanitized via WordPress APIs (`sanitize_text_field`, `absint`, `floatval`)
- WP generator tag removed from `<head>`
- `DISALLOW_FILE_EDIT` enforced in production `wp-config.php`
- Wordfence WAF configured with mining-sector security profile

---

## Developer

**Md Rashed Azad Chowdhury** — WordPress Web Developer · PMP® · CBAP® · ITIL V3 · ISO 27001

- 📍 Greater Sudbury, ON, Canada
- 📧 rashed06cse@gmail.com
- 💼 [linkedin.com/in/rashed-azad](https://linkedin.com/in/rashed-azad)
- 🎓 MSc Computational Science — Laurentian University

---

## License

GPL v2 or later — consistent with WordPress licensing.

---

*Smart & Sustainable Mining Lab · Laurentian University · Sudbury, ON · 2025*
