<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>iSurvey Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body style="background: #e5e5e5;">
  <nav class="navbar navbar-dark navbar-expand-lg bg-dark mb-2">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">iSurvey</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="http://localhost:8000/test/surveys">Surveys</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="http://localhost:8000/test/users">Users</a>
          </li>
        </ul>

        <button class="btn btn-outline-danger " type="button" onclick="logout()">Logout</button>
      </div>
    </div>
  </nav>
  <div class="d-flex justify-content-between p-4">
    <span class="text-uppercase">Users</span>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        {{-- <li class="breadcrumb-item"><a href="http://localhost:8000/test/surveys">Surveys</a></li> --}}
        <li class="breadcrumb-item active" aria-current="page">Users</li>
      </ol>
    </nav>
  </div>
  <div class="container d-flex justify-content-center">
    @foreach ($data as $user)
      <div class="col-12 col-md-4 col-lg-4">
        <div class="card p-4">
          {{ dd($user)}}
        </div>
      </div>
    @endforeach

  </div>

  <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script>
    $(function(){
      if(!getCookie('loggedInUser')){
        alert('kkkk')
        location.replace("http://localhost:8000/test")
      }
    })
    function getCookie(name){
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) {
        return parts.pop().split(';').shift();
      }
    }

    function setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      let expires = "expires="+ d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function request(url, options){
      const csrfToken = decodeURIComponent(getCookie('XSRF-TOKEN'))
      return fetch(url, {
        headers: {
          'content-type': 'application/json',
          'accept': 'application/json',
          'X-XSRF-TOKEN': csrfToken
        },
        credentials: 'include',
        ...options
      })
    }

    function login(){
      let email = $('#email').val()
      let password = $('#password').val()

      return fetch('sanctum/csrf-cookie', {
        headers: {
          'content-type': 'application/json',
          'accept': 'application/json'
        },
        credentials: 'include'
      }).then(() =>
        request('/login',{
          method: 'POST',
          body: JSON.stringify({
            email: email,
            password: password
          })
        }).then(() => {
          setCookie('loggedInUser', email, 2)
          $('#logged-in-user-email').html(getCookie('loggedInUser'))
          $('#login-info').show('slow')
          $('#logout-btn').show('slow')
          $('#login-form').hide('slow')
        })
      )
    }

    function logout(){
      return request('/logout', {
        method: 'POST'
      }).then(() => {
        setCookie('loggedInUser', '', -1)
        $('#logout-btn').hide(0)
        $('#login-form').show('slow')
        $('#login-info').hide('slow')
        $('#logged-in-user-email').html('')

        window.location.href = "http://localhost:8000/test"
      })
    }

    // fetch('sanctum/csrf-cookie', {
    //   headers: {
    //     'content-type': 'application/json',
    //     'accept': 'application/json'
    //   },
    //   credentials: 'include'
    // }).then(() => logout())
    // .then(() => { return login() })
    // .then(() => request('/api/v1/users'))
  </script>
</body>
</html>
