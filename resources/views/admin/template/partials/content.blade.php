<section>
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h6>@yield('title')</h6>
			</div>
			<div class="panel-body">
				@include('flash::message')
				@include('admin.template.partials.errors')
				@yield('content')
			</div>
		</div>
	</div>
	
</section>