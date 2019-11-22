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
            position: relative;
            background: #fffbfb;
            width: 100%;
            display: block;
            padding: 10px 20px 10px 35px;
            border-radius: 11px;
        }

        img {
            height: 20px;
            padding-right: 6px;
            position: absolute;
            left: 0px;
            top: 9px;
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
@foreach($lists as $list)
    <div>
        <div class="header-pdf">
            <h1>{{$list->list_title}}</h1>
            <p style="border-bottom: 1px solid;">{{$list->description}}</p>
        </div>

        <div>
            <ul id="myUL">
                @foreach($list->tasks as $task)
                    <li>
                        <div class="pdf-task-title">
                            @if($task['progress'] !== null && $task['progress'] !== '')
                                <img src="{{public_path().'/img/'.$task['progress'].'.png'}}" alt=""
                                     style="height: 20px;padding-right: 6px">
                                <img src="{{asset('img/'.$task['progress'].'.png')}}" alt=""
                                     style="height: 20px;padding-right: 6px">
                            @endif
                            <span>{{$task['text']}}</span>

                        </div>
                        @if(count($task['children']) > 0)
                            <ul class="nested">
                                @foreach($task['children'] as $task1)
                                    <li>
                                        <div class="pdf-task-title">
                                            @if($task1['progress'] !== null && $task1['progress'] !== '')
                                                <img src="{{public_path().'/img/'.$task1['progress'].'.png'}}" alt=""
                                                     style="height: 20px;padding-right: 6px">
                                            @endif
                                            <span>{{$task1['text']}}</span>


                                        </div>
                                        @if(count($task['children']) > 0)
                                            <ul class="nested">
                                                @foreach($task1['children'] as $task2)
                                                    <li>
                                                        <div class="pdf-task-title">
                                                            @if($task2['progress'] !== null && $task2['progress'] !== '')
                                                                <img
                                                                    src="{{public_path().'/img/'.$task2['progress'].'.png'}}"
                                                                    alt=""
                                                                    style="height: 20px;padding-right: 6px">
                                                                {{--                    <img src="{{asset('img/'.$task['progress'].'.png')}}" alt="" style="height: 20px;padding-right: 6px">--}}
                                                            @endif
                                                            <span>{{$task2['text']}}</span>

                                                        </div>

                                                        @if(count($task2['children']) > 0)
                                                            <ul class="nested">
                                                                @foreach($task2['children'] as $task3)
                                                                    <li>
                                                                        <div class="pdf-task-title">
                                                                            @if($task3['progress'] !== null && $task3['progress'] !== '')
                                                                                <img
                                                                                    src="{{public_path().'/img/'.$task3['progress'].'.png'}}"
                                                                                    alt=""
                                                                                    style="height: 20px;padding-right: 6px">
                                                                                {{--                    <img src="{{asset('img/'.$task['progress'].'.png')}}" alt="" style="height: 20px;padding-right: 6px">--}}
                                                                            @endif
                                                                            <span>{{$task3['text']}}</span>
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
        </div>
    </div>
@endforeach


<script>

</script>

</body>
</html>
