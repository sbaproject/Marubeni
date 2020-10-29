@extends('layouts.master')

@section('css')
	<link rel="stylesheet" href="css/user/03_application_list.css">
@endsection

@section('content')
<section class="content">
	<h4 class="mb-2">Application List</h4>
	<div class="card">
		<div class="card-body p-4">
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<a href="">
							<div class="application-item">
								Entertainment
							</div>
						</a>
					</div>
					<div class="col-sm-4">
						<a href="/pages/examples/05-business-trip-application.html">
							<div class="application-item">
								Business Trip
							</div>
						</a>
					</div>
					<div class="col-sm-4">
						<a href="/pages/examples/04-leave-application.html">
							<div class="application-item">
								Leave
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->

</section>
@endsection