<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Libs\Common;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
            ['resazipdev@gmail.com'],
            [],
            []
        );

        // $faker = \Faker\Factory::create();
        // $data = [];
        // $item['name'] = $faker->company;
        // $item['attendants_name'] = $faker->name;
        // $item['email'] = $faker->email;
        // $item['created_at'] = Carbon::now();
        // $item['updated_at'] = Carbon::now();
        // $data[] = $item;
        // DB::table('companies')->insert($data);

        // Common::sendApplicationNoticeMail(
        //     'mails.mail-test',
        //     'test mail 2' . Carbon::now(),
        //     ['resazipdev@gmail.com'],
        //     [],
        //     []
        // );

        // Common::sendApplicationNoticeMail(
        //     'mails.mail-test',
        //     'test mail 3' . Carbon::now(),
        //     ['resazip@gmail.com'],
        //     [],
        //     []
        // );

        // Common::sendApplicationNoticeMail(
        //     'mails.mail-test',
        //     'test mail 4' . Carbon::now(),
        //     ['resazip@gmail.com'],
        //     [],
        //     []
        // );

        return true;
    }
}
