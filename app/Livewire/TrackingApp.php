<?php
// app/Livewire/TrackingApp.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Tracking;
use App\Models\User;
use App\Exports\TrackingsExport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TrackingApp extends Component
{
    // Daftar user untuk dropdown login
    public Collection $allUsers;
    
    // Data untuk 'Live Update'
    public Collection $liveRecords;

    // Data untuk 'Dashboard'
    public Collection $userRecords;

    // Properti untuk form login
    public $login_user_id = '';
    public $login_pin = '';
    public $loginError = '';

    // Properti untuk Modal
    public $showModal = false;
    public $modalAction = ''; // 'create' atau 'update'
    public $editingRecord;
    
    // Properti untuk form 'Tambah'/'Update'
    public $vehicle_name, $plate_number, $description, $start_time;

    // Daftar stages (seperti di JS Anda)
    public $stages = [
        'security' => ['label' => 'Security', 'next' => 'loading'],
        'loading' => ['label' => 'Bongkar Muat', 'next' => 'ttb'],
        'ttb' => ['label' => 'Officer TTB', 'next' => 'completed'],
    ];

    /**
     * 'mount()' berjalan sekali saat komponen dimuat.
     */
    public function mount()
    {
        // Ambil semua user untuk dropdown login
        $this->allUsers = User::orderBy('name')->get();
        
        // Muat data (baik untuk dashboard atau live update)
        $this->loadData();
    }

    /**
     * Memuat data yang relevan dari database.
     */
    public function loadData()
    {
        if (Auth::check()) {
            // Jika SUDAH login
            $userRole = Auth::user()->role;
            if ($userRole == 'admin') {
                $this->userRecords = Tracking::latest()->get();
            } else {
                $this->userRecords = Tracking::where('current_stage', $userRole)
                                            ->orWhere('current_stage', 'completed')
                                            ->latest()
                                            ->get();
            }
        } else {
            // Jika BELUM login (untuk Live Update)
            $this->liveRecords = Tracking::latest()->take(5)->get();
        }
    }

    /**
     * Fungsi Login.
     */
    public function login()
    {
        // Validasi input
        $credentials = [
            'id' => $this->login_user_id,
            'password' => $this->login_pin,
        ];

        // Coba autentikasi
        if (Auth::attempt($credentials)) {
            session()->regenerate();
            $this->loginError = '';
            $this->login_pin = '';
            $this->loadData(); // Muat data dashboard
        } else {
            // Gagal login
            $this->loginError = 'PIN salah! Silakan coba lagi.';
            $this->login_pin = '';
        }
    }

    /**
     * Fungsi Logout.
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/'); // Muat data live update
    }

    // --- FUNGSI MODAL ---

    public function openNewEntryModal()
    {
        $this->resetForm();
        $this->modalAction = 'create';
        $this->start_time = now()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    public function openUpdateModal($recordId)
    {
        $this->resetForm();
        $this->editingRecord = Tracking::find($recordId);
        $this->modalAction = 'update';
        $this->start_time = now()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->vehicle_name = '';
        $this->plate_number = '';
        $this->description = '';
        $this->start_time = '';
        $this->editingRecord = null;
    }

    // --- FUNGSI SIMPAN & UPDATE DATA ---

    public function handleSubmit()
    {
        if ($this->modalAction === 'create') {
            $this->createNewRecord();
        } else {
            $this->updateRecord();
        }
    }

    public function createNewRecord()
    {
        if (Auth::user()->role != 'security') return; // Keamanan

        $this->validate([
            'vehicle_name' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
        ]);

        Tracking::create([
            'vehicle_name' => $this->vehicle_name,
            'plate_number' => $this->plate_number,
            'description' => $this->description,
            'security_start' => $this->start_time,
            'current_stage' => 'security',
        ]);

        $this->closeModal();
        $this->loadData();
    }

    public function updateRecord()
    {
        $record = $this->editingRecord;
        if (Auth::user()->role != $record->current_stage) return; // Keamanan

        $currentStage = $record->current_stage;
        $startField = $currentStage . '_start';
        $endField = $currentStage . '_end';

        if (is_null($record->$startField)) {
            $record->$startField = $this->start_time;
        } else {
            $record->$endField = $this->start_time;
            $record->current_stage = $this->stages[$currentStage]['next'] ?? 'completed';
        }

        $record->save();
        $this->closeModal();
        $this->loadData();
    }

    // --- FUNGSI EXPORT ---

    public function exportExcel()
    {
        if (Auth::user()->role != 'admin') return;
        return Excel::download(new TrackingsExport(), 'Laporan_Bongkar_Muat_'.now()->format('Ymd').'.xlsx');
    }

    // Modifikasi TrackingsExport
    // Buka app/Exports/TrackingsExport.php dan sesuaikan
    // (Tambahkan ini secara manual ke app/Exports/TrackingsExport.php)
    /*
    <?php
    namespace App\Exports;

    use App\Models\Tracking;
    use Maatwebsite\Excel\Concerns\FromCollection;
    use Maatwebsite\Excel\Concerns\WithHeadings;

    class TrackingsExport implements FromCollection, WithHeadings
    {
        public function collection()
        {
            return Tracking::all()->map(function ($record) {
                return [
                    'vehicle_name' => $record->vehicle_name,
                    'plate_number' => $record->plate_number,
                    'description' => $record->description,
                    'security_start' => $record->security_start ? $record->security_start->format('d/m/Y H:i') : '-',
                    'security_end' => $record->security_end ? $record->security_end->format('d/m/Y H:i') : '-',
                    'loading_start' => $record->loading_start ? $record->loading_start->format('d/m/Y H:i') : '-',
                    'loading_end' => $record->loading_end ? $record->loading_end->format('d/m/Y H:i') : '-',
                    'ttb_start' => $record->ttb_start ? $record->ttb_start->format('d/m/Y H:i') : '-',
                    'ttb_end' => $record->ttb_end ? $record->ttb_end->format('d/m/Y H:i') : '-',
                    'current_stage' => $record->current_stage,
                    'created_at' => $record->created_at->format('d/m/Y H:i'),
                ];
            });
        }

        public function headings(): array
        {
            return [
                'Nama Kendaraan', 'Plat Nomor', 'Keterangan',
                'Security Mulai', 'Security Selesai',
                'Bongkar Muat Mulai', 'Bongkar Muat Selesai',
                'TTB Mulai', 'TTB Selesai',
                'Status Terakhir', 'Dibuat Tanggal'
            ];
        }
    }
    */


    /**
     * 'render()' adalah fungsi yang menampilkan view.
     */
    public function render()
    {
        // Jika belum login, refresh data live update
        if (!Auth::check()) {
            $this->loadData();
        }
        
        // Memuat view blade dan layoutnya
        return view('livewire.tracking-app')->layout('layouts.app');
    }
}