<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8" />
<meta name="csrf-token" content="{!! csrf_token() !!}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset("css/slide.css") }}">
<link rel="stylesheet" href="{{ asset("css/index.css") }}">
<style class="shared-css" type="text/css" >
    .s1_1{font-family:DejaVuSans_mv;color:#000;}
    @font-face {
        font-family: DejaVuSans-Bold_n4;
        src: url({{ asset("assets/frontend/slide/fonts/DejaVuSans-Bold_n4.woff") }}) format("woff");
    }

    @font-face {
        font-family: DejaVuSans_mv;
        src: url({{ asset("assets/frontend/slide/fonts/DejaVuSans_mv.woff") }}) format("woff");
    }
</style>
</head>

<body style="margin: 0;">

{{-- Header Start --}}
    @include('front.sample-report-page.header.index')
{{-- Header Start --}}

{{-- Content --}}
    @if($response['frame'] == "intro")
        @include('front.sample-report-page.pages.intro')
    @elseif($response['frame'] == "dummy-content")
        @if(app('request')->input('page') == 2)
            @include('front.sample-report-page.pages.dummy-content-page-2')
        @elseif(app('request')->input('page') == 3)
            @include('front.sample-report-page.pages.dummy-content-page-3')
        @endif
    @elseif($response['frame'] == "page-4")
        @include('front.sample-report-page.pages.page-4')
    @elseif($response['frame'] == "page-5")
        @include('front.sample-report-page.pages.page-5')
    @elseif($response['frame'] == "page-6")
        @include('front.sample-report-page.pages.page-6')
    @elseif($response['frame'] == "page-8")
        @include('front.sample-report-page.pages.page-8')
    @elseif($response['frame'] == "page-9")
        @include('front.sample-report-page.pages.page-9')
    @elseif($response['frame'] == "content")
        @include('front.sample-report-page.pages.content')
    @elseif($response['frame'] == "flowchart-10")
        @include('front.sample-report-page.pages.flowchart-10')
    @elseif($response['frame'] == "page-13")
        @include('front.sample-report-page.pages.page-13')
    @elseif($response['frame'] == "page-14")
        @include('front.sample-report-page.pages.page-14')
    @elseif($response['frame'] == "page-15")
        @include('front.sample-report-page.pages.page-15')
    @elseif($response['frame'] == "page-16")
        @include('front.sample-report-page.pages.page-16')
    @elseif($response['frame'] == "page-17")
        @include('front.sample-report-page.pages.page-17')
    @elseif($response['frame'] == "page-18")
        @include('front.sample-report-page.pages.page-18')
    @elseif($response['frame'] == "page-19")
        @include('front.sample-report-page.pages.page-19')
    @elseif($response['frame'] == "page-20")
        @include('front.sample-report-page.pages.page-20')
    @elseif($response['frame'] == "page-21")
        @include('front.sample-report-page.pages.page-21')
    @elseif($response['frame'] == "page-22")
        @include('front.sample-report-page.pages.page-22')
    @elseif($response['frame'] == "page-23")
        @include('front.sample-report-page.pages.page-23')
    @elseif($response['frame'] == "page-24")
        @include('front.sample-report-page.pages.page-24')
    @elseif($response['frame'] == "page-25")
        @include('front.sample-report-page.pages.page-25')
    @elseif($response['frame'] == "page-26")
        @include('front.sample-report-page.pages.page-26')
    @elseif($response['frame'] == "page-27")
        @include('front.sample-report-page.pages.page-27')
    @elseif($response['frame'] == "page-28")
        @include('front.sample-report-page.pages.page-28')
    @elseif($response['frame'] == "page-29")
        @include('front.sample-report-page.pages.page-29')
    @elseif($response['frame'] == "page-30")
        @include('front.sample-report-page.pages.page-30')
    @elseif($response['frame'] == "page-31")
        @include('front.sample-report-page.pages.page-31')
    @elseif($response['frame'] == "page-32")
        @include('front.sample-report-page.pages.page-32')
    @elseif($response['frame'] == "page-33")
        @include('front.sample-report-page.pages.page-33')
    @elseif($response['frame'] == "page-34")
        @include('front.sample-report-page.pages.page-34')
    @elseif($response['frame'] == "page-35")
        @include('front.sample-report-page.pages.page-35')
    @elseif($response['frame'] == "page-36")
        @include('front.sample-report-page.pages.page-36')
    @elseif($response['frame'] == "page-37")
        @include('front.sample-report-page.pages.page-37')
    @elseif($response['frame'] == "page-38")
        @include('front.sample-report-page.pages.page-38')
    @elseif($response['frame'] == "page-39")
        @include('front.sample-report-page.pages.page-39')
    @elseif($response['frame'] == "page-40")
        @include('front.sample-report-page.pages.page-40')
    @elseif($response['frame'] == "page-41")
        @include('front.sample-report-page.pages.page-41')
    @elseif($response['frame'] == "page-42")
        @include('front.sample-report-page.pages.page-42')
    @elseif($response['frame'] == "page-43")
        @include('front.sample-report-page.pages.page-43')
    @elseif($response['frame'] == "page-44")
        @include('front.sample-report-page.pages.page-44')
    @elseif($response['frame'] == "page-45")
        @include('front.sample-report-page.pages.page-45')
    @elseif($response['frame'] == "page-46")
        @include('front.sample-report-page.pages.page-46')
    @elseif($response['frame'] == "page-47")
        @include('front.sample-report-page.pages.page-47')
    @elseif($response['frame'] == "page-48")
        @include('front.sample-report-page.pages.page-48')
    @elseif($response['frame'] == "page-49")
        @include('front.sample-report-page.pages.page-49')
    @elseif($response['frame'] == "page-50")
        @include('front.sample-report-page.pages.page-50')
    @elseif($response['frame'] == "page-51")
        @include('front.sample-report-page.pages.page-51')
    @elseif($response['frame'] == "page-52")
        @include('front.sample-report-page.pages.page-52')
    @elseif($response['frame'] == "page-53")
        @include('front.sample-report-page.pages.page-53')
    @elseif($response['frame'] == "page-54")
        @include('front.sample-report-page.pages.page-54')
    @elseif($response['frame'] == "page-55")
        @include('front.sample-report-page.pages.page-55')
    @elseif($response['frame'] == "page-56")
        @include('front.sample-report-page.pages.page-56')
    @elseif($response['frame'] == "page-57")
        @include('front.sample-report-page.pages.page-57')
    @elseif($response['frame'] == "page-58")
        @include('front.sample-report-page.pages.page-58')
    @elseif($response['frame'] == "page-59")
        @include('front.sample-report-page.pages.page-59')
    @elseif($response['frame'] == "page-60")
        @include('front.sample-report-page.pages.page-60')
    @elseif($response['frame'] == "page-61")
        @include('front.sample-report-page.pages.page-61')
    @elseif($response['frame'] == "page-62")
        @include('front.sample-report-page.pages.page-62')
    @elseif($response['frame'] == "page-63")
        @include('front.sample-report-page.pages.page-63')
    @elseif($response['frame'] == "page-64")
        @include('front.sample-report-page.pages.page-64')
    @elseif($response['frame'] == "page-65")
        @include('front.sample-report-page.pages.page-65')
    @elseif($response['frame'] == "page-66")
        @include('front.sample-report-page.pages.page-66')
    @elseif($response['frame'] == "page-67")
        @include('front.sample-report-page.pages.page-67')
    @elseif($response['frame'] == "page-68")
        @include('front.sample-report-page.pages.page-68')
    @elseif($response['frame'] == "page-69")
        @include('front.sample-report-page.pages.page-69')
    @elseif($response['frame'] == "page-70")
        @include('front.sample-report-page.pages.page-70')
    @elseif($response['frame'] == "page-71")
        @include('front.sample-report-page.pages.page-71')
    @elseif($response['frame'] == "page-72")
        @include('front.sample-report-page.pages.page-72')
    @endif
    {{-- @include('front.sample-report-page.pages.content') --}}
    {{-- @include('front.sample-report-page.pages.thankyou') --}}
{{-- Content --}}

{{-- Footer --}}
    {{-- @include('front.sample-report-page.footer.index') --}}
{{-- Footer --}}

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- Chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function AddMinutesToDate(date, minutes) {
        return new Date(date.getTime() + minutes * 60000);
    }
    function DateFormat(date){
    var days = date.getDate();
    var year = date.getFullYear();
    var month = (date.getMonth()+1);
    var hours = date.getHours();
    var minutes = date.getMinutes();
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = days + '/' + month + '/' + year + '/ '+hours + ':' + minutes;
    return strTime;
    }
    $(document).on('click', '#btn-next', function(){
        let obj = $(this).attr('data-href');
        let user = "{{ base64_encode($user) }}";
        let report = "{{ base64_encode($report) }}";
        let sampleId = "{{ base64_encode($sampleId) }}";
        let page = "{{ $page }}";
        let startTime = new Date();
        let endTime = AddMinutesToDate(startTime,5);
        let data = JSON.stringify({user: user, report: report, sampleId: sampleId, page:page, startTime:startTime, endTime:endTime});
        $.ajax({
            url: "{{ route('sample-report-logs-store') }}",
            contentType: "application/json",
            dataType: "json",
            type: "POST",
            data: data,
            processData: false,
            success: function (data){
                alert('logs added successfully');
                window.location.href = obj;
            },
            error: function(error){}
        });
    });    
</script>
<!-- Slide 24 bar chart -->
<script>
    const ctx = document.getElementById('report-name-market');
    ctx.height = '128px';
    new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['2020', '2021', '2022', '2023(e)', '2030(f)'],
        datasets: [{
            label: '# of Votes',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#6C3CBF',
        }]
    },
    options: {
        scales: {
        y: {
            beginAtZero: true
        },
        yAxis: {
            ticks: {
                display: false
            }
        }
        }
    }
    });
