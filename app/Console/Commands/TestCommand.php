<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Test';

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
     * @return int
     */
    public function handle()
    {
        $array1 = [
            'name' => 'Marco',
            'age' => '39',
        ];
        // $postData = array_map(fn ($key, $value) => [$key => $value], $request->input());
        $array2 = array_map(function ($key) use ($array1) {
            return [
                $key => $array1[$key],
            ];
        }, array_keys($array1));

        // $array2 = [];
//
        // foreach($array1 as $key => $value) {
            // $array2[$key] = $value;
        // }
//
        dd($array2);

        return 0;
    }
}
