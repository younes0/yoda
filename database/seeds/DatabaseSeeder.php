<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Yoda\Models;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->pgsqlTruncate();
        Model::unguard();

        // admin
        $admin = factory(Models\User::class, 'admin')->create([
            'email'    => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        Model::reguard();
    }

    protected function pgsqlTruncate()
    {
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");

        foreach ($tables as $table) {
            if ($table->table_name !== 'migrations') {
                DB::table($table->table_name)->truncate();
            }
        }
    }
    
    protected function mysqlTruncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach (DB::select('SHOW TABLES') as $table) {

            $column = 'Tables_in_'.Config::get('database.connections.mysql.database');

            if ($table->$column !== 'migrations') {
                DB::table($table->$column)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
