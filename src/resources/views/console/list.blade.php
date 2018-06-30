@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/categories/new') }}" class="btn btn-sm btn-info pull-right"><i class="fa fa-plus"></i> 添加分类</a>
        <h1 class="m-n font-thin h3">分类列表</h1>
    </div>
    <div class="wrapper-md">
        <sl-component-category-list></sl-component-category-list>
    </div>

@endsection