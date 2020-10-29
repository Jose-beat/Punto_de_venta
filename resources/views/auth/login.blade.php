@extends('auth.contenido')

@section('login')
<style>

.login-container{
    margin-top: 5%;
    margin-bottom: 5%;
}
.login-form-1{
    padding: 5%;
    box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
    background: #e7e7e7;
}
.login-form-1 h3{
    text-align: center;
    color: #333;
}
.login-form-2{
    padding: 5%;
    background: #00cc6d;
    box-shadow: 0 5px 8px 0 rgba(228, 224, 224, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
}
.login-form-2 h3{
    text-align: center;
    color: #fff;
}
.login-container form{
    padding: 10%;
}
.btnSubmit
{
    width: 50%;
    border-radius: 1rem;
    padding: 1.5%;
    border: none;
    cursor: pointer;
}
.login-form-1 .btnSubmit{
    font-weight: 600;
    color: #fff;
    background-color: #0062cc;
}
.login-form-2 .btnSubmit{
    font-weight: 600;
    color: #00cc22;
    background-color: rgb(255, 255, 255);
}
.login-form-2 .btnSubmit:hover{
    font-weight: 600;
    color: #ffffff;
    background-color: rgb(71, 179, 9);
}
.login-form-2 .ForgetPwd{
    color: #fff;
    font-weight: 600;
    text-decoration: none;
}
.login-form-1 .ForgetPwd{
    color: #0062cc;
    font-weight: 600;
    text-decoration: none;
}
.img-logo{
    width: 371px;
    height: 350px;
    position: relative;
    left: 20px;

}
.lab{
    color: rgb(255, 255, 255);
}

</style>

<div class="container login-container">
            <div class="row">
                <div class="col-md-6 login-form-1">
                    <img src="{{asset('storage/src/Logo_Doc.png')}}" class="img-logo" class="img-logo" >
                </div>
                <div class="col-md-6 login-form-2">
                <h3>Ventas</h3>
                    <form class = "form-horizontal was-validated" method="POST" action="{{route('login')}}" >
                    {{ csrf_field() }}

                       <div class="form-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}">
                          <!---  <span class="input-group-addon"><i class="icon-user"></i></span>--->
                          <label for="usuario"  class="lab">Usuario</label>
                              <input  type="text" class="form-control" placeholder="usuario" value="{{old('usuario')}}" id="usuario" name="usuario"/>
                             {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
                        </div>

                        <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                           <!--- <span class="input-group-addon"><i class="icon-lock"></i></span>--->
                           <label for="password" class="lab">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña"/>
                            {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btnSubmit" value="Login" /> Login</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
<!----
<div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card-group mb-0">
          <div class="card p-4">
          <form class="form-horizontal was-validated" method="POST" action="{{route('login')}}">
          {{ csrf_field() }}
              <div class="card-body">
              <h3 class="text-center bg-success">Compras - Ventas</h3>

              <div class="form-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-success px-4"><i class="fa fa-sign-in fa-2x"></i> Iniciar sesión</button>
                </div>
              </div>
            </div>
          </form>
          </div>

        </div>
      </div>
    </div>
    ---->
@endsection
