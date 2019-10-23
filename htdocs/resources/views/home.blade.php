@extends('layouts.userboard')

@section('page_heading')
<h1 class="page-header">主控面板</h1>
@endsection

@section('section')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default" style="margin-top: 20px">
                <!--<div class="card-header">主控面板</div>-->
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    您已經登入系統！
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
