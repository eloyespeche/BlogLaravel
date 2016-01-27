<div class="container">
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">MiBlog</a>
      </div>   
      
      @if(Auth::user())

      <div class="collapse navbar-collapse navbar-left" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class=""><a href="#">Inicio<span class="sr-only">(current)</span></a></li>
          <li class=""><a href="{{ route('admin.users.index') }}">Usuarios<span class="sr-only">(current)</span></a></li>
          <li class=""><a href="#">Articulos<span class="sr-only">(current)</span></a></li>
          <li class=""><a href="{{ route('admin.categories.index') }}">Categorias<span class="sr-only">(current)</span></a></li>
          <li class=""><a href="#">Imágenes<span class="sr-only">(current)</span></a></li>
          <li class=""><a href="{{ route('admin.tags.index') }}">Tags<span class="sr-only">(current)</span></a></li>
        </ul>
      </div>
      
      @endif

      <div class="collapse navbar-collapse navbar-right">
        @if(Auth::user())
          <ul class="nav navbar-nav">
           <li><a href="#">Página Principal</a></li>
            <li class="dropdown">
             <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name  }} <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
               <li><a href="{{ route('admin.auth.logout') }}">Salir</a></li>
             </ul>
           </li>
         </ul>
        @else
          <ul class="nav navbar-nav">
           <li><a href="#">Página Principal</a></li>
            <li class="dropdown">
             <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cuenta<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
               <li><a href="{{ route('admin.auth.login') }}">Iniciar Sesión</a></li>
             </ul>
           </li>
         </ul>
        @endif
      </div>
    </div>
  </nav>
</div>

