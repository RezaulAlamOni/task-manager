<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        ul, #myUL {
            list-style-type: none;
        }

        li {
            padding-top: 3px;
        }

        .active {
            display: block;
        }

        .header-pdf {
            padding: 0px 50px;
            text-align: center;

        }

        #myUL {
            padding: 20px 50px;
        }

        .caret {
            /*background: #f1eded;*/
            /*width: 100%;*/
            /*display: block;*/
            /*padding: 10px 20px;*/
            /*border-radius: 11px;*/
        }

        .pdf-task-title {
            background: #fffbfb;
            width: 100%;
            display: block;
            padding: 10px 20px;
            border-radius: 11px;
        }

        .tag {
            float: right;
            border-radius: 5px;
            padding: 1px 6px;
            margin-left: 2px;
        }
    </style>
</head>
<body>
<div class="header-pdf">
    <h1>{{$list->list_title}}</h1>
    <p style="border-bottom: 1px solid;">{{$list->description}}</p>
</div>

<ul id="myUL">
    @foreach($tasks as $task)
        <li>
            <div class="pdf-task-title">
                @if($task['progress'] !== null && $task['progress'] !== '')
                    <img src="{{public_path().'/img/'.$task['progress'].'.png'}}" alt=""
                         style="height: 20px;padding-right: 6px">
{{--                    <img src="{{asset('img/'.$task['progress'].'.png')}}" alt=""--}}
{{--                         style="height: 20px;padding-right: 6px">--}}
                @endif
                <span>{{$task['text']}}</span>

{{--                @if($task['date'] !== '0000-00-00')--}}
{{--                    <span class="tag" style="background: #fefffd">{{date('d-M',strtotime($task['date']))}}</span>--}}
{{--                @endif--}}
{{--                @foreach($task['tags'] as $tag)--}}
{{--                    <span class="tag" style="background: {{$tag['color']}}">{{$tag['text']}}</span>--}}
{{--                @endforeach--}}

            </div>
            @if(count($task['children']) > 0)
                <ul class="nested">
                    @foreach($task['children'] as $task1)
                        <li>
                            <div class="pdf-task-title">
                                @if($task1['progress'] !== null && $task1['progress'] !== '')
                                    <img src="{{public_path().'/img/'.$task1['progress'].'.png'}}" alt=""
                                         style="height: 20px;padding-right: 6px">
{{--                                                        <img src="{{asset('img/'.$task['progress'].'.png')}}" alt="" style="height: 20px;padding-right: 6px">--}}
                                @endif
                                <span>{{$task1['text']}}</span>

{{--                                @if($task1['date'] !== '0000-00-00')--}}
{{--                                    <span class="tag"--}}
{{--                                          style="background: #fefffd">{{date('d-M',strtotime($task['date']))}}</span>--}}
{{--                                @endif--}}
{{--                                @foreach($task1['tags'] as $tag)--}}
{{--                                    <span class="tag" style="background: {{$tag['color']}}">{{$tag['text']}}</span>--}}
{{--                                @endforeach--}}

                            </div>
                            @if(count($task['children']) > 0)
                                <ul class="nested">
                                    @foreach($task1['children'] as $task2)
                                        <li>
                                            <div class="pdf-task-title">
                                                @if($task2['progress'] !== null && $task2['progress'] !== '')
                                                    <img src="{{public_path().'/img/'.$task2['progress'].'.png'}}" alt=""
                                                         style="height: 20px;padding-right: 6px">
                                                    {{--                    <img src="{{asset('img/'.$task['progress'].'.png')}}" alt="" style="height: 20px;padding-right: 6px">--}}
                                                @endif
                                                <span>{{$task2['text']}}</span>

{{--                                                @if($task2['date'] !== '0000-00-00')--}}
{{--                                                    <span class="tag"--}}
{{--                                                          style="background: #fefffd">{{date('d-M',strtotime($task['date']))}}</span>--}}
{{--                                                @endif--}}

{{--                                                @foreach($task2['tags'] as $tag)--}}
{{--                                                    <span class="tag"--}}
{{--                                                          style="background: {{$tag['color']}}">{{$tag['text']}}</span>--}}
{{--                                                @endforeach--}}

                                            </div>

                                            @if(count($task2['children']) > 0)
                                                <ul class="nested">
                                                    @foreach($task2['children'] as $task3)
                                                        <li>
                                                            <div class="pdf-task-title">
                                                                @if($task3['progress'] !== null && $task3['progress'] !== '')
                                                                    <img
                                                                        src="{{public_path().'/img/'.$task3['progress'].'.png'}}"
                                                                        alt="" style="height: 20px;padding-right: 6px">
                                                                    {{--                    <img src="{{asset('img/'.$task['progress'].'.png')}}" alt="" style="height: 20px;padding-right: 6px">--}}
                                                                @endif
                                                                <span>{{$task3['text']}}</span>


{{--                                                                @if($task3['date'] !== '0000-00-00')--}}
{{--                                                                    <span class="tag"--}}
{{--                                                                          style="background: #fefffd">{{date('d-M',strtotime($task['date']))}}</span>--}}
{{--                                                                @endif--}}
{{--                                                                @foreach($task3['tags'] as $tag)--}}
{{--                                                                    <span class="tag"--}}
{{--                                                                          style="background: {{$tag['color']}}">{{$tag['text']}}</span>--}}
{{--                                                                @endforeach--}}

                                                            </div>
                                                            @if(count($task3['children']) > 0)
                                                                <ul class="nested">
                                                                    @foreach($task3['children'] as $task5)
                                                                        <li>
                                                                            <div class="pdf-task-title">
                                                                                @if($task5['progress'] !== null && $task5['progress'] !== '' )
                                                                                    <img
                                                                                        src="{{public_path().'/img/'.$task5['progress'].'.png'}}"
                                                                                        alt=""
                                                                                        style="height: 20px;padding-right: 6px">
                                                                                    {{--                    <img src="{{asset('img/'.$task['progress'].'.png')}}" alt="" style="height: 20px;padding-right: 6px">--}}
                                                                                @endif
                                                                                <span>{{$task5['text']}}</span>

{{--                                                                                @if($task5['date'] !== '0000-00-00')--}}
{{--                                                                                    <span class="tag"--}}
{{--                                                                                          style="background: #fefffd">{{date('d-M',strtotime($task['date']))}}</span>--}}
{{--                                                                                @endif--}}

{{--                                                                                @foreach($task5['tags'] as $tag)--}}
{{--                                                                                    <span class="tag"--}}
{{--                                                                                          style="background: {{$tag['color']}}">{{$tag['text']}}</span>--}}
{{--                                                                                @endforeach--}}

                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>

<script>

</script>

</body>
</html>
