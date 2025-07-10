<table style="border-collapse: collapse; width: 100%;">
    <tr>
        <th colspan="{{ count($tanggalList) + 8 }}"
            style="background-color: #FFD700; font-weight: bold; font-size: 22px;  text-align: center; border: 1px solid black;">
            ABSENSI KARYAWAN PT ALVIN BHAKTI MANDIRI
        </th>
    </tr>
    <tr>
        <th colspan="{{ count($tanggalList) + 8 }}"
            style="font-weight: bold;  text-align: center; bold; border: 1px solid black;">
            PERIODE {{ \Carbon\Carbon::parse($tanggalList[0])->translatedFormat('F Y') }}
        </th>
    </tr>

    {{-- Header Tanggal --}}
    <tr>
        <th style="border: 1px solid black;">Nama</th>
        <th style="border: 1px solid black;">Divisi</th>
        @foreach ($tanggalList as $tanggal)
            @php
                $carbonDate = \Carbon\Carbon::parse($tanggal);
                $isSunday = $carbonDate->isSunday();
            @endphp
            <th
                style="border: 1px solid black; text-align: center; {{ $isSunday ? 'background-color: #FF0000; color: white;' : '' }}">
                {{ $carbonDate->format('d') }}
            </th>
        @endforeach
        <th style="border: 1px solid black;">Hadir</th>
        <th style="border: 1px solid black;">Izin</th>
        <th style="border: 1px solid black;">Sakit</th>
        <th style="border: 1px solid black;">Gaji Pokok</th>
        <th style="border: 1px solid black;">Potongan</th>
        <th style="border: 1px solid black;">Gaji Akhir</th>
    </tr>

    {{-- Data Karyawan --}}
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
            <td style="border: 1px solid black;">{{ $row['hadir'] }}</td>
            <td style="border: 1px solid black;">{{ $row['izin'] }}</td>
            <td style="border: 1px solid black;">{{ $row['sakit'] }}</td>
            <td style="border: 1px solid black;">Rp {{ number_format($row['gaji_pokok'], 0, ',', '.') }}</td>
            <td style="border: 1px solid black;">{{ 'Rp ' . number_format($row['potongan'], 0, ',', '.') }}</td>

            <td style="border: 1px solid black;">{{ 'Rp ' . number_format($row['gaji_akhir'], 0, ',', '.') }}</td>
        </tr>
    @endforeach

    {{-- Keterangan --}}
    <tr>
        <td colspan="{{ count($tanggalList) + 7 }}" style="border: none;">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" style="border: none;"><strong>Keterangan:</strong></td>
    </tr>
    <tr>
        <td colspan="3" style="border: none;">H = Hadir</td>
    </tr>
    <tr>
        <td colspan="3" style="border: none;">I = Izin</td>
    </tr>
    <tr>
        <td colspan="3" style="border: none;">S = Sakit</td>
    </tr>
    <tr>
        <td colspan="3" style="border: none;">Potongan = Total potongan denda keterlambatan</td>
    </tr>
    <tr>
        <td colspan="3" style="border: none;">Gaji Akhir = Gaji pokok dikurangi total potongan</td>
    </tr>
</table>