</script>
<!-- Slide 25 bar chart -->
<script>
    const ctx1 = document.getElementById('global-market-seg1');
    ctx1.height = '128px';
    new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['2023', '2030'],
        datasets: [{
            label: 'SUB SEGMENT 2',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#ed7d31',
        },
        {
            label: 'SUB SEGMENT 1',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#6c3cbf',
        }
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            }
        }
    }
    });
</script>

<script>
    const ctx2 = document.getElementById('global-market-geography');
    ctx2.height = '128px';
    new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['2023', '2030'],
        datasets: [{
            label: 'SUB SEGMENT 2',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#ed7d31',
        },
        {
            label: 'SUB SEGMENT 1',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#6c3cbf',
        }
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            }
        }
    }
    })
</script>

<script>
    const ctx3 = document.getElementById('global-market-seg2');
    ctx3.height = '128px';
    new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: ['2023', '2030'],
        datasets: [{
            label: 'SUB SEGMENT 2',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#ed7d31',
        },
        {
            label: 'SUB SEGMENT 1',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#6c3cbf',
        }
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            }
        }
    }
    });
</script>

<script>
    const ctx4 = document.getElementById('global-market-seg3');
    ctx4.height = '128px';
    new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: ['2023', '2030'],
        datasets: [{
            label: 'SUB SEGMENT 2',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#ed7d31',
        },
        {
            label: 'SUB SEGMENT 1',
            data: [50, 60, 70, 80, 90],
            borderWidth: 1,
            backgroundColor: '#6c3cbf',
        }
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            }
        }
    }
    });
