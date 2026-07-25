// Tambahkan fungsi hashCode dan fungsi warna (tetap sama)
String.prototype.hashCode = function () {
    let hash = 0;
    for (let i = 0; i < this.length; i++) {
        const char = this.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash;
    }
    return hash;
};

function generateColor(index, total) {
    const hue = (index * 360) / (total || 1);
    return `hsla(${hue}, 70%, 65%, 0.7)`;
}

function generateBorderColor(index, total) {
    const hue = (index * 360) / (total || 1);
    return `hsla(${hue}, 70%, 50%, 1)`;
}

// Fungsi inisialisasi chart
window.initDefectChart = function (defectData, currentFY) {
    console.log("Initializing defect chart with data:", defectData);
    const monthNames = ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar"];

    // Fungsi helper yang sama dengan rr-chart.js
    function formatFiscalPeriod(fy_n) {
        if (!fy_n) return '';
        const [year, month] = fy_n.split('-');
        return 'FY' + year.slice(-2) + '-' + month;
    }

    function getFiscalSortValue(fiscalPeriod) {
        const [fy, month] = fiscalPeriod.split('-');
        const year = parseInt(fy.replace('FY', ''), 10);
        const monthNum = parseInt(month, 10);
        return (year * 100) + monthNum;
    }

    function getMonthFromFYN(fy_n) {
        if (!fy_n) return 0;
        const parts = fy_n.split('-');
        return parts.length > 1 ? parseInt(parts[1], 10) : 0;
    }

    function getMonthNameFromFYN(fy_n) {
        if (!fy_n) return '';
        const parts = fy_n.split('-');
        if (parts.length < 2) return '';
        const monthIndex = (parseInt(parts[1], 10) - 1) % 12;
        return monthNames[monthIndex];
    }

    // Validasi data
    if (!defectData || defectData.length === 0) {
        console.warn("No defect data available");
        return;
    }

    const canvas = document.getElementById('defectChart');
    if (!canvas) {
        console.error("Canvas element 'defectChart' not found");
        return;
    }

    const ctx = canvas.getContext('2d');

    // Hapus chart lama jika ada
    if (window.defectChart instanceof Chart) {
        window.defectChart.destroy();
    }

    // Buat chart baru
    window.defectChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [],
                borderColor: [],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 10,
                        font: { size: 11 }
                    }
                },
                title: {
                    display: true,
                    text: 'Top 10 Defects',
                    font: { size: 16 }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            if (context.raw === 0) return '';
                            const index = context.dataIndex;
                            const defect = window.currentTopDefects ? window.currentTopDefects[index] : null;
                            if (!defect) return '';
                            const categories = defect.categories.join(', ');
                            return `${defect.defect_name}: ${defect.total_defect} (${categories})`;
                        }
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: { display: false }
                }
            }
        }
    });

    // Fungsi update chart dengan pendekatan yang sama dengan rr-chart.js
    window.updateDefectChart = function (selectedFY, selectedModel, selectedItem, selectedMonth,
        selectedDate, selectedShift, selectedLine, selectedGroup) {

        // Filter data berdasarkan semua parameter
        let filtered = defectData;

        // Filter dengan pendekatan yang sama seperti di rr-chart.js
        if (selectedFY !== 'all') {
            filtered = filtered.filter(d => d.fy_n && ('FY' + d.fy_n.split('-')[0].slice(-2)) === selectedFY);
        }

        if (selectedModel !== 'all') {
            filtered = filtered.filter(d => d.model === selectedModel);
        }

        if (selectedItem !== 'all') {
            filtered = filtered.filter(d => d.item_name === selectedItem);
        }

        if (selectedMonth !== 'all') {
            const monthNum = parseInt(selectedMonth, 10);
            filtered = filtered.filter(d => d.fy_n && getMonthFromFYN(d.fy_n) === monthNum);
        }

        if (selectedDate) {
            filtered = filtered.filter(d => d.date === selectedDate);
        }

        if (selectedShift !== 'all') {
            filtered = filtered.filter(d => d.shift === selectedShift);
        }

        if (selectedLine !== 'all') {
            filtered = filtered.filter(d => d.line === selectedLine);
        }

        if (selectedGroup !== 'all') {
            filtered = filtered.filter(d => d.group === selectedGroup);
        }

        // Gabungkan qty untuk defect name yang sama
        const combinedDefects = {};
        filtered.forEach(defect => {
            const defectName = defect.defect_name;
            if (!defectName) return;

            if (!combinedDefects[defectName]) {
                combinedDefects[defectName] = {
                    defect_name: defectName,
                    total_defect: 0,
                    categories: []
                };
            }

            const defectValue = parseInt(defect.total_defect) || 0;
            combinedDefects[defectName].total_defect += defectValue;

            if (defect.defect_category && !combinedDefects[defectName].categories.includes(defect.defect_category)) {
                combinedDefects[defectName].categories.push(defect.defect_category);
            }
        });

        // Konversi ke array dan urutkan dari nilai terbesar ke terkecil
        const combinedDefectsArray = Object.values(combinedDefects);
        combinedDefectsArray.sort((a, b) => b.total_defect - a.total_defect);

        // Ambil top 10
        const topDefects = combinedDefectsArray.slice(0, 10);
        window.currentTopDefects = topDefects;

        // Jika tidak ada data setelah filter, kosongkan chart
        if (topDefects.length === 0) {
            window.defectChart.data.labels = [];
            window.defectChart.data.datasets[0].data = [];
            window.defectChart.data.datasets[0].backgroundColor = [];
            window.defectChart.data.datasets[0].borderColor = [];
            window.defectChart.update();
            return;
        }

        // Persiapkan data chart
        const labels = topDefects.map(defect => defect.defect_name);
        const data = topDefects.map(defect => defect.total_defect);
        const backgroundColors = topDefects.map((_, index) => generateColor(index, topDefects.length));
        const borderColors = topDefects.map((_, index) => generateBorderColor(index, topDefects.length));

        // Update chart
        window.defectChart.data.labels = labels;
        window.defectChart.data.datasets[0].data = data;
        window.defectChart.data.datasets[0].backgroundColor = backgroundColors;
        window.defectChart.data.datasets[0].borderColor = borderColors;
        window.defectChart.update();
    };

    // Inisialisasi chart pertama kali dengan filter default
    function applyChartTheme(theme) {
        const isDark = theme === 'dark';
        const textColor = isDark ? '#cbd5e1' : '#475569';
        if (window.defectChart.options.plugins.legend && window.defectChart.options.plugins.legend.labels) {
            window.defectChart.options.plugins.legend.labels.color = textColor;
        }
        if (window.defectChart.options.plugins.title) {
            window.defectChart.options.plugins.title.color = textColor;
        }
        if (window.defectChart.options.scales && window.defectChart.options.scales.r) {
            window.defectChart.options.scales.r.ticks.color = textColor;
            window.defectChart.options.scales.r.grid = { color: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' };
        }
        window.defectChart.update();
    }

    window.addEventListener('theme-changed', function (e) {
        applyChartTheme(e.detail.theme);
    });

    const activeTheme = localStorage.getItem('theme') || 'light';
    applyChartTheme(activeTheme);
    window.updateDefectChart(currentFY, 'all', 'all', 'all', '', 'all', 'all', 'all');
};