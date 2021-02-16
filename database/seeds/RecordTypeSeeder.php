<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\RecordType;

class RecordTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // DB::table('record_types')->insert([
        //     'name'=> 'credit',
        // ]);
        // DB::table('record_types')->insert([
        //     'name'=> 'debit',
        // ]);

        $record = new RecordType();
        $record->name = 'credit';
        $record->save();

        $record2 = new RecordType();
        $record2->name = 'debit';
        $record2->save();
    }
}
