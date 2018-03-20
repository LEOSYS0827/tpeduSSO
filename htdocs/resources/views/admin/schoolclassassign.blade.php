@extends('layouts.dashboard')

@section('page_heading')
指派任教老師
@endsection

@section('section')
<div class="container">
	<div class="row">
	@if (session('error'))
	    <div class="alert alert-danger">
	    @foreach (session('error') as $line)
		{{ $line }}<br>
		@endforeach
	    </div>
	@endif
	@if (session('success'))
	    <div class="alert alert-success">
		{{ session('success') }}
	    </div>
	@endif
	<form id="form" action="" method="POST" action="{{ route('school.assignClass') }}">
    @csrf
    <input type="hidden" name="grade" value="{{ $my_grade }}">
    <input type="hidden" name="ou" value="{{ $my_ou }}">
	<div class="col-sm-12">
		<div class="panel panel-default">	  
		<div class="panel-heading">
			<h4>請勾選要指派的班級：</h4>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<select class="form-control" id="grade" name="grade" onchange="refresh_classes();">
				@foreach ($grades as $grade)
			    	<option value="{{ $grade }}" {{ $my_grade == $grade ? 'selected' : '' }}>{{ $grade }}年級</option>
			    @endforeach
				</select>
			</div>
			<div id="classes" class="form-group">
				@foreach ($classes as $class)
					<label class="checkbox-inline">
						<input type="checkbox" name="classes" value="{{ $class->ou }}">{{ $class->description }}
					</label>
				@endforeach
			</div>
		</div>
		</div>
	</div>
	
	<div class="col-sm-12">
		<div class="panel panel-default">
		<div class="panel-heading">
			<h4>請勾選要指派的老師：</h4>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<select class="form-control" id="ou" name="ou" onchange="refresh_teachers();">
				@foreach ($ous as $ou)
			    	<option value="{{ $ou->ou }}" {{ $my_ou == $ou->ou ? 'selected' : '' }}>{{ $ou->description }}</option>
			    @endforeach
				</select>
			</div>
			<div id="teachers" class="form-group">
				@foreach ($teachers as $teacher)
					<label class="checkbox-inline">
						<input type="checkbox" name="teachers" value="{{ $teacher->idno }}">{{ $teacher->name }}（{{ $teacher->title }}）
					</label>
				@endforeach
			</div>
		</div>
		</div>
	</div>

	<div class="col-sm-12">
		<div class="panel panel-default">
		<div class="panel-heading">
			<h4>請選擇您要進行的動作：</h4>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<button type="submit" class="btn btn-success" name="act" value="add">新增指派</button>
				<button type="submit" class="btn btn-primary" name="act" value="rep">取代指派</button>
				<button type="submit" class="btn btn-danger" name="act" value="del">移除指派</button>
			</div>
		</div>
		</div>
	</div>
	</form>
	<script type="text/javascript">
		window.axios.defaults.headers.common = {
			'X-Requested-With': 'XMLHttpRequest',
		};
		function refresh_classes() {
			axios.get('/school/classes/{{ $dc }}/' + $('#grade').val())
    			.then(response => {
    				$('#classes').find('label').remove();
   					response.data.forEach(
    					function add_checkbox(myclass) { 
    						$('#classes').append('<label class="checkbox-inline"><input type="checkbox" name="classes" value="' + myclass.ou + '">' + myclass.description + '</label>');
    					}
        			);
				})
				.catch(function (error) {
					console.log(error);
  				});
      	}
		function refresh_teachers() {
			axios.get('/school/teachers/{{ $dc }}/' + $('#ou').val())
    			.then(response => {
    				$('#teachers').find('label').remove();
   					response.data.forEach(
    					function add_checkbox(teacher) {
    						$('#teachers').append('<label class="checkbox-inline"><input type="checkbox" name="teachers" value="' + teacher.idno + '">' + teacher.name + '（' + teacher.title + '）</label>');
    					}
        			);
				})
				.catch(function (error) {
					console.log(error);
  				});
      	}
	</script>
	</div>
</div>
@endsection