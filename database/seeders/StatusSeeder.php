<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusesToSeed = [
            // Property Statuses
            ['entity_type' => 'property', 'status_name' => 'Available', 'description' => 'Property is available for sale or rent.'],
            ['entity_type' => 'property', 'status_name' => 'Sold', 'description' => 'Property has been sold.'],
            ['entity_type' => 'property', 'status_name' => 'Rented', 'description' => 'Property has been rented.'],
            ['entity_type' => 'property', 'status_name' => 'Pending Approval', 'description' => 'Property listing is pending admin approval.'],
            ['entity_type' => 'property', 'status_name' => 'Unavailable', 'description' => 'Property is currently unavailable.'],

            // Property Request Statuses
            ['entity_type' => 'property_request', 'status_name' => 'New', 'description' => 'New property request submitted.'],
            ['entity_type' => 'property_request', 'status_name' => 'Pending', 'description' => 'Property request is pending action.'],
            ['entity_type' => 'property_request', 'status_name' => 'Approved', 'description' => 'Property request has been approved.'],
            ['entity_type' => 'property_request', 'status_name' => 'Rejected', 'description' => 'Property request has been rejected.'],
            ['entity_type' => 'property_request', 'status_name' => 'In Progress', 'description' => 'Property request is being processed.'],
            ['entity_type' => 'property_request', 'status_name' => 'Completed', 'description' => 'Property request has been completed.'],
            ['entity_type' => 'property_request', 'status_name' => 'Cancelled', 'description' => 'Property request has been cancelled.'],

            // Finishing Request Statuses
            ['entity_type' => 'finishing_request', 'status_name' => 'New', 'description' => 'New finishing request submitted.'],
            ['entity_type' => 'finishing_request', 'status_name' => 'Pending Quote', 'description' => 'Awaiting quotation for the finishing request.'],
            ['entity_type' => 'finishing_request', 'status_name' => 'Quote Submitted', 'description' => 'Quotation has been submitted to the client.'],
            ['entity_type' => 'finishing_request', 'status_name' => 'Approved', 'description' => 'Finishing request and quote approved.'],
            ['entity_type' => 'finishing_request', 'status_name' => 'In Progress', 'description' => 'Finishing work is in progress.'],
            ['entity_type' => 'finishing_request', 'status_name' => 'Completed', 'description' => 'Finishing work has been completed.'],
            ['entity_type' => 'finishing_request', 'status_name' => 'Cancelled', 'description' => 'Finishing request has been cancelled.'],

            // Service Request Statuses
            ['entity_type' => 'service_request', 'status_name' => 'Open', 'description' => 'New service request, not yet assigned.'],
            ['entity_type' => 'service_request', 'status_name' => 'Pending Assignment', 'description' => 'Service request is pending assignment to a provider.'],
            ['entity_type' => 'service_request', 'status_name' => 'Assigned', 'description' => 'Service request has been assigned to a service provider.'],
            ['entity_type' => 'service_request', 'status_name' => 'In Progress', 'description' => 'Service request is currently being worked on.'],
            ['entity_type' => 'service_request', 'status_name' => 'On Hold', 'description' => 'Service request is temporarily on hold.'],
            ['entity_type' => 'service_request', 'status_name' => 'Resolved', 'description' => 'Service request has been resolved by the provider.'],
            ['entity_type' => 'service_request', 'status_name' => 'Completed', 'description' => 'Service request has been completed and confirmed.'],
            ['entity_type' => 'service_request', 'status_name' => 'Closed', 'description' => 'Service request is closed.'],
            ['entity_type' => 'service_request', 'status_name' => 'Cancelled', 'description' => 'Service request has been cancelled.'],
        ];

        foreach ($statusesToSeed as $statusData) {
            Status::updateOrCreate(
                [
                    'entity_type' => $statusData['entity_type'],
                    'status_name' => $statusData['status_name']
                ],
                $statusData // This will set/update all fields including 'description'
            );
        }
    }
}
