@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">WhatsApp Connection</div>
            <div class="card-body text-center">
                <h5 id="status-text">Status: Checking...</h5>
                <div id="qr-container" class="my-3"></div>
                <button id="refresh-qr" class="btn btn-primary">Refresh Status / QR</button>
                <form action="{{ route('whatsapp.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function checkStatus() {
        $.get("{{ route('whatsapp.status') }}", function(data) {
            $('#status-text').text('Status: ' + data.status);
            if(data.status === 'connected') {
                $('#qr-container').html('<div class="alert alert-success">Connected</div>');
            } else if(data.status === 'scan_qr') {
                loadQr();
            } else {
                $('#qr-container').html('<div class="alert alert-warning">Disconnected / Waiting</div>');
            }
        });
    }

    function loadQr() {
        $.get("{{ route('whatsapp.qr') }}", function(data) {
            if(data.qr_code) {
                $('#qr-container').html('<img src="' + data.qr_code + '" class="img-fluid" />');
            }
        });
    }

    $(document).ready(function() {
        checkStatus();
        $('#refresh-qr').click(checkStatus);
        
        // Auto refresh every 10 seconds if not connected
        setInterval(function(){
            if($('#status-text').text().indexOf('connected') === -1) {
                checkStatus();
            }
        }, 10000);
    });
</script>
@endsection
