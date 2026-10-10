@extends('layouts.admin')
@section('title', 'Dashboard')

@section('styles')
<style>
.dashboard-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; margin-bottom:24px; }
.dashboard-stat { display:flex; align-items:center; gap:14px; padding:20px; background:#fff; border:1px solid #eee; border-radius:14px; color:#1f2937; text-decoration:none; }
.dashboard-stat:hover { box-shadow:0 8px 20px rgba(0,0,0,.07); transform:translateY(-2px); }
.dashboard-stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
.dashboard-stat strong { display:block; font-size:26px; line-height:1.1; color:#111827; }
.dashboard-stat span { display:block; margin-top:5px; font-size:13px; color:#6b7280; }
.dashboard-messages { background:#fff; border:1px solid #eee; border-radius:14px; overflow:hidden; }
.dashboard-messages .panel-header { padding:16px 20px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; }
.dashboard-messages .panel-header h2 { margin:0; font-size:15px; }
.dashboard-messages .panel-header a { color:var(--admin-primary); text-decoration:none; }
.dashboard-messages .msg-row { display:flex; align-items:center; gap:12px; padding:13px 20px; border-bottom:1px solid #f3f4f6; color:inherit; text-decoration:none; }
.dashboard-messages .msg-row:last-child { border-bottom:0; }
.dashboard-messages .msg-row:hover { background:#fafafa; }
.dashboard-messages .msg-avatar { width:38px; height:38px; border-radius:50%; background:#eef2ff; display:flex; align-items:center; justify-content:center; color:#4f46e5; font-weight:700; flex-shrink:0; }
.dashboard-messages .msg-body { flex:1; min-width:0; }
.dashboard-messages .msg-name, .dashboard-messages .msg-subj { margin:0; }
.dashboard-messages .msg-name { font-size:13.5px; font-weight:600; color:#111827; }
.dashboard-messages .msg-subj { margin-top:3px; font-size:12.5px; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.dashboard-messages .msg-meta { display:flex; flex-direction:column; align-items:flex-end; gap:5px; flex-shrink:0; }
.dashboard-messages .msg-time { color:#9ca3af; font-size:11px; white-space:nowrap; }
.dashboard-messages .badge { padding:3px 9px; border-radius:99px; font-size:11px; font-weight:600; }
.dashboard-messages .badge-unread { background:#FEF3C7; color:#B45309; }
.dashboard-messages .badge-read { background:#F3F4F6; color:#6B7280; }
.dashboard-messages .no-data { padding:36px 20px; text-align:center; color:#9ca3af; }
@media(max-width:960px) { .dashboard-stats { grid-template-columns:repeat(2,minmax(0,1fr)); } }
@media(max-width:560px) { .dashboard-stats { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')
<div class="dashboard-stats">
    <a href="{{ route('admin.blogs.index') }}" class="dashboard-stat">
        <div class="dashboard-stat-icon" style="background:#EFF6FF;color:#2563EB"><i class="fas fa-blog"></i></div>
        <div><strong>{{ $stats['blogs'] }}</strong><span>Blogs</span></div>
    </a>
    <a href="{{ route('admin.website-content.index', 'services') }}" class="dashboard-stat">
        <div class="dashboard-stat-icon" style="background:#ECFDF5;color:#059669"><i class="fas fa-concierge-bell"></i></div>
        <div><strong>{{ $stats['services'] }}</strong><span>Services</span></div>
    </a>
    <a href="{{ route('admin.galleries.index') }}" class="dashboard-stat">
        <div class="dashboard-stat-icon" style="background:#FFF7ED;color:#EA580C"><i class="fas fa-images"></i></div>
        <div><strong>{{ $stats['galleries'] }}</strong><span>Gallery Items</span></div>
    </a>
    <a href="{{ route('admin.website-pages.index') }}" class="dashboard-stat">
        <div class="dashboard-stat-icon" style="background:#F0F9FF;color:#0284C7"><i class="fas fa-file-alt"></i></div>
        <div><strong>{{ $stats['pages'] ?? '—' }}</strong><span>Fixed Website Pages</span></div>
    </a>
    <a href="{{ route('admin.sliders.index') }}" class="dashboard-stat">
        <div class="dashboard-stat-icon" style="background:#FDF2F8;color:#DB2777"><i class="fas fa-images"></i></div>
        <div><strong>{{ $stats['sliders'] }}</strong><span>Sliders</span></div>
    </a>
    <a href="{{ route('admin.contacts.index') }}" class="dashboard-stat">
        <div class="dashboard-stat-icon" style="background:#FEF3C7;color:#D97706"><i class="fas fa-envelope"></i></div>
        <div><strong>{{ $stats['messages'] }}</strong><span>Messages ({{ $stats['unread_messages'] }} unread)</span></div>
    </a>
</div>

<div class="dashboard-messages">
    <div class="panel-header">
        <h2><i class="fas fa-envelope" style="color:#DB2777;margin-right:7px"></i>Recent Messages</h2>
        <a href="{{ route('admin.contacts.index') }}">View All</a>
    </div>
    @forelse($recentMessages as $msg)
        <a href="{{ route('admin.contacts.show', $msg->id) }}" class="msg-row">
            <div class="msg-avatar">{{ strtoupper(substr($msg->name, 0, 1)) }}</div>
            <div class="msg-body">
                <p class="msg-name">{{ $msg->name }}</p>
                <p class="msg-subj">{{ $msg->subject ?? \Illuminate\Support\Str::limit($msg->message, 40) }}</p>
            </div>
            <div class="msg-meta">
                <span class="msg-time">{{ \Carbon\Carbon::parse($msg->created_at)->diffForHumans() }}</span>
                <span class="badge {{ $msg->is_read ? 'badge-read' : 'badge-unread' }}">{{ $msg->is_read ? 'Read' : 'New' }}</span>
            </div>
        </a>
    @empty
        <div class="no-data"><i class="fas fa-inbox"></i><p>No messages yet</p></div>
    @endforelse
</div>
@endsection
