
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="icon" type="image/png" href="./assets/img/favicon.png"> --}}
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('imgs/promologo.png') }}">
    
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="{{asset('./assets/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('./assets/css/uf-style.css')}}">
    {{-- <title>Login Form Bootstrap 1 by UIFresh</title> --}}
    <style>
      #logo-img{
        filter: drop-shadow(4px 4px 6px rgba(255, 255, 255, 0.5));
      }
    </style>
  </head>
  <body >
    {{-- <div id="night-sky" style="--number-of-stars: 20"> --}}

        <div class="uf-form-signin" >
          <div class="text-center">
            <a href="#"><img style="margin: 20px" src="{{asset('imgs/promostoneLogo.png')}}" id="logo-img" alt="" width="90" height="85"> </a>
            {{-- <application-logo  /> --}}
          <h1 class="text-white h3">Connexion au compte</h1>
          </div>
          <form class="mt-4" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group uf-input-group input-group-lg mb-3">
              <span class="input-group-text fa fa-user"></span>
              <input type="text" name="email" class="form-control" placeholder="Email address" required> 
              @error('email')
                  <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="input-group uf-input-group input-group-lg mb-3">
              <span class="input-group-text fa fa-lock"></span>
              <input type="password" name="password" class="form-control" placeholder="Password" required>
              @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>
            <div class="d-flex mb-3 justify-content-between">
              <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input uf-form-check-input" id="exampleCheck1">
                <label class="form-check-label text-white" for="exampleCheck1">Remember Me</label>
    
              </div>
              {{-- <a href="#">Forgot password?</a> --}}
            </div>
            <div class="d-grid mb-4">
              <button type="submit" class="btn uf-btn-primary btn-lg">Login</button>
            </div>
          </form>
        </div>
    {{-- </div> --}}

    <!-- JavaScript -->

    <!-- Separate Popper and Bootstrap JS -->
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
  </body>
</html>