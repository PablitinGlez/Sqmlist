<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\State;
use App\Models\Municipality;
use App\Models\Colonia;

class ImportSepomexData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sepomex {--path= : The path to the SEPOMEX TXT file} {--debug : Enable debug mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports SEPOMEX postal code data into the states, municipalities, and colonias tables.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        ini_set('memory_limit', '2048M');
        set_time_limit(0);

        $filePath = $this->option('path');
        $debug = $this->option('debug');

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
        $this->info("Total lines in file: {$totalLines}");

        $statesToInsert = [];
        $municipalitiesToInsert = [];
        $coloniasToInsert = [];

         $stateMap = []; 
        $municipalityMap = []; 

        $batchSize = 1000;
        $lineCount = 0;
        $processedRecords = 0;
        $skippedRecords = 0;
        $errorRecords = 0; 

       
        $sampleContent = file_get_contents($fullPath, false, null, 0, 10000);
        $encoding = mb_detect_encoding($sampleContent, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        $this->info("Detected encoding: {$encoding}");

    
        $file = new \SplFileObject($fullPath, 'r');
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY); 
        $file->setCsvControl('|'); 

    
        if (!$file->eof()) {
            $file->fgetcsv(); 
            $lineCount++; 
        }

     
        $this->output->progressStart($totalLines - 1); 

        DB::beginTransaction();
        try {
            while (!$file->eof()) {
                $line = $file->current();
                $file->next();
                $lineCount++; 

                if (!is_array($line)) {
                    if ($debug) {
                        $this->warn("Skipping line {$lineCount} - Not an array. Raw: " . print_r($line, true));
                    }
                    $skippedRecords++;
                    $this->output->progressAdvance();
                    continue;
                }
                if (count($line) < 14) { 
                    if ($debug) {
                        $this->warn("Skipping line {$lineCount} - Less than 14 columns (" . count($line) . "). Raw: " . implode('|', $line));
                    }
                    $skippedRecords++;
                    $this->output->progressAdvance();
                    continue;
                }

                try {
                    $sourceEncoding = 'ISO-8859-1'; 
                    $targetEncoding = 'UTF-8';

                    
                    $cleanAndConvert = function($text) use ($sourceEncoding, $targetEncoding) {
                      
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
                        if ($debug) {
                            $this->warn("Skipping line {$lineCount} due to empty required fields after cleaning. State: '{$d_estado}', Municipality: '{$D_mnpio}', Colonia: '{$d_asenta}'.");
                        }
                        $skippedRecords++;
                        $this->output->progressAdvance();
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
                        if ($currentStateId) {
                            $stateMap[$d_estado] = $currentStateId;
                        } else {
                      
                            Log::warning("ImportSepomexData: Failed to get ID for state: '{$d_estado}' (Length: " . mb_strlen($d_estado) . ", Hex: " . bin2hex($d_estado) . ") on line {$lineCount}. Skipping line.");
                            $errorRecords++;
                            $this->output->progressAdvance();
                            continue;
                        }
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
                        if ($currentMunicipalityId) {
                            $municipalityMap[$municipalityKey] = $currentMunicipalityId;
                        } else {
                           
                            Log::warning("ImportSepomexData: Failed to get ID for municipality: '{$D_mnpio}' (State ID: {$currentStateId}, Length: " . mb_strlen($D_mnpio) . ", Hex: " . bin2hex($D_mnpio) . ") on line {$lineCount}. Skipping line.");
                            $errorRecords++;
                            $this->output->progressAdvance();
                            continue;
                        }
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

                    $processedRecords++;

                  
                    if (count($coloniasToInsert) >= $batchSize) {
                        DB::table('colonias')->insertOrIgnore($coloniasToInsert);
                        $coloniasToInsert = [];
                    }

                } catch (\Exception $e) {
                    $errorRecords++;
                    if ($errorRecords <= 100 || $debug) { 
                        $this->error("Error processing line {$lineCount}: " . $e->getMessage());
                        $this->info("Line content: " . json_encode($line));
                    }
                    Log::error("Error processing line {$lineCount}: " . $e->getMessage(), [
                        'line_content' => $line,
                        'exception' => $e
                    ]);
                    $this->output->progressAdvance();
                }
            }

           
            if (!empty($statesToInsert)) {
                DB::table('states')->insertOrIgnore($statesToInsert);
            }
            if (!empty($municipalitiesToInsert)) {
                DB::table('municipalities')->insertOrIgnore($municipalitiesToInsert);
            }
            if (!empty($coloniasToInsert)) {
                DB::table('colonias')->insertOrIgnore($coloniasToInsert);
            }

            DB::commit();
            $this->output->progressFinish();
            
          
            $this->info("Import completed successfully!");
            $this->info("Lines processed (from file): {$lineCount}");
            $this->info("Records successfully processed (inserted/found): {$processedRecords}");
            $this->info("Records skipped (invalid format/empty fields): {$skippedRecords}");
            $this->info("Records with errors (ID lookup failed): {$errorRecords}");
            
        
            $stateCount = DB::table('states')->count();
            $municipalityCount = DB::table('municipalities')->count();
            $coloniaCount = DB::table('colonias')->count();
            
            $this->info("States in database: {$stateCount}");
            $this->info("Municipalities in database: {$municipalityCount}");
            $this->info("Colonias in database: {$coloniaCount}");
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->output->progressFinish();
            $this->error("An error occurred during import: " . $e->getMessage());
            Log::error("SEPOMEX Import Error: " . $e->getMessage(), ['exception' => $e]);
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
