<!DOCTYPE html>
<html lang="en">

<head>
    <title>Itsroop - OTP Verification</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- TECHNICUL CSS -->
    <link id="themeColors" rel="stylesheet" href="/backend/dist/css/techincul.css" />
    <link id="themeColors" rel="stylesheet" href="/backend/dist/css/style.min.css" />
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100">
            <div class="position-relative z-index-5">
                <div class="row">
                    <div class="col-xl-7 col-xxl-8">
                        <a href="/" class="text-nowrap logo-img d-block px-14 py-9 w-100"></a>
                        <div class="d-none d-xl-flex align-items-center justify-content-center"
                            style="height: calc(100vh - 80px);">
                            <img src="/backend/dist/images/backgrounds/shopping.png" alt="" class="img-fluid"
                                width="500">
                        </div>
                    </div>
                    <div class="col-xl-5 col-xxl-4">
                        <div
                            class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
                            <div class="col-sm-8 col-md-6 col-xl-9">
                                <div class="text-center">
                                    <img src="/frontend/tcul-img/logo.png" width="90" alt="" />
                                    <br /><br />
                                    <p class=" mb-9">OTP Verification</p>
                                    <p>An OTP has been sent to your email.</p>
                                </div>
                                <form method="POST" action="{{ route('admin.otp.verify') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="otp" class="form-label">Enter 6-digit OTP</label>
                                        <input type="text" class="form-control" name="otp" required maxlength="6" />
                                    </div>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Verify OTP</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/backend/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="/backend/dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="/backend/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/backend/dist/js/app.min.js"></script>
    <script src="/backend/dist/js/app.init.js"></script>
    <script src="/backend/dist/js/app-style-switcher.js"></script>
    <script src="/backend/dist/js/sidebarmenu.js"></script>
    <script src="/backend/dist/js/custom.js"></script>
</body>

</html>
