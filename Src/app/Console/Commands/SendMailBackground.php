<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Libs\Common;
use Illuminate\Console\Command;

class SendMailBackground extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:sendmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Common::sendApplicationNoticeMail(
            'mails.mail-test',
            'test mail' . Carbon::now(),
            ['resazip@gmail.com'],
            [],
            []
        );

        return true;
    }
}
