
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Dashboard | Kemahasiswaan FIT</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            margin-bottom: 28px;
        }

        .header h1 {
            margin-bottom: 8px;
            font-size: 28px;
        }

        .header p {
            color: #6b7280;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .card {
            padding: 22px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .card h3 {
            margin: 0 0 14px;
            font-size: 14px;
            color: #6b7280;
        }

        .number {
            font-size: 30px;
            font-weight: bold;
        }

        .section-title {
            margin-bottom: 18px;
        }


        .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 14px;
            margin-bottom: 22px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 180px;
        }

        .filter-group label {
            font-size: 13px;
            font-weight: bold;
            color: #4b5563;
        }

        .filter-group select,
        .filter-group input {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            color: #1f2937;
            font-size: 14px;
        }

        .filter-button,
        .reset-button {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .filter-button {
            border: none;
            background: #1d4ed8;
            color: white;
        }

        .reset-button {
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f9fafb;
            color: #4b5563;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .critical {
            background: #fee2e2;
            color: #991b1b;
        }

        .high {
            background: #ffedd5;
            color: #9a3412;
        }

        .medium {
            background: #fef3c7;
            color: #92400e;
        }

        .low {
            background: #dbeafe;
            color: #1e40af;
        }

        .status {
            background: #f3f4f6;
            color: #374151;
        }

        .empty {
            padding: 30px;
            text-align: center;
            color: #6b7280;
        }

        @media (max-width: 800px) {
            body {
                padding: 16px;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 480px) {
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <header class="header">
        <h1>Security Dashboard</h1>
        <p>Monitoring keamanan sistem Kemahasiswaan FIT</p>
    </header>

    <section class="stats">
        <div class="card">
            <h3>Total Percobaan Login</h3>
            <div class="number">{{ $totalAttempts }}</div>
        </div>

        <div class="card">
            <h3>Login Gagal</h3>
            <div class="number">{{ $failedAttempts }}</div>
        </div>

        <div class="card">
            <h3>Login Berhasil</h3>
            <div class="number">{{ $successfulAttempts }}</div>
        </div>

        <div class="card">
            <h3>Event Keamanan Terbuka</h3>
            <div class="number">{{ $openEvents }}</div>
        </div>
    </section>


    <section class="card">
        <h2 class="section-title">Kejadian Keamanan Terbaru</h2>

        <form method="GET"
            action="{{ route('admin.security-dashboard') }}"
            class="filter-form">

            <div class="filter-group">
                <label for="status">Status Event</label>
                <select name="status" id="status">
                    <option value="">Semua Status</option>
                    <option value="open"
                        {{ $status === 'open' ? 'selected' : '' }}>
                        Open
                    </option>
                    <option value="resolved"
                        {{ $status === 'resolved' ? 'selected' : '' }}>
                        Resolved
                    </option>
                </select>
            </div>

            <div class="filter-group">
                <label for="search">Cari IP Address atau Deskripsi</label>
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ $search }}"
                    placeholder="Contoh: 127.0.0.1 atau brute force"
                >
            </div>

            <div class="filter-group">
                <label for="severity">Tingkat Keparahan</label>
                <select name="severity" id="severity">
                    <option value="">Semua Severity</option>
                    <option value="high"
                        {{ $severity === 'high' ? 'selected' : '' }}>
                        High
                    </option>
                    <option value="medium"
                        {{ $severity === 'medium' ? 'selected' : '' }}>
                        Medium
                    </option>
                    <option value="low"
                        {{ $severity === 'low' ? 'selected' : '' }}>
                        Low
                    </option>
                </select>
            </div>

            <button type="submit" class="filter-button">
                Terapkan Filter
            </button>

            <a href="{{ route('admin.security-dashboard') }}"
            class="reset-button">
                Reset
            </a>
        </form>

        <div class="table-wrapper">

            <table>
                <thead>
                    <tr>
                        <th>Waktu Deteksi</th>
                        <th>Jenis Kejadian</th>
                        <th>Severity</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentEvents as $event)
                        <tr>
                            <td>
                                {{ $event->detected_at?->format('d-m-Y H:i') ?? '-' }}
                            </td>

                            <td>{{ $event->event_type }}</td>

                            <td>{{ $event->loginAttempt?->ip_address ?? '-' }}</td>

                            <td>
                                <span class="badge {{ strtolower($event->severity) }}">
                                    {{ ucfirst($event->severity) }}
                                </span>
                            </td>

                            <td>{{ $event->description ?? '-' }}</td>

                            <td>
                                <span class="badge status">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.security-events.show', $event) }}">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty">
                                Belum ada kejadian keamanan yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
</body>
</html>
