<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>{{ $emailData['subject'] }}</title>
    @include('vendor.emailTemplates.bootstrap')
    <style>
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */

        /*All the styling goes here*/

        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            background-color: #f6f6f6;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        p,
        ul,
        ol {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
        }

        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */

        .body {
            background-color: #f6f6f6;
            width: 100%;
        }

        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            padding: 10px;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            padding: 10px;
        }

        .main {
            background: #ffffff;
            border-radius: 3px;
            width: 100%;
        }

        .card-body {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 0;
        }

        .card-footer {
            background-color: rgba(255, 255, 255, 0.03);
            border-top: none;
        }
    </style>
</head>
<body>
<span class="preheader">{{ $emailData['subject'] }}</span>
<table class="body">
    <tr>
        <td></td>
        <td class="container">
            <div class="content main">
                <h5>Hi {{ $emailData['name'] }},</h5>
                <p>
                    &nbsp; &nbsp; &nbsp; &nbsp; Daily report for project titled <b>{{ $project->name }}</b>.
                </p>
                <div class="card">
                    <div class="card-header">
                        <span class="float-left"><b>Project Title:</b> {{ $project->name }}</span>
                        <span class="float-right"><b>Date:</b> {{ \Carbon\Carbon::parse($date)->format('d F, Y') }}</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                            <th>Task Title</th>
                            <th>Action Type</th>
                            <th>Action By User</th>
                            <th>Action Date</th>
                            </thead>
                            <tbody>
                            @foreach($project->action_log as $log)
                                <tr>
                                    <td>{{ $log->title }}</td>
                                    <td>{{ $log->log_type }}</td>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->action_at)->format('d F, Y \a\t H:i A') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-muted">
                        <p style="margin-bottom: 0px;">Thank you,</p>
                        <p><a href="{{ config('app.url') }}">{{ config('app.name') }}</a></p>
                    </div>
                </div>
        </td>
        <td></td>
    </tr>
</table>
</body>
</html>
