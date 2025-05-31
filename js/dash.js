const labels = ["Lập trình C", "Lập trình Java", "Cơ sở dữ liệu"];
const mustTeach = [45, 60, 30];

new Chart(document.getElementById("teachingChart"), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Số giờ phải dạy',
            data: mustTeach,
            backgroundColor: 'rgb(70, 189, 216)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Biểu đồ so sánh số giờ giảng dạy'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Số giờ'
                }
            }
        }
    }
});
