<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#f6f6f9;margin:0;padding:24px}
        .box{max-width:1100px;margin:0 auto;background:#ffffff;padding:18px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06)}
        table{width:100%;border-collapse:collapse;margin-top:12px}
        th,td{padding:8px 10px;border-bottom:1px solid #eee;text-align:left}
        th{background:#fafafa;font-weight:600}
        .admin-yes{color:green;font-weight:700}
        .admin-no{color:#888}
        .muted{color:#666;font-size:13px}
        .btn{display:inline-block;padding:6px 10px;border-radius:6px;border:none;background:#2f7cff;color:#fff;text-decoration:none}
        .small-btn{padding:4px 8px;font-size:13px}
    </style>
</head>
<body>
    <div class="box">
        <h1>Admin panel — All users</h1>
        <p class="muted">Displaying all users from the database. Only accessible for admins.</p>

        @if(session('success'))
            <div style="padding:8px;background:#e6fff3;border-radius:6px;margin-bottom:10px">{{ session('success') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Admin</th>
                    <th>Suspended</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->username ?? '-' }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->created_at }}</td>
                    <td>
                        @if($u->is_admin)
                            <span class="admin-yes">YES</span>
                        @else
                            <span class="admin-no">no</span>
                        @endif
                    </td>
                    <td>
                        @if($u->is_suspended)
                            <div style="color:#b02a37;font-weight:700">SUSPENDED</div>
                            <div class="muted">{{ $u->suspended_reason ?? 'no reason provided' }}</div>
                        @else
                            <div class="admin-no">active</div>
                        @endif
                    </td>
                    <td>
                        @if(isset($me) && $me && $me->is_admin)
                            <form method="POST" action="{{ url('/admin/user/' . $u->id . '/toggle-admin') }}" style="display:inline">
                                @csrf
                                @if($u->id == session('user_id') && $u->is_admin)
                                    <input type="hidden" name="allow_self_demote" value="0">
                                    <button class="btn small-btn" disabled>Cannot demote self</button>
                                @else
                                    <button class="btn small-btn" type="submit">{{ $u->is_admin ? 'Revoke admin' : 'Make admin' }}</button>
                                @endif
                            </form>

                            <!-- suspend / unsuspend -->
                            <form method="POST" action="{{ url('/admin/user/' . $u->id . '/suspend') }}" style="display:inline;margin-left:8px">
                                @csrf
                                @if($u->is_suspended)
                                    <input type="hidden" name="action" value="unsuspend">
                                    <button class="btn small-btn" type="submit">Unsuspend</button>
                                @else
                                    <input type="hidden" name="action" value="suspend">
                                    <input placeholder="Reason (why user suspended)" name="reason" style="padding:6px;border-radius:4px;border:1px solid #ddd;min-width:220px;margin-right:6px" />
                                    <button class="btn small-btn" type="submit">Suspend</button>
                                @endif
                            </form>

                            <!-- admin send message -->
                            <form method="POST" action="{{ url('/admin/user/' . $u->id . '/message') }}" style="display:inline;margin-left:8px">
                                @csrf
                                <input name="body" placeholder="Send message to user (reason/notice)" style="padding:6px;border-radius:4px;border:1px solid #ddd;min-width:260px;margin-right:6px" />
                                <button class="btn small-btn" type="submit">Send</button>
                            </form>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:12px"><a href="/home">Back to home</a></div>
    </div>
</body>
</html>
