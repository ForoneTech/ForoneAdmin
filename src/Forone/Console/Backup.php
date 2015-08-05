<?php
/**
 * User : YuGang Yang
 * Date : 8/5/15
 * Time : 12:41
 * Email: smartydroid@gmail.com
 * QQ/Wechat: 11814169
 */

namespace Forone\Admin\Console;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Orangehill\Iseed\Facades\Iseed;

class Backup extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database backup with ISeed.';

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
    public function fire()
    {
        //
        $this->info('Database Backup Start...');
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        foreach ($tableNames as $tableName) {
            Iseed::generateSeed($tableName);
            $this->info('Seeded: ' . $tableName);
        }
        $this->info('Database Backup End...');
    }

}
