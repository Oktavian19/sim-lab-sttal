<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Laboratorium;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetailAlat;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PeminjamanController extends Controller
{
    private $relations = ['peminjam', 'laboratorium'];

    public function create()
    {
        $labs = Laboratorium::where('status', 'aktif')->get();

        $alats = Alat::with('laboratorium')->get();

        return view('peminjaman.create', compact('labs', 'alats'));
    }

    public function store(Request $request)
    {
        $startDateTime = $request->tanggal_peminjaman.' '.$request->start_time.':00';
        $endDateTime = $request->tanggal_peminjaman.' '.$request->end_time.':00';

        $request->merge([
            'start_datetime' => $startDateTime,
            'end_datetime' => $endDateTime,
        ]);

        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'hanya_alat' => 'nullable',
            'jumlah_peserta' => 'required_without:hanya_alat|nullable|integer|min:1',
            'id_lab' => 'required|exists:laboratorium,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'alat_ids' => 'required_with:hanya_alat|nullable|array',
            'alat_ids.*' => 'exists:alat,id',
            'jumlah_alat' => 'nullable|array',
            'jumlah_alat.*' => 'nullable|integer|min:1',
        ]);

        $isHanyaAlat = $request->has('hanya_alat');
        $idLabToSave = $isHanyaAlat ? null : $request->id_lab;
        $pesertaToSave = $isHanyaAlat ? 1 : $request->jumlah_peserta;

        $conflictErrors = Peminjaman::checkAvailability(
            $idLabToSave,
            $request->alat_ids ?? [],
            $request->jumlah_alat ?? [],
            $startDateTime,
            $endDateTime,
        );

        if ($conflictErrors) {
            return response()->json([
                'status' => false,
                'title' => 'Gagal menyetujui peminjaman.',
                'message' => 'Beberapa sumber daya tidak tersedia.',
                'errors' => $conflictErrors,
            ]);
        }

        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::create([
                'id_peminjam' => Auth::id(),
                'id_lab' => $idLabToSave,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'kegiatan' => $request->kegiatan,
                'jumlah_peserta' => $pesertaToSave,
                'status_pengajuan' => 'pending',
            ]);

            if ($request->has('alat_ids')) {
                foreach ($request->alat_ids as $alatId) {
                    $jumlahDiminta = isset($request->jumlah_alat[$alatId]) ? (int) $request->jumlah_alat[$alatId] : 1;

                    PeminjamanDetailAlat::create([
                        'peminjaman_id' => $peminjaman->id,
                        'id_alat' => $alatId,
                        'jumlah' => $jumlahDiminta,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'title' => 'Permohonan Terkirim!',
                'message' => 'Data peminjaman telah masuk ke sistem kami.',
                'info' => 'Status saat ini: Menunggu Persetujuan Admin.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function schedule()
    {
        $labs = Laboratorium::where('status', '!=', 'nonaktif')->get();

        $alats = Alat::with('laboratorium')->get();

        $peminjamans = Peminjaman::with(['peminjam', 'detailAlat'])
            ->whereIn('status_pengajuan', ['pending', 'disetujui', 'dipinjam'])
            ->get()
            ->map(function ($item) {
                $alatDipinjam = [];
                foreach ($item->detailAlat as $detail) {
                    $alatDipinjam[$detail->id_alat] = $detail->jumlah;
                }

                return [
                    'id' => $item->id,
                    'id_lab' => $item->id_lab,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                    'status_pengajuan' => $item->status_pengajuan,
                    'kegiatan' => $item->kegiatan,
                    'peminjam_nama' => $item->peminjam->nama,
                    'alat_dipinjam' => $alatDipinjam,
                ];
            });

        return view('peminjaman.schedule', compact('labs', 'alats', 'peminjamans'));
    }

    public function riwayatUser(Request $request)
    {
        $userId = Auth::id();

        $query = Peminjaman::with('laboratorium')
            ->where('id_peminjam', $userId);

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'selesai') {
                $query->whereIn('status_pengajuan', ['selesai', 'ditolak', 'dibatalkan']);
            } else {
                $query->where('status_pengajuan', $request->status);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                    ->orWhereHas('laboratorium', function ($lab) use ($search) {
                        $lab->where('nama_lab', 'like', "%{$search}%");
                    });
            });
        }

        $peminjaman = $query->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('peminjaman.riwayat.user.list', compact('peminjaman'))->render();
        }

        return view('peminjaman.riwayat.user.index', compact('peminjaman'));
    }

    public function showRiwayatUser($id)
    {
        $peminjaman = Peminjaman::with(['laboratorium', 'detailAlat', 'pengembalian'])->findOrFail($id);

        return view('peminjaman.riwayat.user.show', compact('peminjaman'));
    }

    public function cancel($id)
    {
        $peminjaman = Peminjaman::where('id', $id)
            ->where('id_peminjam', Auth::id())
            ->firstOrFail();

        if ($peminjaman->status_pengajuan !== 'pending') {
            return back()->with('error', 'Pengajuan tidak dapat dibatalkan.');
        }

        $peminjaman->update([
            'status_pengajuan' => 'dibatalkan',
        ]);

        return back()->with('success', 'Pengajuan berhasil dibatalkan.');
    }

    public function indexValidasi()
    {
        return view('peminjaman.validasi.index');
    }

    public function listValidasi()
    {
        $peminjaman = Peminjaman::with($this->relations)
            ->where('status_pengajuan', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return DataTables::of($peminjaman)
            ->addIndexColumn()
            ->addColumn('tanggal_pengajuan', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->created_at->translatedFormat('d F Y').'</div>
                ';
            })
            ->addColumn('peminjam', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->peminjam->nama.'</div>
                <div class="text-xs text-slate-400">NRP: '.$peminjaman->peminjam->nrp.'</div>
            ';
            })
            ->addColumn('rencana_pinjam', function ($peminjaman) {
                $hours = $peminjaman->start_time->diffInHours($peminjaman->end_time);

                $result = '-';

                if ($hours < 24) {
                    $result = '
                        <div class="font-medium text-slate-900">
                            <i class="fa-regular fa-calendar mr-1"></i>'.$peminjaman->start_time->translatedFormat('d F Y').'
                        </div>
                        <div class="text-xs text-slate-400">
                            '.$peminjaman->start_time->format('H:i').' - '.$peminjaman->end_time->format('H:i').'
                        </div>
                    ';
                } else {
                    $days = (int) $peminjaman->start_time->diffInDays($peminjaman->end_time);

                    $result = '
                        <div class="font-medium text-slate-900">
                            <i class="fa-regular fa-calendar mr-1"></i>'.$peminjaman->start_time->translatedFormat('d F Y').'
                        </div>
                        <div class="text-xs text-slate-400">
                            '.$days.' hari
                        </div>
                    ';
                }

                return $result;
            })
            ->addColumn('kegiatan', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->kegiatan.'</div>
                <div class="text-xs text-slate-400">'.($peminjaman->laboratorium ? $peminjaman->laboratorium->nama_lab : 'Hanya peminjaman alat').'</div>
                ';
            })
            ->addColumn('jumlah_peserta', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->jumlah_peserta.' Orang</div>
                ';
            })

            ->addColumn('aksi', function ($peminjaman) {
                return '
            <div class="flex items-center justify-center gap-3">
                <a href="/peminjaman/'.$peminjaman->id.'/showValidasi" onclick="modalAction(this.href); return false;"
                    class="bg-blue-600 text-white hover:bg-blue-700 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-magnifying-glass"></i> Validasi
                </a>
            </div>
            ';
            })
            ->rawColumns(['tanggal_pengajuan', 'peminjam', 'rencana_pinjam', 'kegiatan', 'jumlah_peserta', 'aksi'])
            ->make(true);
    }

    public function showValidasi($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $detailAlat = PeminjamanDetailAlat::where('peminjaman_id', $id)->with('alat.laboratorium')->get();

        return view('peminjaman.validasi.show', compact('peminjaman', 'detailAlat'));
    }

    public function approve(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $alatIds = $peminjaman->detailAlat()->pluck('id_alat')->toArray();

        $conflictErrors = Peminjaman::checkAvailability(
            $peminjaman->id_lab,
            $alatIds,
            $peminjaman->start_time,
            $peminjaman->end_time,
            $peminjaman->id
        );

        if ($conflictErrors) {
            return response()->json([
                'status' => false,
                'title' => 'Gagal menyetujui peminjaman.',
                'message' => 'Beberapa sumber daya tidak tersedia.',
                'errors' => $conflictErrors,
            ]);
        }

        $peminjaman->update([
            'status_pengajuan' => 'disetujui', 'catatan_admin' => $request->catatan_admin,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman disetujui.',
        ]);
    }

    public function reject(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'catatan_admin' => 'required|string|min:5',
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi!',
            'catatan_admin.min' => 'Alasan penolakan terlalu singkat.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ]);
        }

        $peminjaman->update([
            'status_pengajuan' => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Peminjaman ditolak.',
        ]);
    }

    public function indexMonitoring()
    {
        return view('peminjaman.monitoring.index');
    }

    public function listMonitoring()
    {
        $peminjaman = Peminjaman::with($this->relations)
            ->whereIn('status_pengajuan', ['disetujui', 'dipinjam'])
            ->orderBy('start_time', 'desc')
            ->get();

        return DataTables::of($peminjaman)
            ->addIndexColumn()
            ->addColumn('mulai_pinjam', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->start_time->translatedFormat('d F Y').'</div>
                ';
            })
            ->addColumn('peminjam', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->peminjam->nama.'</div>
                <div class="text-xs text-slate-400">NRP: '.$peminjaman->peminjam->nrp.'</div>
            ';
            })
            ->addColumn('kegiatan', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->kegiatan.'</div>
                <div class="text-xs text-slate-400">'.($peminjaman->laboratorium ? $peminjaman->laboratorium->nama_lab : 'Hanya peminjaman alat').'</div>
                ';
            })
            ->addColumn('jumlah_peserta', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->jumlah_peserta.' Orang</div>
                ';
            })
            ->addColumn('tenggat_waktu', function ($peminjaman) {
                $html = '<div class="font-medium text-slate-900">
                            <i class="fa-regular fa-calendar mr-1"></i>'.$peminjaman->end_time->translatedFormat('d F Y').'
                        </div>';

                if ($peminjaman->end_time->isPast()) {
                    $now = Carbon::now();

                    $daysLate = (int) $peminjaman->end_time->diffInDays($now);
                    $hoursLate = (int) $peminjaman->end_time->diffInHours($now);

                    $lateText = '';

                    if ($daysLate > 0) {
                        $lateText = "Terlambat {$daysLate} hari";
                    } else {
                        $lateText = "Terlambat {$hoursLate} jam";
                    }

                    $html .= '<div class="text-xs text-red-500 mt-1">
                                '.$lateText.'
                            </div>';
                }

                return $html;
            })

            ->addColumn('aksi', function ($peminjaman) {
                $html = '<div class="flex items-center justify-center gap-3">';

                $html .= '<a href="/peminjaman/'.$peminjaman->id.'/showMonitoring" onclick="modalAction(this.href); return false;"
                            class="text-slate-500 hover:text-blue-600 bg-slate-100 p-2 rounded-md" title="Detail">
                            <i class="fa-solid fa-eye"></i>
                          </a>';

                if ($peminjaman->status_pengajuan == 'disetujui') {
                    $html .= '<a href="/peminjaman/'.$peminjaman->id.'/pengambilanMonitoring" onclick="modalAction(this.href); return false;"
                                class="text-slate-500 hover:text-amber-600 bg-slate-100 p-2 rounded-md" title="Proses Pengambilan">
                                <i class="fa-solid fa-hand-holding"></i> Ambil
                              </a>';
                } elseif ($peminjaman->status_pengajuan == 'dipinjam') {
                    $html .= '<a href="/peminjaman/'.$peminjaman->id.'/pengembalianMonitoring" onclick="modalAction(this.href); return false;"
                                class="text-slate-500 hover:text-green-600 bg-slate-100 p-2 rounded-md" title="Proses Pengembalian">
                                <i class="fa-solid fa-arrow-rotate-left"></i> Kembali
                              </a>';
                }

                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['mulai_pinjam', 'peminjam', 'tenggat_waktu', 'kegiatan', 'jumlah_peserta', 'aksi'])
            ->make(true);
    }

    public function showMonitoring($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $detailAlat = PeminjamanDetailAlat::where('peminjaman_id', $id)->with('alat.laboratorium')->get();

        return view('peminjaman.monitoring.show', compact('peminjaman', 'detailAlat'));
    }

    public function pengambilanMonitoring($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $detailAlat = PeminjamanDetailAlat::where('peminjaman_id', $id)->with('alat.laboratorium')->get();

        return view('peminjaman.monitoring.pengambilan', compact('peminjaman', 'detailAlat'));
    }

    public function prosesPengambilan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bukti_pengambilan' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'detail_id' => 'sometimes|array',
            'detail_id.*' => 'exists:peminjaman_detail_alat,id',
            'kondisi_saat_pinjam' => 'sometimes|array',
            'kondisi_saat_pinjam.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ]);
        }

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->start_time->isFuture()) {
            return response()->json([
                'status' => false,
                'message' => 'Peminjaman belum bisa diambil karena jadwal mulai peminjaman belum tiba.',
            ]);
        }

        try {
            DB::beginTransaction();


            $path_foto = null;

            if ($request->hasFile('bukti_pengambilan')) {
                $path_foto = $request->file('bukti_pengambilan')->store('uploads/bukti_pengambilan', 'public');
            }

            $peminjaman->update([
                'status_pengajuan' => 'dipinjam',
                'bukti_pengambilan' => $path_foto,
            ]);

            if ($request->has('detail_id') && $request->has('kondisi_saat_pinjam')) {
                $detailIds = $request->detail_id;
                $kondisiArray = $request->kondisi_saat_pinjam;

                foreach ($detailIds as $index => $detailId) {
                    PeminjamanDetailAlat::where('id', $detailId)
                        ->update([
                            'kondisi_saat_pinjam' => $kondisiArray[$index],
                        ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Fasilitas berhasil diambil. Status diubah menjadi Dipinjam.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses data.',
            ]);
        }
    }

    public function pengembalianMonitoring($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $detailAlat = PeminjamanDetailAlat::where('peminjaman_id', $id)->with('alat.laboratorium')->get();

        return view('peminjaman.monitoring.pengembalian', compact('peminjaman', 'detailAlat'));
    }

    public function prosesPengembalian(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kembali_realisasi' => 'required|date',
            'status_pengembalian' => 'required|string',
            'bukti_pengembalian' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'detail_id' => 'sometimes|array',
            'detail_id.*' => 'exists:peminjaman_detail_alat,id',
            'kondisi_saat_kembali' => 'sometimes|array',
            'kondisi_saat_kembali.*' => 'required|string',
            'denda_atau_sanksi' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ]);
        }

        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::findOrFail($id);
            $path_foto = null;

            if ($request->hasFile('bukti_pengembalian')) {
                $path_foto = $request->file('bukti_pengembalian')->store('uploads/bukti_pengembalian', 'public');
            }

            $peminjaman->update([
                'status_pengajuan' => 'selesai',
                'bukti_pengembalian' => $path_foto,
            ]);

            if ($request->has('detail_id') && $request->has('kondisi_saat_kembali')) {
                $detailIds = $request->detail_id;
                $kondisiArray = $request->kondisi_saat_kembali;

                foreach ($detailIds as $index => $detailId) {
                    PeminjamanDetailAlat::where('id', $detailId)
                        ->update([
                            'kondisi_saat_kembali' => $kondisiArray[$index],
                        ]);
                }
            }

            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'id_petugas' => Auth::user()->id,
                'tanggal_kembali_realisasi' => $request->tanggal_kembali_realisasi,
                'status_pengembalian' => $request->status_pengembalian,
                'denda_atau_sanksi' => $request->denda_atau_sanksi,
                'catatan' => $request->catatan,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Peminjaman selesai, fasilitas telah dikembalikan.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses data.',
            ]);
        }
    }

    public function indexRiwayat()
    {
        return view('peminjaman.riwayat.index');
    }

    public function listRiwayat()
    {
        $peminjaman = Peminjaman::with($this->relations)
            ->whereIn('status_pengajuan', ['selesai', 'ditolak', 'dibatalkan'])
            ->orderBy('start_time', 'desc')
            ->get();

        return DataTables::of($peminjaman)
            ->addIndexColumn()
            ->addColumn('tanggal_peminjaman', function ($peminjaman) {
                $html = '
                <div class="font-medium text-slate-900">'.$peminjaman->start_time->translatedFormat('d F Y').'</div>

                ';

                $daysDiff = (int) $peminjaman->start_time->diffInDays($peminjaman->end_time);
                if ($daysDiff > 0) {
                    $html .= '<div class="text-xs text-slate-400">'.$daysDiff.' Hari</div>';
                } else {
                    $html .= '<div class="text-xs text-slate-400">'.$peminjaman->start_time->translatedFormat('H:i').' - '.$peminjaman->end_time->translatedFormat('H:i').'</div>';
                }

                return $html;
            })
            ->addColumn('tanggal_kembali', function ($peminjaman) {
                if ($peminjaman->status_pengajuan != 'selesai') {
                    return '<span class="text-xs text-slate-400">Peminjaman tidak selesai</span>';
                }

                return '
                <div class="font-medium text-slate-900">'.$peminjaman->pengembalian->tanggal_kembali_realisasi->translatedFormat('d F Y').'</div>
                <div class="text-xs text-slate-400">'.$peminjaman->pengembalian->tanggal_kembali_realisasi->translatedFormat('H:i').'</div>
                ';
            })
            ->addColumn('peminjam', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->peminjam->nama.'</div>
                <div class="text-xs text-slate-400">NRP: '.$peminjaman->peminjam->nrp.'</div>
            ';
            })
            ->addColumn('kegiatan', function ($peminjaman) {
                return '
                <div class="font-medium text-slate-900">'.$peminjaman->kegiatan.'</div>
                <div class="text-xs text-slate-400">'.($peminjaman->laboratorium ? $peminjaman->laboratorium->nama_lab : 'Hanya peminjaman alat').'</div>
                ';
            })
            ->addColumn('status', function ($peminjaman) {

                $baseClass = 'text-xs font-medium px-2.5 py-0.5 rounded border ';

                $statusMap = [
                    'selesai' => 'bg-green-100 text-green-800 border-green-200',
                    'dibatalkan' => 'bg-red-100 text-red-800 border-red-200',
                    'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                ];

                $status = $peminjaman->status_pengajuan;

                $class = $statusMap[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';

                return '<span class="'.$baseClass.$class.'">'
                        .ucfirst($status).
                       '</span>';
            })
            ->addColumn('aksi', function ($peminjaman) {
                return '
            <div class="flex items-center justify-center gap-3">
                <a href="/peminjaman/'.$peminjaman->id.'/showRiwayat" onclick="modalAction(this.href); return false;"
                    class="text-slate-500 hover:text-blue-600 bg-slate-100 p-2 rounded-md">
                    <i class="fa-solid fa-eye"></i> Detail
                </a>
            </div>
            ';
            })
            ->rawColumns(['tanggal_peminjaman', 'tanggal_kembali', 'peminjam', 'kegiatan', 'status', 'aksi'])
            ->make(true);
    }

    public function showRiwayat($id)
    {
        $peminjaman = Peminjaman::with('pengembalian')->findOrFail($id);
        $detailAlat = PeminjamanDetailAlat::where('peminjaman_id', $id)->with('alat.laboratorium')->get();

        return view('peminjaman.riwayat.show', compact('peminjaman', 'detailAlat'));
    }

    public function checkAvailableStock(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $startTime = $request->start_time;
        $endTime = $request->end_time;

        $alats = Alat::all();
        $alatIds = $alats->pluck('id')->toArray();

        $borrowedTools = PeminjamanDetailAlat::selectRaw('id_alat, SUM(jumlah) as total_dipinjam')
            ->whereHas('peminjaman', function ($q) use ($startTime, $endTime) {
                $q->activeCollision($startTime, $endTime);
            })
            ->whereIn('id_alat', $alatIds)
            ->groupBy('id_alat')
            ->get()
            ->keyBy('id_alat');

        $availableStock = [];

        foreach ($alats as $alat) {
            $borrowedQty = $borrowedTools->has($alat->id) ? (int) $borrowedTools->get($alat->id)->total_dipinjam : 0;
            $availableStock[$alat->id] = max(0, $alat->jumlah - $borrowedQty);
        }

        return response()->json([
            'status' => true,
            'data' => $availableStock,
        ]);
    }
}