</script>

<!-- Slide 26 Bubble chart -->
<script>
    const ctx5 = document.getElementById('bubble-chart');
    ctx5.height = '128px';
    new Chart(ctx5, {
    type: 'bubble',
    data: {
        datasets: [
            {
                label: 'Dataset 1',
                data: [
                    {x: 5, y: 1, r: 35},
                    {x: 9, y: 3, r: 46},
                    {x: 8, y: 2, r: 25},
                    {x: 7, y: 3, r: 40},
                    {x: 10, y: 2, r: 52},
                ]
            }
        ]
    },
    options: {
        plugins: {
            legend: {
                display: true,
            }
        }
    }
    });
</script>

<!-- Slide 27 Semi doughnut chart -->
<script>
    var ctx6 = document.getElementById("semi-doughnut-chart");
    ctx6.height = '128px'
    var ctx6 = new Chart(ctx6, {
        type: 'doughnut',
        data: {
            labels: ["Red", "Orange", "Green"],
            datasets: [{
                label: '# of Votes',
                data: [33, 33, 33, 33],
                backgroundColor: [
                    '#f7931e',
                    '#1b1464',
                    '#0000ff',
                    '#93278f'
                ],
                borderColor: [
                    '#f7931e',
                    '#1b1464',
                    '#0000ff',
                    '#93278f'
                ],
                borderWidth: 5
            }]

        },
        options: {
            // rotation: -90,
            circumference: 180,
            plugins: {
                legend: {
                    display: false,
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 25
        }
    })
</script>

<!-- Slide 41 Mixed chart -->
<script>
    var ctx7 = document.getElementById("price-spectrum-chart");
    var ctx7 = new Chart(ctx7, {
        type: 'scatter',
        data: {
            labels: ["Global", "North America", "Europe", "Asia Pacific", "Middle East & Africa", "Latin America"],
            datasets: [{
                type: 'bar',
                label: 'SUB SEGMENT 2',
                data: [50, 60, 70, 80, 90, 75],
                borderWidth: 1,
                backgroundColor: '#ed7d31',
            },
            {
                type: 'line',
                label: 'SUB SEGMENT 1',
                data: [50, 60, 70, 80, 90, 85],
                borderWidth: 1,
                backgroundColor: '#6c3cbf',
                borderColor: 'rgb(54, 162, 235)'
            },
            {
                type: 'line',
                label: 'SUB SEGMENT 2',
                data: [40, 50, 90, 85, 60, 65],
                borderWidth: 1,
                backgroundColor: '#6c3cbf',
                borderColor: '#7030a0'
            }
            ]},
        options: {
            // rotation: -90,
            circumference: 180,
            plugins: {
                legend: {
                    display: false,
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 25
        }
    })
</script>

<!-- Slide 42 bar chart -->
<script>
    const ctx8 = document.getElementById('global-market-bar-chart');
    new Chart(ctx8, {
    type: 'bar',
    data: {
        labels: ['2018', '2022', '2023', '2024', '2026', '2030'],
        datasets: [{
            label: 'SUB SEGMENT 2',
            data: [10, 20, 30, 40, 60, 90],
            borderWidth: 1,
            backgroundColor: '#7030a0',
        },
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            },
        }
    }
    });
</script>

<!-- Slide 43 doughnut chart -->
<script>
    var ctx9 = document.getElementById("global-market-largest-chart");
    var ctx9 = new Chart(ctx9, {
        type: 'doughnut',
        data: {
            labels: ["Red", "Orange", "Green"],
            datasets: [{
                label: '# of Votes',
                data: [65, 35],
                backgroundColor: [
                    '#7030a0',
                    '#fff',
                ],
                borderColor: [
                    '#7030a0',
                    '#ccc',
                ],
                // borderWidth: 5
            }]

        },
        options: {
            plugins: {
                legend: {
                    display: false,
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 65
        }
    })

    var ctx10 = document.getElementById("global-market-second-largest-chart");
    var ctx10 = new Chart(ctx10, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                label: '# of Votes',
                data: [45, 55],
                backgroundColor: [
                    '#00b050',
                    '#fff',
                ],
                borderColor: [
                    '#00b050',
                    '#ccc',
                ],
                // borderWidth: 5
            }]

        },
        options: {
            plugins: {
                legend: {
                    display: false,
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 85
        }
    })
</script>

<!-- Slide 46 bar chart -->
<script>
    const ctx11 = document.getElementById('region-market-size-bar-chart');
    new Chart(ctx11, {
    type: 'bar',
    data: {
        labels: ['2018', '2022', '2023', '2024', '2026', '2030'],
        datasets: [{
            label: 'Latin America',
            data: [6, 8, 8, 10, 11, 12],
            borderWidth: 1,
            backgroundColor: '#4472c4',
        },
        {
            label: 'Middle East & Africa',
            data: [8, 12, 14, 18, 18, 22],
            borderWidth: 1,
            backgroundColor: '#ed7d31',
        },
        {
            label: 'Asia Pacific',
            data: [10, 20, 30, 40, 60, 90],
            borderWidth: 1,
            backgroundColor: '#a5a5a5',
        },
        {
            label: 'Europe',
            data: [10, 20, 30, 40, 60, 90],
            borderWidth: 1,
            backgroundColor: '#ffc000',
        },
        {
            label: 'North America',
            data: [10, 20, 30, 40, 60, 90],
            borderWidth: 1,
            backgroundColor: '#7030a0',
        },
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            },
        }
    }
    });
</script>

<!-- Slide 50 doughnut chart -->
<script>
    var ctx12 = document.getElementById("revenue-share-geography");
    var ctx12 = new Chart(ctx12, {
        type: 'doughnut',
        data: {
            labels: ["North America", "Europe", "Asia Pacific", "MEA", "Latin America"],
            datasets: [{
                label: '# of Votes',
                data: [35, 15, 25, 15, 10],
                backgroundColor: [
                    '#6c3cbf',
                    '#ed7d31',
                    '#a5a5a5',
                    '#ffc000',
                    '#2ac5a7'
                ],
                borderColor: [
                    '#6c3cbf',
                    '#ed7d31',
                    '#a5a5a5',
                    '#ffc000',
                    '#2ac5a7'
                ],
                // borderWidth: 5
            }]

        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 65
        }
    })
</script>

<!-- Slide 53 bar chart -->
<script>
    const ctx13 = document.getElementById('america-market-size');
    new Chart(ctx13, {
    type: 'bar',
    data: {
        labels: ['2020', '2021', '2022', '2023(e)', '2030(f)'],
        datasets: [{
            label: 'USA',
            data: [40, 50, 60, 70, 80],
            borderWidth: 1,
            backgroundColor: '#7030a0',
        },
        {
            label: 'Canada',
            data: [10, 20, 30, 40, 50],
            borderWidth: 1,
            backgroundColor: '#ed7d31',
        },
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            },
        }
    }
    });
</script>

<!-- Slide 54 bar chart -->
<script>
    const ctx14 = document.getElementById('europe-market-size');
    new Chart(ctx14, {
    type: 'bar',
    data: {
        labels: ['2020', '2021', '2022', '2023(e)', '2030(f)'],
        datasets: [{
            label: 'UK',
            data: [40, 50, 60, 70, 80],
            borderWidth: 1,
            backgroundColor: '#7030a0',
        },
        {
            label: 'France',
            data: [10, 20, 30, 40, 50],
            borderWidth: 1,
            backgroundColor: '#ed7d31',
        },
        {
            label: 'Canada',
            data: [30, 40, 60, 50, 70],
            borderWidth: 1,
            backgroundColor: '#a5a5a5',
        },
        {
            label: 'Canada',
            data: [20, 40, 30, 60, 50],
            borderWidth: 1,
            backgroundColor: '#ffc000',
        },
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            },
        }
    }
    });
