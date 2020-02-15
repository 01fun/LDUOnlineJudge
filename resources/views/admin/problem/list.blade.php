@extends('layouts.admin')

@section('title','题目管理 | 后台')

@section('content')

    <h2>问题管理</h2>
    <div class="table-responsive">
        {{$problems->appends($_GET)->links()}}
        <a href="javascript:$('td input[type=checkbox]').prop('checked',true)" class="btn border">全选</a>
        <a href="javascript:$('td input[type=checkbox]').prop('checked',false)" class="btn border">取消</a>

        <a href="javascript:update_hidden(0);" class="ml-3">题目状态公开</a>
        <a href="javascript:" class="text-gray" onclick="whatisthis('选中的题目将被公开，允许普通用户在题库中查看和提交!')">
            <i class="fa fa-question-circle-o" aria-hidden="true"></i>
        </a>

        <a href="javascript:update_hidden(1);" class="ml-3">状态设为隐藏</a>
        <a href="javascript:" class="text-gray" onclick="whatisthis('选中的题目将被隐藏，普通用户无法在题库中查看和提交，但不会影响竞赛!')">
            <i class="fa fa-question-circle-o" aria-hidden="true"></i>
        </a>

        <table class="table table-striped table-hover table-sm">
            <thead>
            <tr>
                <th></th>
                <th>题号</th>
                <th>题目</th>
                <th>出处</th>
                <th>特判</th>
                <th>解决/提交</th>
                <th>创建时间</th>
                <th>隐藏</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($problems as $item)
                <tr>
                    <td onclick="var cb=$(this).find('input[type=checkbox]');cb.prop('checked',!cb.prop('checked'))">
                        <input type="checkbox" value="{{$item->id}}" onclick="window.event.stopPropagation();" style="vertical-align:middle;zoom: 140%">
                    </td>
                    <td nowrap>{{$item->id}}</td>
                    <td nowrap><a href="{{route('problem',$item->id)}}" target="_blank">{{$item->title}}</a></td>
                    <td nowrap>{{$item->source}}</td>
                    <td nowrap>{{$item->spj?'特判':'否'}}</td>
                    <td nowrap>{{$item->solved}} / {{$item->submit}}</td>
                    <td nowrap>{{$item->created_at}}</td>
                    <td nowrap>
                        <a href="javascript:" onclick="update_hidden('{{1-$item->hidden}}',{{$item->id}});"
                            class="px-1" title="点击切换">{{$item->hidden?'隐藏*不可见':'公开'}}</a>
                    </td>
                    <td nowrap>
                        <a href="{{route('admin.problem.update_withId',$item->id)}}" target="_blank" class="px-1"
                           data-toggle="tooltip" title="修改">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        <a href="#" target="_blank" class="px-1" data-toggle="tooltip" title="测试数据">
                            <i class="fa fa-file" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{$problems->appends($_GET)->links()}}
    </div>
    <script>
        function update_hidden(hidden,id=-1) {
            if(id!==-1){  ///单独修改一个
                $('td input[type=checkbox]').prop('checked',false)
                $('td input[value='+id+']').prop('checked',true)
            }
            // 修改题目状态 1公开 or 0隐藏
            var pids=[];
            $('td input[type=checkbox]:checked').each(function () { pids.push($(this).val()); });
            $.post(
                '{{route('admin.problem.update_hidden')}}',
                {
                    '_token':'{{csrf_token()}}',
                    'pids':pids,
                    'hidden':hidden,
                },
                function (ret) {
                    if(id===-1){
                        Notiflix.Report.Init();
                        Notiflix.Report.Success('操作成功','已更新'+ret+'条数据!','confirm',function () {
                            location.reload();
                        })
                    }else{
                        location.reload();
                    }
                }
            );
        }
    </script>
@endsection