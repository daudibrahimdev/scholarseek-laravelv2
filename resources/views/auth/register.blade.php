<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
    {{ config('app.name', 'Laravel') }} | Register
  </title>
  
    <link href="{{ asset('assets/img/brand/favicon.png') }}" rel="icon" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/js/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/argon-dashboard.css?v=1.1.2') }}" rel="stylesheet" />
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-default">
  <div class="main-content">
    
        
        <div class="header bg-gradient-primary py-7 py-lg-8">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-white">Welcome to ScholarSeek!</h1>
              <p class="text-lead text-light">Register to start your mentoring journey.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>

        <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="card bg-secondary shadow border-0">
            
                        <div class="p-4">
                <x-validation-errors class="mb-4" /> 
            </div>
            
                        <div class="card-header bg-transparent pb-5">
              <div class="text-muted text-center mt-2 mb-4"><small>Sign up with</small></div>
              <div class="text-center">
                
                                <a href="#" class="btn btn-neutral btn-icon mr-4"> 
                  <span class="btn-inner--icon"><img src="{{ asset('assets/img/icons/common/github.svg') }}"></span>
                  <span class="btn-inner--text">Github</span>
                </a>
                
                                <a href="#" class="btn btn-neutral btn-icon"> 
                  <span class="btn-inner--icon"><img src="{{ asset('assets/img/icons/common/google.svg') }}"></span>
                  <span class="btn-inner--text">Google</span>
                </a>
              </div>
            </div>
            
                        <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                <small>Or sign up with credentials</small>
              </div>
              
              <form method="POST" action="{{ route('register') }}" role="form">
                @csrf 
                
                                <div class="form-group">
                  <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                    </div>
                    <input class="form-control @error('name') is-invalid @enderror" placeholder="Name" type="text" 
                        name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name"> 
                  </div>
                </div>
                
                                <div class="form-group">
                  <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control @error('email') is-invalid @enderror" placeholder="Email" type="email" 
                        name="email" id="email" value="{{ old('email') }}" required autocomplete="username">
                  </div>
                </div>
                
                                <div class="form-group">
                  <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control @error('password') is-invalid @enderror" placeholder="Password" type="password" 
                        name="password" id="password" required autocomplete="new-password">
                  </div>
                </div>
                
                                <div class="form-group">
                  <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-check-bold"></i></span>
                    </div>
                    <input class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password" type="password" 
                        name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                  </div>
                </div>
                
                <div class="text-muted font-italic"><small>password strength: <span class="text-success font-weight-700">strong</span></small></div>
                
                                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="row my-4">
                  <div class="col-12">
                    <div class="custom-control custom-control-alternative custom-checkbox">
                      <input class="custom-control-input" id="terms" type="checkbox" name="terms"> 
                      <label class="custom-control-label" for="terms">
                        <span class="text-muted">I agree with the 
                          <a href="{{ route('terms.show') }}" target="_blank">Privacy Policy</a>
                          and <a href="{{ route('policy.show') }}" target="_blank">Terms of Service</a>
                        </span>
                      </label>
                    </div>
                  </div>
                </div>
                @endif
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary mt-4">Create account</button> 
                </div>
                
                                <div class="row mt-3">
                  <div class="col-6">
                    <a href="{{ route('login') }}" class="text-light"><small>Already registered?</small></a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
    
    <script src="{{ asset('assets/js/plugins/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=1.1.2') }}"></script>
</body>
</html>