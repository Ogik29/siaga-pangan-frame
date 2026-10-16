<?php
// Ini mengikuti konsep paper bahwa detail shipment disimpan pada database, sementara hash SHA-256 menjadi 
// representasi yang dicatat pada blockchain; paper menempatkan prototype pada Polygon Amoy.

namespace App\Services;

use App\Models\Shipment;
use App\Models\LedgerTransaction;

class BlockchainService
{
    public function recordShipment(
        Shipment $shipment
    ): LedgerTransaction {
        $shipment->load([
            'commodity',
            'originWarehouse',
            'destinationRegion',
        ]);

        $payload = [
            'batch_id' =>
            $shipment->batch_id,

            'commodity' =>
            $shipment->commodity->name,

            'quantity_tons' =>
            $shipment->quantity_tons,

            'origin' =>
            $shipment->originWarehouse->name,

            'destination' =>
            $shipment->destinationRegion->name,

            'timestamp' =>
            $shipment->dispatched_at
                ? $shipment->dispatched_at
                ->toIso8601String()
                : null,
        ];

        $hash = hash(
            'sha256',
            json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
            )
        );

        return LedgerTransaction::updateOrCreate(
            [
                'shipment_id' =>
                $shipment->id,
            ],
            [
                'record_hash' =>
                $hash,

                'blockchain_tx_hash' =>
                null,

                'network' =>
                'polygon-amoy',

                'status' =>
                'recorded',

                'recorded_at' =>
                $shipment->dispatched_at
                    ?? now(),

                'metadata' =>
                $payload,
            ]
        );
    }
}
