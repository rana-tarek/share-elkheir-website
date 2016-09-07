<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akram by Vodafone</title>
    <link rel="stylesheet" type="text/css" href="{{URL::to('assets/')}}/css/default.css" />
    <link rel="stylesheet" type="text/css" href="{{URL::to('assets/')}}/css/component.css" />
    <script src="{{URL::to('assets/')}}/js/modernizr.custom.js"></script>
</head>

<body class="rtl">
    <div class="by-vodafone">
        <p>Powerd by:</p>
        <strong>Vodafone</strong>
    </div>
    <div class="container">
        <div id="cbp-fbscroller" class="cbp-fbscroller">
            <nav>
                <a href="#fbsection1" class="cbp-fbcurrent"></a>
                <a href="#fbsection2"></a>
                <a href="#fbsection3"></a>
                <a href="#fbsection4"></a>
            </nav>
            <section id="fbsection1">
                <div class="header">
                    {{-- <h1 class="title">أكرم</h1>
                    <p class="slogan">خليك أكرم مع فودافون</p> --}}
                    <img class="title" src="{{URL::to('assets/')}}/images/Akram0.png">
                </div>
                <div class="content">
                    <p class="description">دور علي كل المتبرعين في أي مكان</p>
                </div>
            </section>
            <section id="fbsection2">
                <div class="header">
                    {{-- <h1 class="title">أكرم</h1>
                    <p class="slogan">خليك أكرم مع فودافون</p> --}}
                    <img class="title" src="{{URL::to('assets/')}}/images/Akram0.png">
                </div>
                <div class="content">
                    <p class="description">تفاصيل وانواع كل التبرعات الحالية</p>
                </div>
            </section>
            <section id="fbsection3">
                <div class="header">
                    {{-- <h1 class="title">أكرم</h1>
                    <p class="slogan">خليك أكرم مع فودافون</p> --}}
                    <img class="title" src="{{URL::to('assets/')}}/images/Akram0.png">
                </div>
                <div class="content">
                    <p class="description">حدد تفاصيل التبرع وشاركه علي الخريطة مع الكل</p>
                </div>
            </section>
            <section id="fbsection4">
                <div class="content">
                    <p class="description">أنشر تبرعك للتشجيع</p>
                </div>
            </section>
        </div>
    </div>
    <footer>
        <ul class="links">
            <li>
                <a href="#" class="google"></a>
            </li>
            <li>
                <a href="#" class="apple"></a>
            </li>
            <li>
                <a href="{{URL::to('map')}}" class="websit">خريطة التبرعات</a>
            </li>
        </ul>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!-- jquery.easing by http://gsgd.co.uk/ : http://gsgd.co.uk/sandbox/jquery/easing/ -->
    <script src="{{URL::to('assets/')}}/js/jquery.easing.min.js"></script>
    <!-- waypoints jQuery plugin by http://imakewebthings.com/ : http://imakewebthings.com/jquery-waypoints/ -->
    <script src="{{URL::to('assets/')}}/js/waypoints.min.js"></script>
    <!-- jquery-smartresize by @louis_remi : https://github.com/louisremi/jquery-smartresize -->
    <script src="{{URL::to('assets/')}}/js/jquery.debouncedresize.js"></script>
    <script src="{{URL::to('assets/')}}/js/cbpFixedScrollLayout.min.js"></script>
    <script>
        $(function () {
            cbpFixedScrollLayout.init();
        });
    </script>
</body>

</html>