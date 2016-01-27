@extends('admin.template.main')

@section('title','Crear Usuario')

@section('content')

	{!! Form::open(['route' => 'admin.users.store', 'method' => 'POST']) !!}

		<div class="form-group">
			{!! Form::label('name', 'Nombre') !!}
			{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre Completo',
			'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('email', 'Correo Electrónico') !!}
			{!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'example@mail.com', 
			'required']) !!}
		</div>
		
		<div class="form-group">
			{!! Form::label('password', 'Contraseña') !!}
			{!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Contraseña', 
			'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('type', 'Tipo') !!}
			{!! Form::select('type', ['' => 'Seleccione un tipo', 'member' => 'Miembro', 'admin' => 'Administrador'],
			null, ['class' => 'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Registrar Usuario', ['class' => 'btn btn-primary']) !!}
		</div>

	{!! Form::close() !!}

@endsection