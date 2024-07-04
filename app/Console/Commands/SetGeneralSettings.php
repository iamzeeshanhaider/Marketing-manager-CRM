<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SettingsModel;

class SetGeneralSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:general-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $Settings = SettingsModel::first();
        $data = $Settings->toArray();
        if ($data) {
            $pattern = '/([^\=]*)\=[^\n]*/';
            $envFile = base_path() . '/.env';
            $lines = file($envFile);
            $newLines = [];
            foreach ($lines as $line) {
                preg_match($pattern, $line, $matches);
                if (!count($matches)) {
                    $newLines[] = $line;
                    continue;
                }
                $key = trim($matches[1]);
                $upperKey = strtoupper($key);
                if (!key_exists($key, $data)) {
                    $newLines[] = $line;
                    continue;
                }
                $line = "$upperKey={$data[$key]}\n";
                unset($data[$key]);
                $newLines[] = $line;
            }
            file_put_contents($envFile, implode('', $newLines));
            $this->info('Settings loaded from the database.');
        } else {
            $this->error('Settings not found in the database.');
        }
    }
}
