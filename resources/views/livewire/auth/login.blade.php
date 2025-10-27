<div>
    <style>
        /* Box Login */
        .login-box {
            align-items: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 360px;
            /* Standard AdminLTE login box width */
            width: 90%;
            /* Responsive width */
            margin: auto;
            /* Center the box horizontally and vertically within flex container */
        }

        .login-logo img {
            width: 100px;
            height: auto;
        }

        .custom-login {
            display: flex;
            justify-content: center;
            /* Pusatkan secara horizontal */
            align-items: center;
            /* Pusatkan secara vertikal */
            /* height: 100vh; */
            position: relative;
            /* Needed for absolute positioning of children */
        }
    </style>

    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <div class="login-box">
                <div class="card">
                    <div class="card-body login-card-body">
                        <div class="login-logo">
                            <img src="{{ asset('assets/logo/csirt-logo.png') }}" alt="Logo" class="brand-image">
                        </div>
                        <h3><b>Wonosobokab-CSIRT</b></h3>
                        <h5>Computer Security Incident Response Team</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form Login -->
                        <form wire:submit.prevent="login">
                            @if (Session::has('error'))
                                <div class="alert alert-danger">
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                            <div class="input-group mb-3">
                                <input type="email" wire:model="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                    required autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" wire:model="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                    required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember" name="remember">
                                        <label for="remember">Remember Me</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>


</div>
