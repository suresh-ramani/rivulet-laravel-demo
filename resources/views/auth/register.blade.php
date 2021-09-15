@extends('layouts.guest')

@section('content')

    <div class="row justify-content-center mt-5">
        <div class="col-12 col-sm-12 col-md-6">
            <div class="card card-body">
                <form onsubmit="register()" action="javascript:void(0)" method="post">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="email">Name</label>
                                <input type="text" id="name" class="form-control">
                                <small class="text-danger" id="name_ve"></small>
                            </div>
                        </div>
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
                                Register
                            </button>
                            <a class="d-block mt-2" href="{{ route('login') }}">Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function register(){
            $(`small`).hide()

            var name = document.getElementById('name').value
            var email = document.getElementById('email').value
            var password = document.getElementById('password').value

            await axios.post('/api/register',{
                name,
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