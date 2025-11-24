<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DumpUsers extends Command
{
    protected $signature = 'debug:dump-users';
    protected $description = 'Dump users to console';

    public function handle()
    {
        $users = User::all(['id','name','email','role_id','department_id']);
        $this->table(['id','name','email','role_id','department_id'], $users->toArray());
        return 0;
    }
}
