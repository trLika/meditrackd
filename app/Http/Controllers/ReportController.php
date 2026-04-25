<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Service;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        // 1. Répartition par Service
        $patientsByService = Service::withCount('patients')->get();

        // 2. Répartition par Sexe
        $patientsByGender = Patient::select('sexe', DB::raw('count(*) as total'))
            ->groupBy('sexe')
            ->get();

        // 3. Répartition par Groupe Sanguin
        $patientsByBlood = Patient::select('groupe_sanguin', DB::raw('count(*) as total'))
            ->groupBy('groupe_sanguin')
            ->get();

        // 4. Évolution des consultations (6 derniers mois)
        $monthlyConsultations = Consultation::select(
            DB::raw('DATE_FORMAT(date_consultation, "%Y-%m") as month'),
            DB::raw('count(*) as total')
        )
            ->where('date_consultation', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('admin.reports.index', compact(
            'patientsByService',
            'patientsByGender',
            'patientsByBlood',
            'monthlyConsultations'
        ));
    }

    public function exportPDF()
    {
        $data = [
            'title' => 'Rapport Statistique SGIDM',
            'date' => now()->format('d/m/Y'),
            'totalPatients' => Patient::count(),
            'totalConsultations' => Consultation::count(),
            'patientsByService' => Service::withCount('patients')->get(),
            'patientsByGender' => Patient::select('sexe', DB::raw('count(*) as total'))->groupBy('sexe')->get(),
            'patientsByBlood' => Patient::select('groupe_sanguin', DB::raw('count(*) as total'))->groupBy('groupe_sanguin')->get(),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        return $pdf->download('rapport_statistique_' . now()->format('Ymd') . '.pdf');
    }
}
