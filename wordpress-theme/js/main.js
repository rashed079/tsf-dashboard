/**
 * TSF Monitor — main.js
 * Chart.js dashboard + REST API polling
 * @author Md Rashed Azad Chowdhury
 */
(function () {
  'use strict';

  // ── Hero chart (mockup sparkline) ─────────────────────────
  function initHeroChart() {
    const canvas = document.getElementById('hero-chart');
    if (!canvas || typeof Chart === 'undefined') return;

    const labels = Array.from({ length: 30 }, (_, i) => `Day ${i + 1}`);
    const data   = [70.1,70.3,70.8,71.2,71.0,71.5,72.0,71.8,72.3,72.8,
                    73.0,73.2,73.5,73.1,73.6,74.0,73.8,74.2,74.5,74.3,
                    74.1,74.6,74.8,74.2,73.9,74.1,74.5,74.2,74.0,74.2];

    new Chart(canvas, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          data,
          borderColor: '#00b4d8',
          backgroundColor: 'rgba(0,180,216,0.08)',
          borderWidth: 2,
          pointRadius: 0,
          fill: true,
          tension: 0.4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
        scales: {
          x: { display: false },
          y: {
            display: true,
            grid: { color: 'rgba(0,180,216,0.06)' },
            ticks: { color: '#8ab4c2', font: { size: 9 }, maxTicksLimit: 4 },
          }
        },
        animation: { duration: 1200, easing: 'easeInOutQuart' },
      }
    });
  }

  // ── Live dashboard chart (REST API polling) ───────────────
  let liveChart = null;

  function initLiveChart() {
    const canvas = document.getElementById('live-chart');
    if (!canvas || typeof Chart === 'undefined') return;

    liveChart = new Chart(canvas, {
      type: 'line',
      data: {
        labels: [],
        datasets: [
          {
            label: 'Water Level (m)',
            data: [], borderColor: '#00b4d8', backgroundColor: 'rgba(0,180,216,0.06)',
            borderWidth: 2, pointRadius: 3, fill: true, tension: 0.4, yAxisID: 'y',
          },
          {
            label: 'Pore Pressure (kPa)',
            data: [], borderColor: '#e9c46a', backgroundColor: 'rgba(233,196,106,0.06)',
            borderWidth: 2, pointRadius: 3, fill: false, tension: 0.4, yAxisID: 'y1',
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
          legend: {
            labels: { color: '#caf0f8', font: { size: 12 }, boxWidth: 12, padding: 16 }
          }
        },
        scales: {
          x: { ticks: { color: '#8ab4c2', font: { size: 10 } }, grid: { color: 'rgba(0,180,216,0.06)' } },
          y: {
            type: 'linear', position: 'left',
            ticks: { color: '#00b4d8' }, grid: { color: 'rgba(0,180,216,0.06)' },
            title: { display: true, text: 'Water Level (m)', color: '#00b4d8', font: { size: 11 } }
          },
          y1: {
            type: 'linear', position: 'right',
            ticks: { color: '#e9c46a' }, grid: { drawOnChartArea: false },
            title: { display: true, text: 'Pore Pressure (kPa)', color: '#e9c46a', font: { size: 11 } }
          }
        }
      }
    });
  }

  function updateLiveChart(readings) {
    if (!liveChart || !readings.length) return;
    const labels = readings.map(r => {
      const d = new Date(r.recorded_at);
      return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }).reverse();
    const wl = readings.map(r => parseFloat(r.water_level)).reverse();
    const pp = readings.map(r => parseFloat(r.pore_pressure)).reverse();

    liveChart.data.labels = labels;
    liveChart.data.datasets[0].data = wl;
    liveChart.data.datasets[1].data = pp;
    liveChart.update('none');
  }

  // ── REST API polling ──────────────────────────────────────
  function fetchStatus() {
    if (typeof tsfData === 'undefined') return;
    fetch(tsfData.restUrl + 'status', {
      headers: { 'X-WP-Nonce': tsfData.nonce }
    })
      .then(r => r.json())
      .then(data => {
        updateStatusBadge(data.status);
        updateStatCards(data.latest);
      })
      .catch(console.error);
  }

  function fetchReadings() {
    if (typeof tsfData === 'undefined') return;
    fetch(tsfData.restUrl + 'readings?limit=20', {
      headers: { 'X-WP-Nonce': tsfData.nonce }
    })
      .then(r => r.json())
      .then(data => updateLiveChart(data))
      .catch(console.error);
  }

  function updateStatusBadge(status) {
    const badge = document.getElementById('status-badge');
    if (!badge) return;
    const map = {
      safe:     { cls: 'status-safe',   text: '● System Normal' },
      warning:  { cls: 'status-warn',   text: '⚠ Warning Threshold' },
      critical: { cls: 'status-danger', text: '🚨 Critical Alert' },
    };
    const s = map[status] || map.safe;
    badge.className = 'status-badge ' + s.cls;
    badge.textContent = s.text;
  }

  function updateStatCards(latest) {
    if (!latest) return;
    const map = {
      'stat-wl': parseFloat(latest.water_level).toFixed(1) + 'm',
      'stat-pp': parseFloat(latest.pore_pressure).toFixed(0) + ' kPa',
      'stat-sr': parseFloat(latest.seepage_rate).toFixed(1) + ' L/m',
    };
    Object.entries(map).forEach(([id, val]) => {
      const el = document.getElementById(id);
      if (el) el.textContent = val;
    });
  }

  // ── Smooth scroll for nav links ───────────────────────────
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', e => {
        const target = document.querySelector(link.getAttribute('href'));
        if (!target) return;
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }

  // ── Header scroll effect ──────────────────────────────────
  function initHeaderScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;
    window.addEventListener('scroll', () => {
      header.style.background = window.scrollY > 60
        ? 'rgba(10,10,30,0.97)'
        : 'rgba(10,10,30,0.85)';
    }, { passive: true });
  }

  // ── Entry point ───────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', () => {
    initHeroChart();
    initLiveChart();
    initSmoothScroll();
    initHeaderScroll();

    if (typeof tsfData !== 'undefined') {
      fetchStatus();
      fetchReadings();
      setInterval(fetchStatus, 30000);
      setInterval(fetchReadings, 30000);
    }
  });
})();
