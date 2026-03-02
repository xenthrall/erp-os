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
        ]);

        try {
            // 3. Uso de transacciones para integridad
            return DB::transaction(function () use ($validated, $user) {

                $warranty = WarrantyRequest::create([
                    ...$validated,
                    'user_id' => $user->id,
                    'customer_id' => $user->userable->id,
                    'status' => WarrantyRequestStatus::Pending,
                ]);

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
        ]);

        $warranty->update($validated);

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

            // Estadísticas
            $stats = [
                'inProcess' => WarrantyRequest::where('customer_id', $customerId)
                    ->whereIn('status', [
                        WarrantyRequestStatus::Pending,
                    ])->count(),

                'approved' => WarrantyRequest::where('customer_id', $customerId)
                    ->where('status', WarrantyRequestStatus::Approved)
                    ->count(),

                'total' => WarrantyRequest::where('customer_id', $customerId)
                    ->count(),
            ];

            // Últimos 5 casos
            $recentWarranties = WarrantyRequest::where('customer_id', $customerId)
                ->latest()
                ->take(5)
                ->get([
                    'id',
                    'model',
                    'invoice_number',
                    'damage_date',
                    'status',
                ]);

            return Inertia::render('Customer/Dashboard', [
                'stats' => $stats,
                'recentWarranties' => $recentWarranties,
            ]);
        }

        // retornar datos falsos
        return Inertia::render('Customer/Dashboard', [
            'stats' => [
                'inProcess' => 2,
                'approved' => 1,
                'total' => 3,
            ],
            'recentWarranties' => [
                [
                    'id' => 1,
                    'model' => 'Modelo 1',
                    'invoice_number' => 'Factura 1',
                    'damage_date' => '2022-01-01',
                    'status' => 'Pendiente',
                ],
                [
                    'id' => 2,
                    'model' => 'Modelo 2',
                    'invoice_number' => 'Factura 2',
                    'damage_date' => '2022-01-01',
                    'status' => 'Aprobada',
                ],
                [
                    'id' => 3,
                    'model' => 'Modelo 3',
                    'invoice_number' => 'Factura 3',
                    'damage_date' => '2022-01-01',
                    'status' => 'Rechazada',
                ],
            ],
        ]);
    }
}
