<?php

namespace Forone\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Database Drop Table Start...');

        if (config('database.default') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } else if (config('database.default') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tableNames as $v) {
            Schema::drop($v);
            $this->info('Dropped: ' . $v);
        }

        $this->info('Database Drop Table Ended...');

        if (config('database.default') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } else if (config('database.default') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }
}
