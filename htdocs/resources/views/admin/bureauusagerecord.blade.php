@extends('layouts.superboard')

@section('page_heading')
<h1 class="page-header">系統作業日誌查詢</h1>
@endsection

@section('section')
<style>
#urtbody .hold {
	max-width: 222px;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
}
@media (max-width: 1200px) {
	#urtbody .hold {
		max-width: 140px;
	}
}
@media (max-width: 768px) {
	#urtbody .hold {
		max-width: 90px;
	}
}
</style>
<div class="container">
	<form id="query" action="{{ route('bureau.usagerecord') }}" method="POST">
	<div class="row">
		<div class="panel panel-default">	  
			<div class="panel-heading" style="overflow: hidden;">
				<div style="float: right;margin-left: 10px;">
					<button type="button" class="btn btn-success" onclick="$('#query').submit();">查詢</button>
				</div>
				<div style="float: right;margin-top: 4px;">
					<span>共有&nbsp;{{$total}}&nbsp;筆</span>
					@if($total > 0)
					<span>，目前顯示第&nbsp;{{$from}}&nbsp;～&nbsp;{{$to}}&nbsp;筆</span>
					@endif
				</div>
				<div style="float: left;margin-top: 2px;">
					作業日誌期間：
					<input type="text" name="dt1" value="{{ old('dt1') }}" maxlength="10" style="width: 100px;" placeholder="yyyymmdd"/>
					<span class="span-calendar add-on"><i class="glyphicon glyphicon-calendar"></i></span>
					～
					<input type="text" name="dt2" value="{{ old('dt2') }}" maxlength="10" style="width: 100px;" placeholder="yyyymmdd"/>
					<span class="span-calendar add-on"><i class="glyphicon glyphicon-calendar"></i></span>
					<input type="hidden" id="page" name="page" />
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-hover" style="margin: 0;">
					<thead>
						<tr>
							<th>登入人員</th>
							<th>登入IP</th>
							<th>作業時間</th>
							<th>作業模組</th>
							<th>作業內容</th>
							<th>備註</th>
						</tr>
						<tr style="background-color: rgba(40, 180, 240, 0.2);">
							<th><input type="text" name="user" style="width: 100%" value="{{ old('user') }}" /></th>
							<th><input type="text" name="ip" style="width: 100%" value="{{ old('ip') }}" /></th>
							<th>期間設定如上</th>
							<th><input type="text" name="module" style="width: 100%" value="{{ old('module') }}" /></th>
							<th><input type="text" name="content" style="width: 100%" value="{{ old('content') }}" /></th>
							<th><input type="text" name="note" style="width: 100%" value="{{ old('note') }}" /></th>
						</tr>
					</thead>
					<tbody id="urtbody">
					@if (!empty($data))
					@foreach ($data as $d)
						<tr>
							<td style="vertical-align: inherit;">
								<label>{{ $d['username'] }}</label>
							</td>
							<td style="vertical-align: inherit;">
								<label>{{ $d['ipaddress'] }}</label>
							</td>
							<td style="vertical-align: inherit;">
								<label>{{ date('Y-m-d', strtotime($d['created_at'])) }}<br/>{{ date('H:i:s', strtotime($d['created_at'])) }}</label>
							</td>
							<td style="vertical-align: inherit;">
								<label>{{ $d['module'] }}</label>
							</td>
							<td style="vertical-align: inherit;">
								<a href="#showcontent" data-toggle="modal" onclick="$('#showcontent').find('.panel-body').text($(this).find('label').text());"><label class="hold">{{ $d['content'] }}</label></a>
							</td>
							<td style="vertical-align: inherit;">
								<label>{{ $d['note'] }}</label>
							</td>
						</tr>
					@endforeach
					@endif
					</tbody>
				</table>
				<div>
					<nav aria-label="Page navigation">
						<ul class="pagination justify-content-center" style="justify-content: center;display: flex;">
							<!-- first -->
							@if($last_page > 3)
							@if($current_page == 1)
							<li class="page-item disabled"><a class="page-link">最前頁</a>
							@else
							<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="doQuery(1)">最前頁</a>
							@endif
							</li>
							@endif
							<!-- next 5 -->
							@if($current_page > 3)
							<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="doQuery({{ $current_page-5 > 1?($current_page-5):1 }})">...</a></li>
							@endif
							<!-- jump -2 to +2 -->
							@for ($i=($current_page<3?1:($current_page-2));$i<=($last_page-$current_page>2?($current_page+2):$last_page);$i++)
							@if($i == $current_page)
							<li class="page-item active"><a class="page-link">{{ $i }}</a>
							@else
							<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="doQuery({{ $i }})">{{ $i }}</a>
							@endif
							</li>
							@endfor
							<!-- previous 5 -->
							@if($last_page - $current_page > 2)
							<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="doQuery({{ $current_page+5 < $last_page?($current_page+5):$last_page }})">...</a></li>
							@endif
							<!-- latest -->
							@if($last_page > 3)
							@if($current_page == $last_page)
							<li class="page-item disabled"><a class="page-link">最末頁</a>
							@else
							<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="return doQuery({{ $last_page }})">最末頁</a>
							@endif
							</li>
							@endif
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
	@csrf
	</form>

	<div id="showcontent" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="panel-default">
					<div class="panel-heading">作業內容</div>
					<div class="panel-body" style="word-break: break-all;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function doQuery(p) {
		if(!$(this).parent().hasClass('disabled')){
			$("#page").val(p);
			$('#query').submit();
		}

		return false;
	}
</script>
@endsection