</script>

<!-- Slide 56 doughnut chart -->
<script>
    var ctx15 = document.getElementById("market-share-analysis");
    var ctx15 = new Chart(ctx15, {
        type: 'doughnut',
        data: {
            labels: ["Player 1", "Player 2", "Player 3", "Player 4"],
            datasets: [{
                label: '# of Votes',
                data: [35, 15, 25, 15, 10],
                backgroundColor: [
                    '#6c3cbf',
                    '#ed7d31',
                    '#a5a5a5',
                    '#ffc000',
                ],
                borderColor: [
                    '#6c3cbf',
                    '#ed7d31',
                    '#a5a5a5',
                    '#ffc000',
                ],
                // borderWidth: 5
            }]

        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 65
        }
    })
</script>

<!-- Slide 58 scatter chart -->
<script>
    const ctx16 = document.getElementById('prominent-player-scatter-chart');
    new Chart(ctx16, {
    type: 'scatter',
    data: {
        datasets: [
            {
                label: 'Dataset 1',
                data: [{
                    x: -10,
                    y: 0
                }, {
                    x: 0,
                    y: 10
                }, {
                    x: 10,
                    y: 5
                }, {
                    x: 0.5,
                    y: 5.5
                }],
                backgroundColor: [
                    '#6c3cbf29',
                    '#ed7d3129',
                    '#a5a5a529',
                    '#ffc00029',
                ],
                borderColor: [
                    '#6c3cbf',
                    '#ed7d31',
                    '#a5a5a5',
                    '#ffc000',
                ],
            },
        ],
    },
    options: {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Product Offering Strength',
                    font: {
                        size: 14,
                        weight: 'bold',
                    },
                },
                grid: {
                    display: true,
                },
                ticks: {
                    display: false
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Business Strength',
                    font: {
                        size: 14,
                        weight: 'bold',
                    },
                },
                grid: {
                    display: true,
                },
                ticks: {
                    display: false
                }
            },
        },
        plugins: {
            legend: {
                display: false,
            }
        }
    }
    });
