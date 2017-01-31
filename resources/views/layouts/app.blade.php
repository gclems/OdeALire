@extends('layouts.global')
@section('appContent')
<?php $currentRouteName = Route::currentRouteName(); ?>
<header>
  <div class="left-panel">
    <a class="header-brand" href="{{route('home')}}"><i class="fa fa-book"></i>&nbsp;Ode à lire</a>
  </div>
  <div class="right-panel">
    <div class="pull-right hidden-xs hidden-sm" id="user-panel">
        {{ Auth::user()->Name }}&nbsp;
        <a href="{{route('logout')}}" class="disconnect-button"><i class="fa fa-eject"></i></a>
        <a href="" class=""><i class="fa fa-cog"></i></a>
        <div class="clearfix"></div>
    </div>
    <button
        role="button"
        id="btn-toggle-navbar"
        class="btn btn-link visible-xs visible-sm pull-right">
        <i class="fa fa-bars"></i>
    </button>
  </div>
</header>

<div id="left-panel-overlay"></div>
<div id="left-panel">
    <div id="hello">
    Bonjour, <span class="userName">{{ Auth::user()->Name }}</span>
  </div>
    <nav class="navbar">
      <div class="clearfix"></div>
      <ul class="nav side-menu">
          <li class="{{Route::currentRouteName() == 'home' ? 'active':''}}">
              <a href="{{route('home')}}">
                  <i class="fa fa-home"></i>&nbsp;Home
              </a>
          </li>
          <li class="nav-container {{ in_array(Route::currentRouteName(), ['books', 'addBookForm', 'editBookForm', 'series','authors','editors']) ? 'active':'' }}">
              <a href="#"><i class="fa fa-institution"></i>&nbsp;Bibliothèque<span class="fa fa-chevron-down"></span>
              </a>
              <ul class="nav child_menu">
                  <li class="{{ in_array(Route::currentRouteName(), ['books', 'addBookForm', 'editBookForm']) ? 'current-page':''}}"><a href="{{route('books')}}"><i class="fa fa-book"></i>&nbsp;Livres</a></li>
                  <li class="{{Route::currentRouteName() == 'series' ? 'current-page':''}}"><a href="{{route('series')}}"><i class="fa fa-tags"></i>&nbsp;Séries</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="{{Route::currentRouteName() == 'authors' ? 'current-page':''}}"><a href="{{route('authors')}}"><i class="fa fa-user"></i>&nbsp;Auteurs</a></li>
                  <li class="{{Route::currentRouteName() == 'editors' ? 'current-page':''}}"><a href="{{route('editors')}}"><i class="fa fa-building"></i>&nbsp;Éditeurs</a></li>
              </ul>
          </li>
            <li class="nav-container {{ in_array(Route::currentRouteName(), ['loans', 'people']) ? 'active':'' }}">
              <a href="#"><i class="fa fa-exchange"></i>&nbsp;Prêts<span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li class="{{Route::currentRouteName() == 'loans' ? 'current-page':''}}"><a href="{{ route('loans') }}"><i class="fa fa-rocket"></i>&nbsp;Gestion des prêts</a></li>
                <li role="separator" class="divider"></li>
                <li class="{{Route::currentRouteName() == 'people' ? 'current-page':''}}"><a href=""><i class="fa fa-address-book"></i>&nbsp;Emprunteurs</a></li>
              </ul>
            </li>
      </ul>
    </nav>
    <div id="left-panel-footer">
      <a href="">
        <i class="fa fa-cog"></i>
      </a>
      <a href="{{route('logout')}}" class="disconnect">
        <i class="fa fa-eject"></i>
      </a>
    </div>
</div>
<div id="main-panel" role="main">
    <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-home"></i>&nbsp;<strong>Accueil</strong></a></li>
        @yield('breadcrumbs')
    </ol>
    <h1>@yield('h1')</h1>
    <div id="app" class="container-fluid">
        @yield('content')
    </div>
</div>
<div id="overlay" class="vertical-center hide">
  <div class="container">
  <div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
      <div class="panel shadowed">
        <div class="panel-body">
          <div class="vertical-center text-center">
          <i class="fa fa-cog fa-spin fa-2x fa-fw margin-bottom"></i> Veuillez patienter...
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
@endsection
