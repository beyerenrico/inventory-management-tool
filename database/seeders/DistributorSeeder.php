<?php

namespace Database\Seeders;

use App\Models\Distributor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distributors = [
            [
                'name' => 'Office Supplies Direct',
                'email' => 'orders@officesupplies.com',
                'website' => 'https://officesuppliesdirect.com',
                'phone' => '+15550123',
                'address' => '123 Industrial Blvd, New York, NY 10001',
                'company' => 'Office Supplies Direct Inc.',
            ],
            [
                'name' => 'Tech Equipment Corp',
                'email' => 'wholesale@techequipment.com',
                'website' => 'https://techequipmentcorp.com',
                'phone' => '+15550456',
                'address' => '456 Business Ave, Suite 100, Los Angeles, CA 90210',
                'company' => 'Tech Equipment Corp',
            ],
            [
                'name' => 'Industrial Supply Co',
                'email' => 'sales@industrialsupply.com',
                'website' => 'https://industrialsupply.co.uk',
                'phone' => '+442071234567',
                'address' => '789 Manufacturing Way, London, UK',
                'company' => 'Industrial Supply Co',
            ],
            [
                'name' => 'Wholesale Distributors LLC',
                'email' => 'orders@wholesaledist.com',
                'website' => 'https://wholesaledistributors.de',
                'phone' => '+493012345678',
                'address' => '321 Distribution Center Dr, Berlin, Germany',
                'company' => 'Wholesale Distributors LLC',
            ],
        ];

        foreach ($distributors as $distributor) {
            Distributor::create($distributor);
        }
    }
}
