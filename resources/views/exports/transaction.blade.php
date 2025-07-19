<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Event</th>
            <th>Tiket</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telepon</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d F Y, H:i:s') }}</td>
            <td>{{ $transaction->ticket->event->nama }}</td>
            <td>{{ $transaction->ticket->nama }}</td>
            <td>{{ number_format($transaction->ticket->harga) }}</td>
            <td>{{ $transaction->jumlah }}</td>
            <td>{{ number_format($transaction->ticket->harga * $transaction->jumlah) }}</td>
            <td>{{ $transaction->nama }}</td>
            <td>{{ $transaction->email }}</td>
            <td>{{ $transaction->telepon }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="6" style="text-align: right;"><b>Total Omset</b></td>
            <td><b>{{ number_format($total) }}</b></td>
        </tr>
    </tbody>
</table>