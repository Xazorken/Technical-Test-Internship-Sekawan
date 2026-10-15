<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\Driver;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = ['admin', 'approver_l1', 'approver_l2'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Create Locations
        $locations = [
            ['name' => 'Kantor Pusat Jakarta',    'type' => 'pusat',  'city' => 'Jakarta',    'region' => 'DKI Jakarta'],
            ['name' => 'Kantor Cabang Surabaya',  'type' => 'cabang', 'city' => 'Surabaya',   'region' => 'Jawa Timur'],
            ['name' => 'Tambang Nikel Sulawesi 1','type' => 'tambang','city' => 'Kolaka',     'region' => 'Sulawesi Tenggara'],
            ['name' => 'Tambang Nikel Sulawesi 2','type' => 'tambang','city' => 'Morowali',   'region' => 'Sulawesi Tengah'],
            ['name' => 'Tambang Nikel Halmahera', 'type' => 'tambang','city' => 'Halmahera',  'region' => 'Maluku Utara'],
            ['name' => 'Tambang Nikel Papua',     'type' => 'tambang','city' => 'Sorong',     'region' => 'Papua Barat'],
        ];

        foreach ($locations as $loc) {
            Location::firstOrCreate(['name' => $loc['name']], [...$loc, 'is_active' => true]);
        }

        $loc1 = Location::where('type', 'pusat')->first();
        $loc3 = Location::where('name', 'Tambang Nikel Sulawesi 1')->first();

        // Create Admin
        $admin = User::firstOrCreate(['email' => 'admin@kendaraan.com'], [
            'name'        => 'Administrator',
            'password'    => Hash::make('admin123'),
            'phone'       => '08111111111',
            'department'  => 'IT',
            'position'    => 'System Administrator',
            'location_id' => $loc1->id,
            'is_active'   => true,
        ]);
        $admin->syncRoles(['admin']);

        // Create Approver Level 1
        $approverL1 = User::firstOrCreate(['email' => 'approver1@kendaraan.com'], [
            'name'        => 'Budi Santoso',
            'password'    => Hash::make('approver123'),
            'phone'       => '08122222222',
            'department'  => 'Operasional',
            'position'    => 'Kepala Bagian Pool',
            'location_id' => $loc1->id,
            'is_active'   => true,
        ]);
        $approverL1->syncRoles(['approver_l1']);

        // Create Approver Level 2
        $approverL2 = User::firstOrCreate(['email' => 'approver2@kendaraan.com'], [
            'name'        => 'Siti Rahayu',
            'password'    => Hash::make('approver123'),
            'phone'       => '08133333333',
            'department'  => 'Operasional',
            'position'    => 'Manager Operasional',
            'location_id' => $loc1->id,
            'is_active'   => true,
        ]);
        $approverL2->syncRoles(['approver_l2']);

        // Create Vehicles
        $vehicles = [
            ['name' => 'Toyota Innova Reborn', 'brand' => 'Toyota', 'model' => 'Innova Reborn', 'year' => 2022, 'plate_number' => 'B 1234 ABC', 'type' => 'angkutan_orang', 'ownership' => 'milik', 'status' => 'available', 'location_id' => $loc1->id, 'fuel_consumption' => 12],
            ['name' => 'Toyota Hilux',         'brand' => 'Toyota', 'model' => 'Hilux',         'year' => 2021, 'plate_number' => 'DT 5678 XYZ','type' => 'angkutan_orang', 'ownership' => 'milik', 'status' => 'available', 'location_id' => $loc3->id,'fuel_consumption' => 10],
            ['name' => 'Mitsubishi Fuso',      'brand' => 'Mitsubishi','model'=>'Fuso',          'year' => 2020, 'plate_number' => 'DT 9999 FUS','type' => 'angkutan_barang','ownership' => 'milik', 'status' => 'available', 'location_id' => $loc3->id,'fuel_consumption' => 8],
            ['name' => 'Isuzu ELF Sewa',       'brand' => 'Isuzu',  'model' => 'ELF',           'year' => 2023, 'plate_number' => 'B 7777 SEW', 'type' => 'angkutan_orang', 'ownership' => 'sewa', 'rental_company' => 'CV Sewa Kendaraan Jaya', 'status' => 'available', 'location_id' => $loc1->id, 'fuel_consumption' => 11],
            ['name' => 'Toyota Land Cruiser',  'brand' => 'Toyota', 'model' => 'Land Cruiser',  'year' => 2022, 'plate_number' => 'PA 1111 LC', 'type' => 'angkutan_orang', 'ownership' => 'milik', 'status' => 'available', 'location_id' => null, 'fuel_consumption' => 9],
            ['name' => 'Hino Dump Truck',      'brand' => 'Hino',   'model' => 'Dump Truck',    'year' => 2019, 'plate_number' => 'DT 3333 HIN','type' => 'angkutan_barang','ownership' => 'milik', 'status' => 'maintenance','location_id' => $loc3->id,'fuel_consumption' => 6],
        ];
        foreach ($vehicles as $v) {
            Vehicle::firstOrCreate(['plate_number' => $v['plate_number']], $v);
        }

        // Create Drivers
        $drivers = [
            ['name' => 'Ahmad Fauzi',   'license_number' => 'SIM-B1-001', 'license_type' => 'B1', 'license_expiry_date' => '2026-12-31', 'phone' => '08144444444', 'location_id' => $loc1->id, 'status' => 'available'],
            ['name' => 'Rudi Hermawan', 'license_number' => 'SIM-B1-002', 'license_type' => 'B1', 'license_expiry_date' => '2025-06-30', 'phone' => '08155555555', 'location_id' => $loc3->id, 'status' => 'available'],
            ['name' => 'Joko Prasetyo', 'license_number' => 'SIM-B2-003', 'license_type' => 'B2', 'license_expiry_date' => '2027-03-15', 'phone' => '08166666666', 'location_id' => $loc3->id, 'status' => 'available'],
            ['name' => 'Bambang Sutrisno','license_number'=> 'SIM-B1-004','license_type' => 'B1', 'license_expiry_date' => '2026-09-20', 'phone' => '08177777777', 'location_id' => $loc1->id, 'status' => 'available'],
        ];
        foreach ($drivers as $d) {
            Driver::firstOrCreate(['license_number' => $d['license_number']], [...$d, 'is_active' => true]);
        }

        // Create sample bookings
        $vehicle1 = Vehicle::where('plate_number', 'B 1234 ABC')->first();
        $driver1  = Driver::where('license_number', 'SIM-B1-001')->first();

        $booking = Booking::firstOrCreate(['booking_number' => 'BK20241001001'], [
            'user_id'          => $admin->id,
            'vehicle_id'       => $vehicle1->id,
            'driver_id'        => $driver1->id,
            'from_location_id' => $loc1->id,
            'destination'      => 'Tambang Nikel Sulawesi 1',
            'purpose'          => 'Kunjungan inspeksi operasional tambang',
            'start_datetime'   => now()->addDays(2),
            'end_datetime'     => now()->addDays(4),
            'passenger_count'  => 4,
            'status'           => 'pending',
        ]);

        BookingApproval::firstOrCreate(
            ['booking_id' => $booking->id, 'level' => 1],
            ['approver_id' => $approverL1->id, 'status' => 'pending']
        );
        BookingApproval::firstOrCreate(
            ['booking_id' => $booking->id, 'level' => 2],
            ['approver_id' => $approverL2->id, 'status' => 'pending']
        );

        // One completed booking
        $vehicle2 = Vehicle::where('plate_number', 'Isuzu ELF Sewa')->first() ?? $vehicle1;
        $driver2  = Driver::where('license_number', 'SIM-B1-002')->first();

        $booking2 = Booking::firstOrCreate(['booking_number' => 'BK20241001000'], [
            'user_id'          => $admin->id,
            'vehicle_id'       => $vehicle1->id,
            'driver_id'        => $driver2->id,
            'from_location_id' => $loc1->id,
            'destination'      => 'Kantor Cabang Surabaya',
            'purpose'          => 'Pengiriman dokumen dan rapat koordinasi',
            'start_datetime'   => now()->subDays(5),
            'end_datetime'     => now()->subDays(3),
            'passenger_count'  => 2,
            'status'           => 'completed',
            'odometer_start'   => 45000,
            'odometer_end'     => 46200,
            'fuel_used'        => 100.5,
        ]);

        BookingApproval::firstOrCreate(
            ['booking_id' => $booking2->id, 'level' => 1],
            ['approver_id' => $approverL1->id, 'status' => 'approved', 'acted_at' => now()->subDays(6)]
        );
        BookingApproval::firstOrCreate(
            ['booking_id' => $booking2->id, 'level' => 2],
            ['approver_id' => $approverL2->id, 'status' => 'approved', 'acted_at' => now()->subDays(6)]
        );

        $this->command->info('✅ Database seeded successfully!');
        $this->command->newLine();
        $this->command->info('📋 Default Accounts:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',         'admin@kendaraan.com',     'admin123'],
                ['Approver L1',   'approver1@kendaraan.com', 'approver123'],
                ['Approver L2',   'approver2@kendaraan.com', 'approver123'],
            ]
        );
    }
}
