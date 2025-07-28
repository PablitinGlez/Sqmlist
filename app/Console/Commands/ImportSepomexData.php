<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\State;
use App\Models\Municipality;
use App\Models\Colonia;

class ImportSepomexData extends Command
{
    protected $signature = 'import:sepomex {--path= : The path to the SEPOMEX TXT file}';

    protected $description = 'Imports SEPOMEX postal code data into the states, municipalities, and colonias tables.';

    public function handle()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);

        $filePath = $this->option('path');

        if (!$filePath) {
            $this->error('Please provide the path to the SEPOMEX TXT file using --path option.');
            return Command::FAILURE;
        }
        $fullPath = base_path($filePath);

        if (!File::exists($fullPath)) {
            $this->error("The file '{$fullPath}' does not exist.");
            return Command::FAILURE;
        }

        $this->info("Starting SEPOMEX data import from: {$fullPath}");

        $totalLines = $this->countFileLines($fullPath);

        $stateMap = [];
        $municipalityMap = [];
        $coloniasToInsert = [];

        $batchSize = 1000;
        $lineCount = 0;

        $file = new \SplFileObject($fullPath, 'r');
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
        $file->setCsvControl('|');

        if (!$file->eof()) {
            $file->fgetcsv();
            $lineCount++;
        }

        DB::beginTransaction();
        try {
            while (!$file->eof()) {
                $line = $file->current();
                $file->next();
                $lineCount++;

                if (!is_array($line) || count($line) < 14) {
                    continue;
                }

                try {
                    $sourceEncoding = 'ISO-8859-1';
                    $targetEncoding = 'UTF-8';

                    $cleanAndConvert = function ($text) use ($sourceEncoding, $targetEncoding) {
                        $text = mb_convert_encoding($text, $targetEncoding, $sourceEncoding);
                        $text = preg_replace('/[[:cntrl:]]/', '', $text);
                        $text = preg_replace('/\s+/', ' ', $text);
                        return trim($text);
                    };

                    $d_codigo = $cleanAndConvert($line[0]);
                    $d_asenta = $cleanAndConvert($line[1]);
                    $d_tipo_asenta = $cleanAndConvert($line[2]);
                    $D_mnpio = $cleanAndConvert($line[3]);
                    $d_estado = $cleanAndConvert($line[4]);
                    $c_estado = $cleanAndConvert($line[7]);
                    $c_mnpio = $cleanAndConvert($line[11]);
                    $d_zona = $cleanAndConvert($line[13]);

                    if (empty($d_codigo) || empty($d_asenta) || empty($D_mnpio) || empty($d_estado)) {
                        continue;
                    }

                    $currentStateId = $stateMap[$d_estado] ?? null;
                    if (!$currentStateId) {
                        DB::table('states')->insertOrIgnore([
                            'name' => $d_estado,
                            'clave' => $c_estado,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $currentStateId = DB::table('states')->where('name', $d_estado)->value('id');
                        if (!$currentStateId) {
                            continue;
                        }
                        $stateMap[$d_estado] = $currentStateId;
                    }

                    $municipalityKey = $currentStateId . '_' . $D_mnpio;
                    $currentMunicipalityId = $municipalityMap[$municipalityKey] ?? null;
                    if (!$currentMunicipalityId) {
                        DB::table('municipalities')->insertOrIgnore([
                            'state_id' => $currentStateId,
                            'name' => $D_mnpio,
                            'clave' => $c_mnpio,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $currentMunicipalityId = DB::table('municipalities')
                            ->where('state_id', $currentStateId)
                            ->where('name', $D_mnpio)
                            ->value('id');
                        if (!$currentMunicipalityId) {
                            continue;
                        }
                        $municipalityMap[$municipalityKey] = $currentMunicipalityId;
                    }

                    $coloniasToInsert[] = [
                        'municipality_id' => $currentMunicipalityId,
                        'name' => $d_asenta,
                        'postal_code' => $d_codigo,
                        'tipo_asentamiento' => $d_tipo_asenta,
                        'zona' => $d_zona,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (count($coloniasToInsert) >= $batchSize) {
                        DB::table('colonias')->insertOrIgnore($coloniasToInsert);
                        $coloniasToInsert = [];
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (!empty($coloniasToInsert)) {
                DB::table('colonias')->insertOrIgnore($coloniasToInsert);
            }

            DB::commit();

            $this->info("Import completed successfully!");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("An error occurred during import: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function countFileLines($filePath)
    {
        $file = new \SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);
        return $file->key() + 1;
    }
}
