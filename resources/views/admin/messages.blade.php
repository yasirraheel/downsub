@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="card">
    <div class="d-flex justify-between align-center" style="margin-bottom: 1.5rem;">
        <h3>Message Logs</h3>
        <div class="d-flex gap-2">
            <input type="text" placeholder="Search number..." class="form-control" style="width: 200px;">
            <select class="form-control" style="width: 150px;">
                <option>All Status</option>
                <option>Sent</option>
                <option>Failed</option>
                <option>Delivered</option>
            </select>
        </div>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>To / From</th>
                    <th>Message</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#99281</td>
                    <td>+92 300 9876543</td>
                    <td>Hello, your order #123 is confirmed.</td>
                    <td>Template</td>
                    <td><span class="text-success">Read</span></td>
                    <td>10 mins ago</td>
                </tr>
                <tr>
                    <td>#99280</td>
                    <td>+1 202 555 0122</td>
                    <td>Can I get a refund?</td>
                    <td>Incoming</td>
                    <td><span class="text-muted">Received</span></td>
                    <td>15 mins ago</td>
                </tr>
                <tr>
                    <td>#99279</td>
                    <td>+44 7700 900077</td>
                    <td>Special Offer just for you!</td>
                    <td>Campaign</td>
                    <td><span class="text-danger">Failed</span></td>
                    <td>1 hour ago</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
