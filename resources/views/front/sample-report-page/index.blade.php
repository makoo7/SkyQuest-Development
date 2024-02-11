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

</body>
</html>
