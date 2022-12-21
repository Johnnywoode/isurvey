<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iSurvey Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body style="background: #e5e5e5;">
    @include('test.layouts.navbar')
    @yield('content')

    <script src="https://code.jquery.com/jquery-3.6.2.min.js"
        integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script>
        $(function() {

            //   setCookie('loggedInUser', '', -1)
            //   $('#logout-btn').hide('slow')
            //   $('#login-info').hide('slow')

            //   if(getCookie('loggedInUser')){
            //     $('#logout-btn').show('slow')
            //     $('#logged-in-user-email').html(getCookie('loggedInUser'))
            //     $('#login-info').show('slow')
            //     $('#login-form').hide('slow')
            //   }
            //   if(!getCookie('loggedInUser')){
            //     $('#logout-btn').hide('slow')
            //     $('#logged-in-user-email').html('')
            //     $('#login-info').hide('ow')
            //     $('#login-form').show('slow')
            //   }
        })

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
        }

        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function request(url, options) {
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

        function login() {
          let email = $('#email').val()
          let password = $('#password').val()
          $.get("{{ url('sanctum/csrf-cookie') }}")
              .done(() => {
                const csrfToken = decodeURIComponent(getCookie('XSRF-TOKEN'))
                console.log(email);
                $.ajaxSetup({
                    headers: {
                      'content-type': 'application/json',
                      'accept': 'application/json',
                      'X-XSRF-TOKEN': csrfToken
                    },
                    credentials: 'include',
                    observe: 'response'
                });
                $.post('{{ url('login') }}', JSON.stringify({email: email, password: password}), 'json')
                  .done(() => {
                    console.log('logged in');
                    window.location.href = '{{url("test/surveys")}}'
                  })
              })
              .fail((data, status) => {
                  alert(data.responseJSON.message)
              });
          //   return fetch('sanctum/csrf-cookie', {
          //     headers: {
          //       'content-type': 'application/json',
          //       'accept': 'application/json'
          //     },
          //     credentials: 'include'
          //   }).then((res) => {
          //     request('/login',{
          //       method: 'POST',
          //       body: JSON.stringify({
          //         email: email,
          //         password: password
          //       })
          //     }).then(() => {
          //       setCookie('loggedInUser', email, 2)
          //       window.location.href = '{{ url('test.dash') }}'
          //     //   $('#logged-in-user-email').html(getCookie('loggedInUser'))
          //     //   $('#login-info').show('slow')
          //     //   $('#logout-btn').show('slow')
          //     //   $('#login-form').hide('slow')
          //     })
          //   })
        }

        function logout() {
            return request('/logout', {
                method: 'POST'
            }).then(() => {
                setCookie('loggedInUser', '', -1)
                $('#logout-btn').hide(0)
                $('#login-form').show('slow')
                $('#login-info').hide('slow')
                $('#logged-in-user-email').html('')
            })
        }
    </script>
</body>

</html>
