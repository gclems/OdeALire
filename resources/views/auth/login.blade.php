@extends('layouts.global')
@section('title','Connexion')
@section('bodyClass', 'alternative-body')
@section('styles')

@endsection
@section('appContent')
  <div class="login_wrapper">
    {!! Form::open([
        'route' => 'doLogin',
        'method' => 'post',
        'id' => 'login-form'
        ]) !!}
        <div class="bigText">Connexion</div>
        <div class="form-group">
          {!! Form::text('email', '', ['class' => 'form-control', 'placeholder'=>'Adresse email']) !!}
        </div>
        <div class="form-group">
          {!! Form::password('password', ['class' => 'form-control', 'placeholder'=>'Mot de passe']) !!}
        </div>
        <div class="form-group">
         Se souvenir de moi  {!! Form::checkbox('remember','', ['class' => 'form-control']) !!}
        </div>
        @if($errors->has('email') || $errors->has('password') )
        <div class="alert alert-danger">
          @if ($errors->has('email'))
                  {{ $errors->first('email') }}
          @endif
          @if ($errors->has('password'))
                  {{ $errors->first('password') }}
          @endif
        </div>
        @endif
        <div>
            <!-- <a class="reset_pass" href="{{route('register')}}">S'enregistrer</a>
            &nbsp;-&nbsp; -->
            <a class="reset_pass" href="#">Mot de passe oublié ?</a>
            {!! Form::submit('Se connecter', ['class' => 'btn btn-success pull-right', 'id' => 'login-submit']) !!}
        </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
      <div class="separator"></div>
      <div class="bigText">Ode à lire</div>
  </div>
@endsection
