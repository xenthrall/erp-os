<?php

namespace App\Http\Customer; // Corregí el namespace a Controllers

use App\Enums\Warranties\WarrantyRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\Warranties\Customer;
use App\Models\Warranties\WarrantyRequest; // Importante para el estado inicial
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WarrantyController extends Controller
{
    public function index(Request $request)
    {

        if (! (Auth::user()->userable instanceof Customer)) {
            return redirect()->route('customer.dashboard')->with('error', 'Acceso denegado.');
        }
        $user = $request->user();

        $warranties = WarrantyRequest::where('customer_id', $user->userable->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Customer/Warranties/Index', [
            'warranties' => $warranties,
        ]);
    }

    public function create()
    {
        if (! (Auth::user()->userable instanceof Customer)) {
            return redirect()->route('customer.dashboard')->with('error', 'Acceso denegado.');
        }

        return Inertia::render('Customer/Warranties/Create');
    }

    public function store(Request $request)
    {
        // 1. Verificar identidad inmediatamente
        $user = $request->user();
        if (! $user->userable instanceof Customer) {
            return back()->with('error', 'Tu cuenta no está vinculada a un perfil de cliente válido.');
        }

        // 2. Validación
        $validated = $request->validate([
            'shipping_city' => 'required|string|max:100',
            'shipping_address' => 'required|string|max:255',
            'damage_date' => [
                'required',
                'date',
                'before_or_equal:'.now()->format('d-m-Y'),
            ],
            'purchase_date' => [
                'required',
                'date',
                'before_or_equal:'.now()->format('d-m-Y'),
            ],
            'invoice_number' => 'required|string|max:50',
            'internal_code' => 'nullable|string|max:50',
            'model' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1|max:1000',
            'failure_description' => 'required|string|min:10',
            'attachments' => 'nullable|array|max:5', // Máximo 5 archivos
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10240', // Max 10MB por archivo
        ]);

        try {
            // 3. Uso de transacciones para integridad
            return DB::transaction(function () use ($validated, $user, $request) {

                $warranty = WarrantyRequest::create([
                    ...collect($validated)->except('attachments')->toArray(),
                    'user_id' => $user->id,
                    'customer_id' => $user->userable->id,
                    'status' => WarrantyRequestStatus::Pending,
                ]);

                // Procesar adjuntos si existen
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store('warranties/attachments', 'public');
                        $warranty->attachments()->create([
                            'path' => $path,
                        ]);
                    }
                }

                return redirect()->route('customer.warranties.index')
                    ->with('success', "Garantía #{$warranty->id} radicada correctamente.");
            });

        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error inesperado al procesar la solicitud.');
        }
    }

    public function edit(Request $request, WarrantyRequest $warranty)
    {
        $user = $request->user();

        if (! $user->userable instanceof Customer) {
            return redirect()->route('customer.dashboard')->with('error', 'Acceso denegado.');
        }

        if ($warranty->customer_id !== $user->userable->id) {
            abort(403, 'No tienes permiso para editar esta garantía.');
        }

        if ($warranty->status !== WarrantyRequestStatus::Pending) {
            return redirect()->route('customer.warranties.show', $warranty)
                ->with('error', 'Solo puedes editar garantías en estado Pendiente.');
        }

        return Inertia::render('Customer/Warranties/Edit', [
            'warranty' => $warranty,
        ]);
    }

    public function update(Request $request, WarrantyRequest $warranty)
    {
        $user = $request->user();

        if (! $user->userable instanceof Customer) {
            return back()->with('error', 'Acceso denegado.');
        }

        if ($warranty->customer_id !== $user->userable->id) {
            abort(403, 'No tienes permiso para editar esta garantía.');
        }

        if ($warranty->status !== WarrantyRequestStatus::Pending) {
            return back()->with('error', 'Solo puedes editar garantías en estado Pendiente.');
        }

        $validated = $request->validate([
            'shipping_city'       => 'required|string|max:100',
            'shipping_address'    => 'required|string|max:255',
            'damage_date'         => ['required', 'date', 'before_or_equal:' . now()->format('d-m-Y')],
            'purchase_date'       => ['required', 'date', 'before_or_equal:' . now()->format('d-m-Y')],
            'invoice_number'      => 'required|string|max:50',
            'internal_code'       => 'nullable|string|max:50',
            'model'               => 'required|string|max:100',
            'quantity'            => 'required|integer|min:1|max:1000',
            'failure_description' => 'required|string|min:10',
            'attachments'         => 'nullable|array|max:5',
            'attachments.*'       => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $warranty) {
                $warranty->update(collect($validated)->except('attachments')->toArray());

                // Procesar nuevos adjuntos si se suben durante la edición
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store('warranties/attachments', 'public');
                        $warranty->attachments()->create([
                            'path' => $path,
                        ]);
                    }
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error inesperado al actualizar la solicitud.');
        }

        return redirect()->route('customer.warranties.show', $warranty)
            ->with('success', "Garantía #{$warranty->id} actualizada correctamente.");
    }

    public function show(Request $request, WarrantyRequest $warranty)
    {
        $user = $request->user();

        if (! $user->userable instanceof Customer) {
            return redirect()->route('customer.dashboard')->with('error', 'Acceso denegado.');
        }

        // El cliente solo puede ver sus propias garantías
        if ($warranty->customer_id !== $user->userable->id) {
            abort(403, 'No tienes permiso para ver esta garantía.');
        }

        $warranty->load(['notes.user', 'attachments']);

        return Inertia::render('Customer/Warranties/Show', [
            'warranty' => $warranty,
        ]);
    }

    public function destroy(Request $request, WarrantyRequest $warranty)
    {
        $user = $request->user();

        if (! $user->userable instanceof Customer) {
            return back()->with('error', 'Acceso denegado.');
        }

        // Verificar que la garantía pertenezca al cliente autenticado
        if ($warranty->customer_id !== $user->userable->id) {
            abort(403, 'No tienes permiso para eliminar esta garantía.');
        }

        // Solo se pueden eliminar garantías pendientes
        if ($warranty->status !== WarrantyRequestStatus::Pending) {
            return back()->with('error', 'Solo puedes eliminar garantías en estado Pendiente.');
        }

        $warrantyId = $warranty->id;
        $warranty->delete();

        return redirect()->route('customer.warranties.index')
            ->with('success', "Garantía #{$warrantyId} eliminada correctamente.");
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($user->userable instanceof Customer) {
            $customerId = $user->userable->id;

            // Optimización: Una sola consulta agrupada para las métricas
            $counts = WarrantyRequest::where('customer_id', $customerId)
                ->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $stats = [
                'pending'   => $counts[WarrantyRequestStatus::Pending->value] ?? 0,
                'inReview'  => $counts[WarrantyRequestStatus::InReview->value] ?? 0,
                'approved'  => $counts[WarrantyRequestStatus::Approved->value] ?? 0,
                'rejected'  => $counts[WarrantyRequestStatus::Rejected->value] ?? 0,
                'total'     => array_sum($counts),
            ];

            // Últimos 5 casos con fecha legible para la vista
            $recentWarranties = WarrantyRequest::where('customer_id', $customerId)
                ->latest()
                ->take(5)
                ->get();

            return Inertia::render('Customer/Dashboard', [
                'stats' => $stats,
                // Mapeamos para enviar dates formateadas via carbon (diffForHumans u otras opciones para JS)
                'recentWarranties' => $recentWarranties,
            ]);
        }

        return redirect()->route('home')->with('error', 'Acceso denegado a esta área.');
    }
}
