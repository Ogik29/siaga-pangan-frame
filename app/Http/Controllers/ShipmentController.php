<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\BlockchainService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function __construct(
        private BlockchainService $blockchainService
    ) {}

    public function show(
        Shipment $shipment
    ): View {
        $shipment->load([
            'commodity',
            'originWarehouse',
            'destinationRegion',
            'sender',
            'receiver',
            'ledgerTransaction',
        ]);

        return view(
            'shipments.show',
            compact('shipment')
        );
    }

    public function confirmSender(
        Request $request,
        Shipment $shipment
    ): RedirectResponse {
        $shipment->update([
            'sender_user_id' => Auth::id(),

            'sender_confirmed_at' => now(),

            'dispatched_at' => $shipment->dispatched_at
                ?? now(),

            'status' => 'In Transit',
        ]);

        $this->syncLedger(
            $shipment->fresh()
        );

        return back()
            ->with(
                'success',
                'Shipment dispatch confirmed.'
            );
    }

    public function confirmReceiver(
        Request $request,
        Shipment $shipment
    ): RedirectResponse {
        $shipment->update([
            'receiver_user_id' => Auth::id(),

            'receiver_confirmed_at' => now(),

            'arrived_at' => now(),

            'status' => 'Received',
        ]);

        $this->syncLedger(
            $shipment->fresh()
        );

        return back()
            ->with(
                'success',
                'Shipment receipt confirmed.'
            );
    }

    private function syncLedger(
        Shipment $shipment
    ): void {
        if (
            $shipment->sender_confirmed_at
            &&
            $shipment->receiver_confirmed_at
        ) {
            $shipment->update([
                'status' => 'Received',
            ]);
        }

        $this->blockchainService
            ->recordShipment($shipment);
    }
}