</script>

<!-- Slide 59 Line chart -->
<script>
    const ctx17 = document.getElementById('vertical-line-chart');
    new Chart(ctx17, {
    type: 'line',
    data: {
        labels: ['0', '2', '4', '6', '8', '10'],
        datasets: [{
            label: '',
            data: [5, 3, 5, 5.5, 4.2, 3],
            borderWidth: 1,
            backgroundColor: '#7030a0',
        },
    ]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                suggestedMin: 0,
                suggestedMax: 10,
                ticks: {
                    stepSize: 2,
                },
                grid: {
                    display: true,
                },
            },
            y: {
                ticks: {
                    display: false,
                },
                grid: {
                    display: true,
                },
            },
        },
        plugins: {
            legend: {
                display: false,
            }
        }
    }
    });
</script>

<script>
    const ctx18 = document.getElementById('recent-financial-chart');
    new Chart(ctx18, {
    type: 'bar',
    data: {
        labels: ['2020', '2021', '2022'],
        datasets: [{
            label: '',
            data: [60, 30, 50],
            borderWidth: 1,
            backgroundColor: '#7030a0',
        },
    ]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                suggestedMin: 0,
                suggestedMax: 100,
                ticks: {
                    display: false,
                },
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: false,
                position: 'bottom',
            },
        }
    }
    });
