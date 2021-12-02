
<?php
require_once 'vendor/autoload.php';

$ds = DIRECTORY_SEPARATOR;
$input_path     = "input"   . $ds;
$output_path    = "output"  . $ds;

try {
    $_file_service = new \App\Services\FileService($input_path, $output_path);

    $input_file_names = $_file_service->getAllFilenamesFromInputFolder();

    $strategies_collection = $_file_service->getInputFileProcessingStrategiesCollection($input_file_names);


    $_book_repo        = new \App\Repo\BookRepo();
    $_person_repo      = new \App\Repo\PersonRepo();
    $_transaction_repo = new \App\Repo\TransactionRepo($_book_repo, $_person_repo);

    $_processing_service = new \App\Services\ProcessingService($_book_repo, $_person_repo, $_transaction_repo);

    $_processing_service->process($strategies_collection);
    $_processing_service->calculate();

    $output_data      = $_processing_service->getOutputData();
    $output_file_name = $_file_service->getFilenameForOutput();
    $output_strategy  = $_file_service->getOutputFileProcessingStrategy($output_file_name);

    $output_strategy->open();
    $output_strategy->saveData($output_data);

} catch (\Exception $exception) {
    echo "Errors\n";
    echo $exception->getMessage();
    die();
}

echo "Done. please check output file";