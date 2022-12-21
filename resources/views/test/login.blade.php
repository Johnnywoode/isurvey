@extends('test.layouts.master')

@section('content')
  <div class="d-flex flex-column justify-content-center align-items-center mt-5">

    {{-- <div class="row d-flex justify-content-end">
      <p id="login-info" class="text-success">Successful login. Welcome <i id="logged-in-user-email" class="font-weight-bold"></i></p>
    </div>

    <div class="row d-flex justify-content-end"><button id="logout-btn" class="btn btn-danger" type="button" onclick="logout()">Logout</button></div> --}}

    <form id="login-form" class="card p-4" >
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input id="email" type="email" name="email" class="form-control" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input id="password" type="password" name="password" class="form-control">
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
      </div>
      <button type="button" class="btn btn-primary" onclick="login()">Login</button>
    </form>
  </div>
@endsection


