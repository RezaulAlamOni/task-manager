<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        ul, #myUL {
            list-style-type: none;
            padding: 5px 25px;
        }

        li {
            padding: 5px 0px;
        }

        .active {
            display: block;
        }

        .header-pdf {
            padding: 0px 50px;

        }

        #myUL {
            padding: 20px 50px;
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
            <span class="caret">{{$task['text']}}</span>
            @if(count($task['children']) > 0)
                <ul class="nested">
                    @foreach($task['children'] as $task1)
                        <li>
                            <span class="caret">{{$task1['text']}}</span>
                            @if(count($task['children']) > 0)
                                <ul class="nested">
                                    @foreach($task1['children'] as $task2)
                                        <li>
                                            <span class="caret">{{$task2['text']}}</span>
                                            @if(count($task2['children']) > 0)
                                                <ul class="nested">
                                                    @foreach($task2['children'] as $task3)
                                                        <li>
                                                            <span class="caret">{{$task3['text']}}</span>
                                                            @if(count($task3['children']) > 0)
                                                                <ul class="nested">
                                                                    @foreach($task3['children'] as $task5)
                                                                        <li>
                                                                            <span class="caret">{{$task5['text']}}</span>

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
