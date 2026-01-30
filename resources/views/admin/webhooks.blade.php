@extends('layouts.admin')

@section('title', 'Webhooks')

@section('content')
<div class="card">
    <h3>Global Webhook Configuration</h3>
    <p class="text-muted" style="margin-bottom: 1.5rem;">Configure where incoming messages and events should be forwarded.</p>

    <form>
        <div class="form-group">
            <label class="form-label">Webhook URL</label>
            <input type="url" class="form-control" value="https://mysite.com/api/webhook" placeholder="https://your-domain.com/api/webhook">
        </div>
        <div class="form-group">
            <label class="form-label">Secret Key</label>
            <input type="text" class="form-control" value="wh_sec_klj234klj234klj" readonly>
        </div>
        <div class="form-group">
            <label class="d-flex align-center">
                <input type="checkbox" checked style="margin-right: 0.5rem;">
                <span class="text-muted">Enable Webhook</span>
            </label>
        </div>
        <button class="btn btn-primary">Save Changes</button>
    </form>
</div>

<div class="card">
    <h3>Recent Deliveries</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Event ID</th>
                <th>Type</th>
                <th>URL</th>
                <th>Response</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>evt_123456789</td>
                <td>message.received</td>
                <td>.../api/webhook</td>
                <td><span class="text-success">200 OK</span></td>
                <td>2 mins ago</td>
            </tr>
            <tr>
                <td>evt_123456788</td>
                <td>message.received</td>
                <td>.../api/webhook</td>
                <td><span class="text-danger">500 Error</span></td>
                <td>5 mins ago</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
