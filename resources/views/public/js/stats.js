const generateColors = (numColors) => {
    const colors = [];
    for (let i = 0; i < numColors; i++) {
        const color = `hsl(${i * 360 / numColors}, 60%, 65%)`;
        colors.push(color);
    }
    return colors;
};



const config2 = {
    type: 'pie',
    data: data2,
    options: {
        plugins: {
            legend: {
                position: null,
                labels: {
                    color: '#555',
                    font: {
                        size: 14
                    }
                }
            },
            title: {
                display: true,
                text: 'File extensions',
                color: '#333',
                font: {
                    size: 18
                }
            }
        },
        responsive: true,
        maintainAspectRatio: false
    },
};


new Chart(document.getElementById('chart2'), config2);
