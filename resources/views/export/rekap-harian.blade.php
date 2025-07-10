<table style="border-collapse: collapse; width: 100%;">
    <tr>
        <th colspan="7" style="background-color: #FFD700; font-weight: bold; border: 1px solid black;">
            REKAP ABSENSI HARIAN
        </th>
    </tr>
    <tr>
        <th colspan="7" style="font-weight: bold; border: 1px solid black;">
            TANGGAL: {{ $tanggal->translatedFormat('l, d F Y') }}
        </th>
    </tr>

    <tr>
        <th style="border: 1px solid black;">Nama</th>
        <th style="border: 1px solid black;">Divisi</th>
        <th style="border: 1px solid black; text-align:center;">Absensi</th>
        <th style="border: 1px solid black;">Hadir</th>
        <th style="border: 1px solid black;">Izin</th>
        <th style="border: 1px solid black;">Sakit</th>
        <th style="border: 1px solid black;">Potongan</th>
    </tr>

    @foreach ($rekap as $row)
        <tr>
            <td style="border: 1px solid black;">{{ $row['nama'] }}</td>
            <td style="border: 1px solid black;">{{ $row['divisi'] }}</td>
            @foreach ($tanggalList as $tgl)
                @php
                    $absenText = $row['absensi'][$tgl] ?? '';
                    $bgColor = str_contains($absenText, 'Terlambat') ? 'background-color: pink;' : '';
                @endphp
                <td style="border: 1px solid black; text-align: center; {{ $bgColor }}">
                    {{ $absenText }}
                </td>
            @endforeach
            <td style="border: 1px solid black; text-align: center;">{{ $row['hadir'] }}</td>
            <td style="border: 1px solid black; text-align: center;">{{ $row['izin'] }}</td>
            <td style="border: 1px solid black; text-align: center;">{{ $row['sakit'] }}</td>
            <td style="border: 1px solid black; text-align: right;">
                {{ 'Rp ' . number_format($row['potongan'], 0, ',', '.') }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="7" style=" border: none;">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" style="background-color: #FFFACD; border: none;"><strong>Keterangan:</strong></td>
    </tr>
    <tr>
        <td colspan="3" style="background-color: #FFFACD; border: none;">H = Hadir</td>
    </tr>
    <tr>
        <td colspan="3" style="background-color: #FFFACD; border: none;">I = Izin</td>
    </tr>
    <tr>
        <td colspan="3" style="background-color: #FFFACD; border: none;">S = Sakit</td>
    </tr>
    <tr>
        <td colspan="3" style="background-color: #FFFACD; border: none;">Potongan = Denda keterlambatan pada hari
            tersebut</td>
    </tr>

</table>
