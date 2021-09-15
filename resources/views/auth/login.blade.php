@extends('layouts.guest')

@section('content')

    <div class="row justify-content-center mt-5">
        <div class="col-12 col-sm-12 col-md-6">
            <div class="card card-body">
                <form action="javascript:void(0)" onsubmit="login()">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" id="email" class="form-control">
                                <small class="text-danger" id="email_ve"></small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control">
                                <small class="text-danger" id="password_ve"></small>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>
                            <a class="d-block mt-2" href="{{ route('register') }}">Register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function login(){
            $(`small`).hide()

            var email = document.getElementById('email').value
            var password = document.getElementById('password').value

            await axios.post('/api/login',{
                email,
                password
            }).then(({data})=>{
                
                localStorage.setItem('access_token', data.access_token)
                localStorage.setItem('user', JSON.stringify(data.user))
                window.location.href = "/dashboard"

            }).catch(({response})=>{
                if(response.status===422){
                    $.each(response.data.errors,function(key, value){
                        $(`#${key}_ve`).html(value[0]).fadeIn()
                    })
                }else{
                    alert(response.data.message)
                }
            })
        }
    </script>
    
@endsection