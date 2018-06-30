@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/categories') }}" class="btn btn-sm btn-default pull-right">返回</a>
        <h1 class="m-n font-thin h3">添加分类</h1>
    </div>
    <div class="wrapper-md">
        <sl-component-category-new></sl-component-category-new>
    </div>

@endsection

@push('stylesheets')
<link rel="stylesheet" href="{{ url('bower_components/wangEditor/dist/css/wangEditor.min.css') }}" type="text/css"/>
@endpush

@push('pre_scripts')
<script src="{{ url('bower_components/wangEditor/dist/js/wangEditor.min.js') }}"></script>
@endpush