</script>

<script>
    const ctx19 = document.getElementById('business-revenue-chart');
    new Chart(ctx19, {
    type: 'pie',
    data: {
        labels: [
            'Segment 1',
            'Segment 2',
            'Segment 3',
            'Segment 4',
            'Segment 5',
        ],
        datasets: [{
            label: [
                'Segment 1',
                'Segment 2',
                'Segment 3',
                'Segment 4',
                'Segment 5',
            ],
            data: [54, 21, 9, 8, 8],
            backgroundColor: [
                '#7030a0',
                '#ffc000',
                '#002060',
                '#cc6600',
                '#548235',
            ],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRation: false,
        scales: {
            x: {
                ticks: {
                    display: false,
                },
                grid: {
                    display: false,
                },
            },
            y: {
                suggestedMin: 0,
                suggestedMax: 100,
                ticks: {
                    display: false,
                },
                grid: {
                    display: false,
                },
            },
        },
        plugins: {
            legend: {
                display: true,
                position: 'right',
            },
        }
    }
    });
</script>

<!-- Slide 70 doughnut chart -->
<script>
    var ctx15 = document.getElementById("why-sky-delivery");
    var ctx15 = new Chart(ctx15, {
        type: 'doughnut',
        data: {
            labels: ["data1", "data2"],
            datasets: [{
                label: '# of Votes',
                data: [98, 2],
                backgroundColor: [
                    '#1b1464',
                    '#6c3cbf',
                ],
                borderColor: [
                    '#1b1464',
                    '#6c3cbf',
                ],
                // borderWidth: 5
            }]

        },
        options: {
            plugins: {
                legend: {
                    display: false,
                    position: 'bottom'
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 30,
        }
    })
</script>

<script>
    var ctx15 = document.getElementById("why-sky-realization");
    var ctx15 = new Chart(ctx15, {
        type: 'doughnut',
        data: {
            labels: ["data1", "data2"],
            datasets: [{
                label: '# of Votes',
                data: [88, 12],
                backgroundColor: [
                    '#1b1464',
                    '#6c3cbf',
                ],
                borderColor: [
                    '#1b1464',
                    '#6c3cbf',
                ],
                // borderWidth: 5
            }]

        },
        options: {
            plugins: {
                legend: {
                    display: false,
                    position: 'bottom'
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 65
        }
    })
</script>

<script>
    var ctx15 = document.getElementById("why-sky-references");
    var ctx15 = new Chart(ctx15, {
        type: 'doughnut',
        data: {
            labels: ["data1", "data2"],
            datasets: [{
                label: '# of Votes',
                data: [90, 10],
                backgroundColor: [
                    '#1b1464',
                    '#6c3cbf',
                ],
                borderColor: [
                    '#1b1464',
                    '#6c3cbf',
                ],
                // borderWidth: 5
            }]

        },
        options: {
            plugins: {
                legend: {
                    display: false,
                    position: 'bottom'
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 65
        }
    })
</script>

<script>
    var ctx15 = document.getElementById("why-sky-repeat");
    var ctx15 = new Chart(ctx15, {
        type: 'doughnut',
        data: {
            labels: ["data1", "data2"],
            datasets: [{
                label: '# of Votes',
                data: [85, 15],
                backgroundColor: [
                    '#1b1464',
                    '#6c3cbf',
                ],
                borderColor: [
                    '#1b1464',
                    '#6c3cbf',
                ],
                // borderWidth: 5
            }]

        },
        options: {
            plugins: {
                legend: {
                    display: false,
                    position: 'bottom'
                }
            },
            tooltip: {
                enabled: false
            },
            cutoutPercentage: 65
        }
    })
</script>
</body>
</html>
