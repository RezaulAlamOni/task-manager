@foreach($projects as $project)
    <li>
        <a href="/project-dashboard/{{$project->id}}">
            <i class="fa fa-fw fa-tasks"></i>
            <span class="mm-text ">{{$project->name}}</span>
        </a>
    </li>
@endforeach
