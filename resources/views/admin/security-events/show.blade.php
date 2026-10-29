
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Event Keamanan</title>
    <style>
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .container {
            max-width: 850px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .row {
            padding: 14px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .label {
            display: block;
            margin-bottom: 6px;
            color: #6b7280;
            font-size: 13px;
        }

        .value {
            overflow-wrap: anywhere;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border: 0;
            border-radius: 7px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .back {
            background: #e5e7eb;
            color: #1f2937;
        }

        .resolve {
            background: #166534;
            color: white;
        }

        .message {
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 7px;
            background: #dcfce7;
            color: #166534;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Detail Event Keamanan</h1>

    <div class="card">
        @if (session('success'))
            <div class="message">{{ session('success') }}</div>
        @endif

        @if (session('info'))
            <div class="message">{{ session('info') }}</div>
        @endif

        <div class="row">
            <span class="label">ID Event</span>
            <div class="value">{{ $event->id }}</div>
        </div>

        <div class="row">
            <span class="label">Jenis Kejadian</span>
            <div class="value">{{ $event->event_type }}</div>
        </div>

        <div class="row">
            <span class="label">Severity</span>
            <div class="value">{{ ucfirst($event->severity) }}</div>
        </div>

        <div class="row">
            <span class="label">Deskripsi</span>
            <div class="value">{{ $event->description ?? '-' }}</div>
        </div>

        <div class="row">
            <span class="label">Status</span>
            <div class="value">{{ ucfirst($event->status) }}</div>
        </div>

        <div class="row">
            <span class="label">Waktu Deteksi</span>
            <div class="value">
                {{ $event->detected_at?->format('d-m-Y H:i:s') ?? '-' }}
            </div>
        </div>

        <div class="row">
            <span class="label">Waktu Penyelesaian</span>
            <div class="value">
                {{ $event->resolved_at?->format('d-m-Y H:i:s') ?? '-' }}
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('admin.security-dashboard') }}" class="button back">
                Kembali ke Dashboard
            </a>

            @if ($event->status !== 'resolved')
                <form
                    method="POST"
                    action="{{ route('admin.security-events.resolve', $event) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="button resolve">
                        Mark as Resolved
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
</body>
</html>
